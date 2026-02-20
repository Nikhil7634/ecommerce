<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketReplyMail;
use App\Mail\TicketStatusUpdateMail;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of support tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo', 'replies'])
            ->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by assigned admin
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->paginate(20);
        
        // Get unique values for filters
        $statuses = SupportTicket::select('status')
            ->distinct()
            ->pluck('status');
            
        $priorities = SupportTicket::select('priority')
            ->distinct()
            ->pluck('priority');
            
        $categories = SupportTicket::select('category')
            ->distinct()
            ->pluck('category');
            
        // Get admin users for assignment
        $admins = User::where('role', 'admin')
            ->where('status', 'active')
            ->get(['id', 'name', 'email']);

        // Get statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'unassigned' => SupportTicket::whereNull('assigned_to')->count(),
            'high_priority' => SupportTicket::where('priority', 'high')->count(),
        ];

        return view('admin.support.tickets', compact('tickets', 'statuses', 'priorities', 'categories', 'admins', 'stats'));
    }

    /**
     * Display the specified support ticket.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'assignedTo', 'replies.user'])
            ->findOrFail($id);
            
        // Mark as viewed by admin
        if (auth()->user()->role === 'admin') {
            $ticket->update(['last_viewed_by_admin' => now()]);
        }
        
        // Get suggested similar tickets
        $similarTickets = SupportTicket::where('category', $ticket->category)
            ->where('id', '!=', $ticket->id)
            ->where('status', '!=', 'closed')
            ->latest()
            ->limit(5)
            ->get(['id', 'ticket_id', 'subject', 'status']);
            
        // Get admin users for reassignment
        $admins = User::where('role', 'admin')
            ->where('status', 'active')
            ->where('id', '!=', auth()->id())
            ->get(['id', 'name', 'email']);

        return view('admin.support.ticket-show', compact('ticket', 'similarTickets', 'admins'));
    }

    /**
     * Reply to a support ticket.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'attachments.*' => 'nullable|file|max:5120', // 5MB max per file
            'private_note' => 'boolean'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        // Create reply
        $reply = SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_private' => $request->boolean('private_note', false),
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('ticket-attachments/' . $ticket->id, 'public');
                
                $reply->attachments()->create([
                    'filename' => $file->getClientOriginalName(),
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Update ticket status and last reply
        $updates = [
            'last_reply_at' => now(),
            'last_reply_by' => auth()->id(),
        ];
        
        // If admin is replying and ticket is not closed, set to in_progress
        if (auth()->user()->role === 'admin' && $ticket->status !== 'closed') {
            $updates['status'] = 'in_progress';
            $updates['assigned_to'] = auth()->id(); // Auto-assign if replying
        }
        
        $ticket->update($updates);

        // Send email notification to user if not private note
        if (!$request->boolean('private_note') && $ticket->user_id && auth()->user()->role === 'admin') {
            try {
                Mail::to($ticket->user->email)->send(new TicketReplyMail($ticket, $reply));
            } catch (\Exception $e) {
                // Log email error but don't break the flow
                \Log::error('Failed to send ticket reply email: ' . $e->getMessage());
            }
        }

        return redirect()->back()
            ->with('success', 'Reply sent successfully.');
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,on_hold,closed',
            'note' => 'nullable|string|max:500'
        ]);

        $ticket = SupportTicket::with('user')->findOrFail($id);
        $oldStatus = $ticket->status;
        $newStatus = $request->status;
        
        $ticket->update([
            'status' => $newStatus,
            'closed_at' => $newStatus === 'closed' ? now() : null,
            'closed_by' => $newStatus === 'closed' ? auth()->id() : null,
        ]);

        // Add status change note
        if ($request->filled('note')) {
            SupportTicketReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => "Status changed from {$oldStatus} to {$newStatus}. Note: " . $request->note,
                'is_private' => false,
                'is_system' => true,
            ]);
        }

        // Send email notification for status change
        if ($ticket->user_id && $oldStatus !== $newStatus) {
            try {
                Mail::to($ticket->user->email)->send(new TicketStatusUpdateMail($ticket, $oldStatus, $newStatus));
            } catch (\Exception $e) {
                \Log::error('Failed to send ticket status update email: ' . $e->getMessage());
            }
        }

        return redirect()->back()
            ->with('success', "Ticket status updated to {$newStatus}.");
    }

    /**
     * Assign ticket to admin.
     */
    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $assignedAdmin = User::find($request->assigned_to);
        
        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress',
        ]);

        // Add assignment note
        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Ticket assigned to {$assignedAdmin->name}.",
            'is_private' => false,
            'is_system' => true,
        ]);

        // TODO: Send notification to assigned admin

        return redirect()->back()
            ->with('success', "Ticket assigned to {$assignedAdmin->name}.");
    }

    /**
     * Update ticket priority.
     */
    public function updatePriority(Request $request, $id)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,critical'
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $oldPriority = $ticket->priority;
        
        $ticket->update(['priority' => $request->priority]);

        // Add priority change note
        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => "Priority changed from {$oldPriority} to {$request->priority}.",
            'is_private' => false,
            'is_system' => true,
        ]);

        return redirect()->back()
            ->with('success', "Ticket priority updated to {$request->priority}.");
    }

    /**
     * Get ticket statistics for dashboard.
     */
    public function dashboardStats()
    {
        $stats = [
            'total_tickets' => SupportTicket::count(),
            'open_tickets' => SupportTicket::where('status', 'open')->count(),
            'in_progress_tickets' => SupportTicket::where('status', 'in_progress')->count(),
            'closed_tickets' => SupportTicket::where('status', 'closed')->count(),
            'unassigned_tickets' => SupportTicket::whereNull('assigned_to')->count(),
            'avg_response_time' => SupportTicket::whereNotNull('first_response_at')
                ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_minutes'))
                ->first()->avg_minutes ?? 0,
            'recent_tickets' => SupportTicket::with('user')
                ->latest()
                ->limit(10)
                ->get(),
            'tickets_by_category' => SupportTicket::select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
            'tickets_by_status' => SupportTicket::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Bulk update ticket status.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
            'action' => 'required|in:assign,status,priority,delete',
            'value' => 'required'
        ]);

        $ticketIds = $request->ticket_ids;
        $action = $request->action;
        $value = $request->value;
        $count = 0;

        switch ($action) {
            case 'assign':
                SupportTicket::whereIn('id', $ticketIds)
                    ->update(['assigned_to' => $value, 'status' => 'in_progress']);
                $count = count($ticketIds);
                $message = "$count tickets assigned.";
                break;
                
            case 'status':
                SupportTicket::whereIn('id', $ticketIds)->update(['status' => $value]);
                $count = count($ticketIds);
                $message = "$count tickets status updated.";
                break;
                
            case 'priority':
                SupportTicket::whereIn('id', $ticketIds)->update(['priority' => $value]);
                $count = count($ticketIds);
                $message = "$count tickets priority updated.";
                break;
                
            case 'delete':
                SupportTicket::whereIn('id', $ticketIds)->delete();
                $count = count($ticketIds);
                $message = "$count tickets deleted.";
                break;
        }

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Export tickets to CSV.
     */
    public function export(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo'])
            ->latest();

        // Apply filters if any
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $tickets = $query->get();

        $fileName = 'support-tickets-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Ticket ID', 
                'Subject', 
                'User', 
                'Email', 
                'Status', 
                'Priority', 
                'Category',
                'Assigned To',
                'Created At',
                'Last Reply At',
                'Closed At'
            ]);

            // Add rows
            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->ticket_id,
                    $ticket->subject,
                    $ticket->user ? $ticket->user->name : 'N/A',
                    $ticket->user ? $ticket->user->email : 'N/A',
                    ucfirst($ticket->status),
                    ucfirst($ticket->priority),
                    $ticket->category,
                    $ticket->assignedTo ? $ticket->assignedTo->name : 'Unassigned',
                    $ticket->created_at->format('Y-m-d H:i:s'),
                    $ticket->last_reply_at ? $ticket->last_reply_at->format('Y-m-d H:i:s') : 'Never',
                    $ticket->closed_at ? $ticket->closed_at->format('Y-m-d H:i:s') : 'Not Closed'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}