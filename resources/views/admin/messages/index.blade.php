<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Messages</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Messages</li>
            </ol>
        </nav>
    </div>

    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ $type == 'all' ? 'active' : '' }}" 
                               href="{{ route('admin.messages.index', ['type' => 'all']) }}">
                                All Messages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $type == 'unread' ? 'active' : '' }}" 
                               href="{{ route('admin.messages.index', ['type' => 'unread']) }}">
                                Unread 
                                @if($unreadCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Messages</h5>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Message</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $message)
                                @php
                                    $otherUser = $message->sender_id == Auth::id() ? $message->receiver : $message->sender;
                                @endphp
                                <tr class="{{ !$message->is_read && $message->receiver_id == Auth::id() ? 'table-active' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-2">
                                                <img src="{{ $otherUser->avatar_url ?? asset('admin-assets/assets/images/avatar.png') }}" 
                                                     alt="{{ $otherUser->name }}" 
                                                     width="40" height="40" 
                                                     class="rounded-circle">
                                            </div>
                                            <div>
                                                <strong>{{ $otherUser->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($otherUser->role) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {{ Str::limit($message->message, 100) }}
                                            @if($message->type == 'file')
                                            <br>
                                            <small class="text-muted">
                                                <i class="fa-light fa-paperclip me-1"></i> Attachment
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        {{ $message->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $message->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($message->sender_id == Auth::id())
                                            <span class="badge bg-{{ $message->is_read ? 'success' : 'secondary' }}">
                                                {{ $message->is_read ? 'Delivered' : 'Sent' }}
                                            </span>
                                        @else
                                            @if($message->is_read)
                                                <span class="badge bg-success">Read</span>
                                            @else
                                                <span class="badge bg-warning">Unread</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa-light fa-eye"></i> View
                                        </a>
                                        @if($message->room)
                                        <a href="{{ route('admin.support.chat', ['room' => $message->room->id]) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fa-light fa-comment"></i> Chat
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center">
                                        <i class="mb-3 fa-light fa-message fa-3x text-muted"></i>
                                        <h5>No messages found</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($messages->hasPages())
                    <div class="mt-4">
                        {{ $messages->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')