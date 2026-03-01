<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Support Chat</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Support</a></li>
                <li class="breadcrumb-item ">Chat</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-light fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-light fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="chatting-panel">
        <div class="d-flex">
            <!-- Left Panel - Chat List -->
            <div class="panel border-end rounded-0" style="width: 350px; min-width: 350px;">
                <div class="p-0 panel-body">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0">Conversations</h5>
                        <small class="text-muted">{{ $chatRooms->count() }} chats</small>
                    </div>
                    
                    <div class="message-list" style="height: calc(100vh - 250px); overflow-y: auto;">
                        <div class="scrollable">
                            @forelse($chatRooms as $room)
                            <a href="{{ route('admin.support.chat', ['room' => $room->id]) }}" 
                               class="text-decoration-none">
                                <div class="single-message {{ $room->unread_count > 0 ? 'unread' : '' }} {{ $currentRoom && $currentRoom->id == $room->id ? 'active' : '' }}">
                                    <div class="avatar">
                                        <img src="{{ $room->user->avatar_url ?? asset('admin-assets/assets/images/avatar.png') }}" alt="{{ $room->user->name }}">
                                        <span class="active-status {{ $room->user->updated_at >= now()->subMinutes(30) ? 'active' : '' }}"></span>
                                    </div>
                                    <div class="part-txt">
                                        <div class="top">
                                            <span class="user-name">{{ $room->user->name }}</span>
                                            <span class="msg-time">
                                                @if($room->last_message_at)
                                                    {{ $room->last_message_at->diffForHumans() }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="msg-short">
                                            <span>
                                                @if($room->last_message)
                                                    @if($room->last_message->sender_id == Auth::id())
                                                        <span class="text-muted">You: </span>
                                                    @endif
                                                    {{ Str::limit($room->last_message->message, 50) }}
                                                @else
                                                    No messages yet
                                                @endif
                                            </span>
                                        </div>
                                        @if($room->unread_count > 0)
                                        <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px;">
                                            {{ $room->unread_count }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            @empty
                            <div class="p-4 text-center">
                                <p class="text-muted">No active conversations</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Panel - Chat Area -->
            <div class="panel rounded-0 position-relative flex-grow-1">
                @if($currentRoom)
                <div class="chatting-area h-100 d-flex flex-column">
                    <!-- Chat Header -->
                    <div class="panel-body border-bottom">
                        <div class="chat-top-bar">
                            <div class="user">
                                <button class="back-to-all-chat btn-flush fs-14 d-md-none" onclick="window.location='{{ route('admin.support.chat') }}'">
                                    <i class="fa-light fa-arrow-left"></i>
                                </button>
                                <div class="avatar">
                                    <img src="{{ $currentRoom->user->avatar_url ?? asset('admin-assets/assets/images/avatar-2.png') }}" alt="{{ $currentRoom->user->name }}">
                                </div>
                                <div class="part-txt">
                                    <span class="user-name">{{ $currentRoom->user->name }}</span>
                                    <span class="active-status {{ $currentRoom->user->updated_at >= now()->subMinutes(30) ? 'active' : '' }} small">
                                        {{ $currentRoom->user->updated_at >= now()->subMinutes(30) ? 'Online' : 'Offline' }}
                                    </span>
                                </div>
                            </div>
                            <div class="chatting-panel-top-btns">
                                <div class="digi-dropdown dropdown">
                                    <button class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="dropdown">
                                        <i class="fa-regular fa-ellipsis"></i>
                                    </button>
                                    <ul class="digi-dropdown-menu dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                        <li class="p-0">
                                            <form action="{{ route('admin.support.chat.close', $currentRoom->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to close this chat?')">
                                                    <span class="dropdown-icon"><i class="fa-light fa-times"></i></span> Close Chat
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="panel-body msg-area flex-grow-1" id="messageArea" style="overflow-y: auto; height: 400px;">
                        <div class="scrollable" id="messagesContainer">
                            @php
                                $lastDate = null;
                            @endphp
                            @foreach($messages as $message)
                                @php
                                    $messageDate = $message->created_at->format('Y-m-d');
                                @endphp
                                @if($lastDate != $messageDate)
                                    <div class="day-divider">
                                        <span>
                                            @if($message->created_at->isToday())
                                                Today
                                            @elseif($message->created_at->isYesterday())
                                                Yesterday
                                            @else
                                                {{ $message->created_at->format('d M Y') }}
                                            @endif
                                        </span>
                                    </div>
                                    @php $lastDate = $messageDate; @endphp
                                @endif

                                <div class="single-message {{ $message->sender_id == Auth::id() ? 'outgoing' : '' }}" data-message-id="{{ $message->id }}">
                                    @if($message->sender_id != Auth::id())
                                    <div class="avatar">
                                        <img src="{{ $message->sender->avatar_url ?? asset('admin-assets/assets/images/avatar-2.png') }}" alt="{{ $message->sender->name }}">
                                    </div>
                                    @endif
                                    
                                    <div class="msg-box">
                                        <div class="msg-box-inner">
                                            <div class="msg-option">
                                                <span class="msg-time">{{ $message->created_at->format('h:i A') }}</span>
                                            </div>
                                            
                                            @if($message->type == 'text')
                                                <p>{{ $message->message }}</p>
                                            @elseif($message->type == 'file' && $message->attachment_path)
                                                <div class="attachment">
                                                    <a href="{{ asset('storage/' . $message->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fa-light fa-file me-2"></i> View Attachment
                                                    </a>
                                                </div>
                                            @endif
                                            
                                            @if($message->sender_id == Auth::id())
                                            <span class="sent-status {{ $message->is_read ? 'seen' : '' }}" 
                                                  title="{{ $message->is_read ? 'Seen' : 'Sent' }}">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($message->sender_id == Auth::id())
                                    <div class="avatar">
                                        <img src="{{ Auth::user()->avatar_url ?? asset('admin-assets/assets/images/admin.png') }}" alt="You">
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Message Input Area -->
                    <div style="position: sticky; bottom: 15px; width: 100%;" class="panel-body msg-type-area border-top">
                        <form style="width: 100%" id="chatForm" onsubmit="return sendMessage(event)">
                            @csrf
                            <input type="hidden" name="room_id" id="room_id" value="{{ $currentRoom->id }}">
                            
                            <div class="gap-2 d-flex align-items-center">
                                <label for="chatAttachment" class="mb-0 btn btn-icon btn-outline-primary" onclick="document.getElementById('chatAttachment').click(); return false;">
                                    <i class="fa-light fa-paperclip"></i>
                                </label>
                                <input type="file" class="d-none" id="chatAttachment" onchange="uploadAttachment(event)">
                                
                                <input type="text" 
                                       class="form-control chat-input" 
                                       id="messageInput" 
                                       placeholder="Type your message..." 
                                       autocomplete="off"
                                       required>
                                
                                <button type="submit" class="btn btn-icon btn-outline-primary">
                                    <i class="fa-light fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="d-flex align-items-center justify-content-center h-100">
                    <div class="p-5 text-center">
                        <i class="mb-3 fa-light fa-message fa-4x text-muted"></i>
                        <h5>Select a conversation to start chatting</h5>
                        <p class="text-muted">Choose a conversation from the left sidebar</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Panel - User Info -->
            @if($currentRoom)
            <div class="panel border-start rounded-0" style="width: 280px; min-width: 280px;">
                <div class="panel-body border-bottom">
                    <div class="text-center user-short">
                        <div class="mx-auto mb-3 avatar avatar-lg" style="width: 80px; height: 80px;">
                            <img src="{{ $currentRoom->user->avatar_url ?? asset('admin-assets/assets/images/avatar-2.png') }}" 
                                 alt="{{ $currentRoom->user->name }}"
                                 class="w-100 h-100 rounded-circle object-fit-cover">
                        </div>
                        <h5>{{ $currentRoom->user->name }}</h5>
                        <p class="mb-2 text-muted small">{{ $currentRoom->user->email }}</p>
                        <span class="badge bg-{{ $currentRoom->user->role == 'buyer' ? 'primary' : 'success' }}">
                            {{ ucfirst($currentRoom->user->role) }}
                        </span>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="scrollable" style="height: calc(100vh - 450px); overflow-y: auto;">
                        <div class="mb-20 chatting-option">
                            <h6 class="mb-3">User Information</h6>
                            <ul class="list-unstyled">
                                @if($currentRoom->user->phone)
                                <li class="mb-3 d-flex align-items-center">
                                    <span class="text-muted" style="width: 80px;"><i class="fa-light fa-phone me-2"></i>Phone:</span>
                                    <span>{{ $currentRoom->user->phone }}</span>
                                </li>
                                @endif
                                <li class="mb-3 d-flex align-items-center">
                                    <span class="text-muted" style="width: 80px;"><i class="fa-light fa-envelope me-2"></i>Email:</span>
                                    <span>{{ $currentRoom->user->email }}</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <span class="text-muted" style="width: 80px;"><i class="fa-light fa-calendar me-2"></i>Joined:</span>
                                    <span>{{ $currentRoom->user->created_at->format('d M Y') }}</span>
                                </li>
                                @if($currentRoom->product)
                                <li class="mb-3">
                                    <span class="text-muted"><i class="fa-light fa-box me-2"></i>Product:</span>
                                    <div class="p-2 mt-2 rounded bg-light">
                                        <strong>{{ $currentRoom->product->name }}</strong>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>

                        <div class="mt-4 chatting-option">
                            <h6 class="mb-3">Quick Actions</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="mailto:{{ $currentRoom->user->email }}" class="p-2 rounded d-flex align-items-center text-decoration-none hover-bg-light">
                                        <span class="me-3"><i class="fa-light fa-envelope text-primary"></i></span>
                                        Send Email
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- main content end -->

@include('admin.layouts.footer')

<style>
    .single-message {
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
        display: flex;
        gap: 12px;
        position: relative;
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .single-message:hover {
        background-color: #f8f9fa;
    }
    
    .single-message.active {
        background-color: #e7f1ff;
        border-left: 3px solid #0d6efd;
    }
    
    .single-message.unread {
        background-color: #fff3e0;
    }
    
    .single-message .avatar {
        width: 45px;
        height: 45px;
        position: relative;
        flex-shrink: 0;
    }
    
    .single-message .avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .single-message .avatar .active-status {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #6c757d;
        border: 2px solid #fff;
    }
    
    .single-message .avatar .active-status.active {
        background-color: #28a745;
    }
    
    .single-message .part-txt {
        flex: 1;
        min-width: 0;
    }
    
    .single-message .top {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    
    .single-message .user-name {
        font-weight: 600;
        color: #212529;
    }
    
    .single-message .msg-time {
        font-size: 11px;
        color: #6c757d;
    }
    
    .single-message .msg-short {
        font-size: 13px;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .single-message.outgoing {
        flex-direction: row-reverse;
    }
    
    .single-message.outgoing .msg-box {
        align-items: flex-end;
    }
    
    .msg-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-width: 70%;
    }
    
    .msg-box-inner {
        position: relative;
        padding: 10px 15px;
        
        border-radius: 15px;
    }
    
    .single-message.outgoing .msg-box-inner {
        background-color: #0d6efd;
        color: white;
    }
    
    .single-message.outgoing .msg-box-inner p {
        color: white;
    }
    
    .msg-option {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .sent-status {
        position: absolute;
        bottom: 5px;
        right: 5px;
        font-size: 12px;
        color: #6c757d;
    }
    
    .sent-status.seen {
        color: #0d6efd;
    }
    
    .day-divider {
        text-align: center;
        margin: 20px 0;
        position: relative;
    }
    
    .day-divider::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background-color: #e9ecef;
    }
    
    .day-divider span {
        background-color: #fff;
        padding: 0 15px;
        position: relative;
        z-index: 1;
        font-size: 12px;
        color: #6c757d;
    }
    
    .chat-input {
        border-radius: 30px;
        padding: 10px 20px;
    }
    
    .btn-icon {
        width: 40px;
        height: 40px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .chatting-panel {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    
    .msg-area {
        padding: 20px;
        background-color: #f8f9fa;
    }
    
    .msg-type-area {
        padding: 15px 20px;
        
        border-top: 1px solid #e9ecef;
    }
    
    .msg-type-area form {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .chat-top-bar {
        padding: 15px 20px;
        
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .user {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .user-short {
        text-align: center;
    }
    
    .user-short .avatar-lg {
        width: 80px;
        height: 80px;
        margin: 0 auto 10px;
    }
    
    .user-short .avatar-lg img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .user-option {
        margin-top: 15px;
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .chatting-option ul {
        padding: 0;
        margin: 0;
    }
    
    .chatting-option ul li {
        list-style: none;
        margin-bottom: 10px;
    }
    
    .chatting-option ul li a {
        color: #212529;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s;
    }
    
    .chatting-option ul li a:hover {
        background-color: #f8f9fa;
    }
    
    .chatting-option ul li a span {
        width: 30px;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .chatting-panel .d-flex {
            flex-direction: column;
        }
        
        .panel {
            width: 100% !important;
        }
    }
</style>

 
 
<script>
    let currentRoomId = {{ $currentRoom->id ?? 'null' }};
    let messagePolling = null;

    document.addEventListener('DOMContentLoaded', function() {
        scrollToBottom();
        
        if (currentRoomId) {
            startMessagePolling();
            markMessagesAsRead();
        }

        // Enter key to send message
        const messageInput = document.getElementById('messageInput');
        if (messageInput) {
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage(e);
                }
            });
        }

        // Also attach form submit handler
        const chatForm = document.getElementById('chatForm');
        if (chatForm) {
            chatForm.addEventListener('submit', sendMessage);
        }
    });

    function sendMessage(event) {
        event.preventDefault();
        
        const input = document.getElementById('messageInput');
        if (!input) {
            console.error('Message input not found');
            return false;
        }
        
        const message = input.value.trim();
        
        if (!message) return false;

        const roomId = document.getElementById('room_id')?.value;
        if (!roomId) {
            console.error('Room ID not found');
            return false;
        }

        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('message', message);
        formData.append('_token', '{{ csrf_token() }}');

        // Disable input while sending
        input.disabled = true;

        fetch('{{ route("admin.support.chat.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                appendMessage(data.message);
                input.value = '';
                scrollToBottom();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending message: ' + error.message);
        })
        .finally(() => {
            input.disabled = false;
            input.focus();
        });

        return false;
    }

    function startMessagePolling() {
        if (messagePolling) {
            clearInterval(messagePolling);
        }
        messagePolling = setInterval(checkNewMessages, 3000);
    }

    function checkNewMessages() {
        if (!currentRoomId) return;

        fetch('{{ url("/admin/support/chat/messages") }}/' + currentRoomId, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateMessages(data.messages);
            }
        })
        .catch(error => console.error('Error checking messages:', error));
    }

    function updateMessages(messages) {
        const container = document.getElementById('messagesContainer');
        if (!container) return;

        const existingIds = new Set();
        
        container.querySelectorAll('[data-message-id]').forEach(msg => {
            const id = msg.getAttribute('data-message-id');
            if (id) existingIds.add(parseInt(id));
        });

        let hasNewMessages = false;

        messages.forEach(message => {
            if (!existingIds.has(message.id)) {
                appendMessage(message);
                hasNewMessages = true;
            }
        });

        if (hasNewMessages) {
            scrollToBottom();
            markMessagesAsRead();
        }
    }

    function appendMessage(message) {
        const container = document.getElementById('messagesContainer');
        if (!container) return;

        const isOutgoing = message.sender_id == {{ Auth::id() }};
        
        // Check if we need to add a date divider
        const lastMessage = container.lastElementChild;
        const today = new Date().toDateString();
        const messageDate = message.date ? new Date(message.date).toDateString() : today;
        
        const messageHtml = `
            <div class="single-message ${isOutgoing ? 'outgoing' : ''}" data-message-id="${message.id}">
                ${!isOutgoing ? `
                <div class="avatar">
                    <img src="${message.sender_avatar || '{{ asset("admin-assets/assets/images/avatar.png") }}'}" alt="${message.sender_name || 'User'}">
                </div>
                ` : ''}
                
                <div class="msg-box">
                    <div class="msg-box-inner">
                        <div class="msg-option">
                            <span class="msg-time">${message.time || new Date().toLocaleTimeString()}</span>
                        </div>
                        <p>${escapeHtml(message.text || message.message || '')}</p>
                        ${isOutgoing ? `
                        <span class="sent-status" title="Sent">
                            <i class="fa-solid fa-circle-check"></i>
                        </span>
                        ` : ''}
                    </div>
                </div>
                
                ${isOutgoing ? `
                <div class="avatar">
                    <img src="${message.sender_avatar || '{{ Auth::user()->avatar_url ?? asset("admin-assets/assets/images/admin.png") }}'}" alt="You">
                </div>
                ` : ''}
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', messageHtml);
    }

    function markMessagesAsRead() {
        if (!currentRoomId) return;

        fetch('{{ url("/admin/support/chat/mark-read") }}/' + currentRoomId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update unread indicators in the left panel
                const currentRoomLink = document.querySelector(`a[href*="room=${currentRoomId}"] .single-message`);
                if (currentRoomLink) {
                    const badge = currentRoomLink.querySelector('.badge');
                    if (badge) badge.remove();
                }
            }
        })
        .catch(error => console.error('Error marking as read:', error));
    }

    function uploadAttachment(event) {
        const file = event.target.files[0];
        if (!file) return;

        const roomId = document.getElementById('room_id')?.value;
        if (!roomId) {
            alert('No active chat room');
            return;
        }

        const formData = new FormData();
        formData.append('room_id', roomId);
        formData.append('attachment', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading state
        const uploadBtn = document.querySelector('label[for="chatAttachment"]');
        const originalHtml = uploadBtn.innerHTML;
        uploadBtn.innerHTML = '<i class="fa-light fa-spinner fa-spin"></i>';
        uploadBtn.style.pointerEvents = 'none';

        fetch('{{ route("admin.support.chat.upload") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear file input
                document.getElementById('chatAttachment').value = '';
                // Reload messages to show attachment
                checkNewMessages();
            } else {
                alert('Error uploading file: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error uploading file');
        })
        .finally(() => {
            uploadBtn.innerHTML = originalHtml;
            uploadBtn.style.pointerEvents = 'auto';
        });
    }

    function scrollToBottom() {
        const messageArea = document.getElementById('messageArea');
        if (messageArea) {
            messageArea.scrollTop = messageArea.scrollHeight;
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        if (messagePolling) {
            clearInterval(messagePolling);
        }
    });
</script>
 