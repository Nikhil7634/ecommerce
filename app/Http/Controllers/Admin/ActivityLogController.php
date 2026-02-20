<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('user_agent', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by log type
        if ($request->filled('type')) {
            $query->where('log_type', $request->type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        
        // Get unique log types for filter
        $logTypes = ActivityLog::select('log_type')
            ->distinct()
            ->pluck('log_type');
            
        // Get users with logs
        $usersWithLogs = \App\Models\User::whereIn('id', function($query) {
            $query->select('user_id')
                  ->from('activity_logs')
                  ->distinct();
        })->get(['id', 'name', 'email']);

        return view('admin.activity-logs.index', compact('logs', 'logTypes', 'usersWithLogs'));
    }

    /**
     * Display the specified activity log.
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);
        
        // Decode properties if they're JSON
        if ($log->properties) {
            $log->properties = json_decode($log->properties, true);
        }
        
        // Parse user agent
        $log->parsed_user_agent = $this->parseUserAgent($log->user_agent);
        
        return view('admin.activity-logs.show', compact('log'));
    }

    /**
     * Clear old activity logs.
     */
    public function clearOldLogs(Request $request)
    {
        $days = $request->days ?? 30;
        
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();
        
        return redirect()->route('admin.activity-logs.index')
            ->with('success', "Successfully cleared $deleted old activity logs (older than $days days).");
    }

    /**
     * Export activity logs to CSV.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user')
            ->latest();

        // Apply filters if any
        if ($request->filled('type')) {
            $query->where('log_type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->get();

        $fileName = 'activity-logs-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 
                'User', 
                'Email', 
                'Description', 
                'Log Type', 
                'IP Address', 
                'User Agent', 
                'Created At'
            ]);

            // Add rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'Guest',
                    $log->user ? $log->user->email : '',
                    $log->description,
                    $log->log_type,
                    $log->ip_address,
                    $this->parseUserAgent($log->user_agent),
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get dashboard statistics for activity logs.
     */
    public function dashboardStats()
    {
        $stats = [
            'total_logs' => ActivityLog::count(),
            'today_logs' => ActivityLog::whereDate('created_at', today())->count(),
            'user_logs' => ActivityLog::whereNotNull('user_id')->count(),
            'guest_logs' => ActivityLog::whereNull('user_id')->count(),
            'top_activities' => ActivityLog::select('log_type', DB::raw('count(*) as count'))
                ->groupBy('log_type')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'recent_logs' => ActivityLog::with('user')
                ->latest()
                ->limit(10)
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Parse user agent string into readable format.
     */
    private function parseUserAgent($userAgent)
    {
        if (!$userAgent) return 'Unknown';
        
        // Simple parsing - you might want to use a proper package like jenssegers/agent
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
            return 'Apple Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Microsoft Edge';
        } elseif (strpos($userAgent, 'Opera') !== false) {
            return 'Opera';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        }
        
        return $userAgent;
    }
}