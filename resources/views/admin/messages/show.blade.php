<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Message Details</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.messages.index') }}">Messages</a></li>
                <li class="breadcrumb-item ">Message #{{ $message->id }}</li>
            </ol>
        </nav>
        <div class="gap-2 mt-2 d-flex">
            <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">
                <i class="fa-light fa-arrow-left me-2"></i>Back to Messages
            </a>
        </div>
    </div>  

    <div class="row">
        <div class="mx-auto col-md-8">
            <div class="panel">
                <div class="panel-header">
                    <h5>Message Details</h5>
                </div>
                <div class="panel-body">
                    @php
                        $otherUser = $message->sender_id == Auth::id() ? $message->receiver : $message->sender;
                    @endphp

                    <div class="p-3 mb-4 rounded message-header bg-light">
                        <div class="d-flex align-items-center">
                            <div class="avatar me-3">
                                <img src="{{ $otherUser->avatar_url ?? asset('admin-assets/assets/images/avatar.png') }}" 
                                     alt="{{ $otherUser->name }}" 
                                     width="60" height="60" 
                                     class="rounded-circle">
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $otherUser->name }}</h4>
                                <p class="mb-1 ">{{ $otherUser->email }}</p>
                                <span class="badge bg-{{ $otherUser->role == 'admin' ? 'primary' : 'success' }}">
                                    {{ ucfirst($otherUser->role) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 message-body">
                        <div class="mb-3">
                            <strong>Sent:</strong> 
                            {{ $message->created_at->format('F d, Y \a\t h:i A') }}
                        </div>
                        
                        @if($message->type == 'text')
                            <div class="p-4 rounded bg-light">
                                {{ $message->message }}
                            </div>
                        @elseif($message->type == 'file' && $message->attachment_path)
                            <div class="p-4 rounded bg-light">
                                <p class="mb-2">{{ $message->message }}</p>
                                <a href="{{ asset('storage/' . $message->attachment_path) }}" 
                                   target="_blank" 
                                   class="btn btn-primary">
                                    <i class="fa-light fa-download me-2"></i>
                                    Download Attachment
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="message-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Status:</strong>
                                @if($message->sender_id == Auth::id())
                                    <span class="badge bg-{{ $message->is_read ? 'success' : 'secondary' }} ms-2">
                                        {{ $message->is_read ? 'Read ' . ($message->read_at ? $message->read_at->diffForHumans() : '') : 'Sent' }}
                                    </span>
                                @else
                                    <span class="badge bg-{{ $message->is_read ? 'success' : 'warning' }} ms-2">
                                        {{ $message->is_read ? 'Read' : 'Unread' }}
                                    </span>
                                @endif
                            </div>
                            
                            @if($message->room)
                            <a href="{{ route('admin.support.chat', ['room' => $message->room->id]) }}" 
                               class="btn btn-primary">
                                <i class="fa-light fa-comment me-2"></i>Continue Conversation
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')