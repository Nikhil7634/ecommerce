<div>
    <!-- Toast Container - Top Right Professional Stacking -->
    <div class="top-0 p-4 toast-container position-fixed end-0" style="z-index: 9999;">
        @foreach($notifications as $notification)
            <div
                wire:key="notification-{{ $notification['id'] }}"
                class="overflow-hidden shadow-lg toast rounded-4"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
                data-bs-autohide="true"
                data-bs-delay="2000"
                data-bs-animation="true"
                x-data
                x-init="new bootstrap.Toast($el).show()"
                x-on:hidden.bs.toast="$wire.removeNotification('{{ $notification['id'] }}')"
                style="min-width: 360px; animation: slideInRight 0.5s ease-out forwards;"
                @class([
                    'text-bg-success' => $notification['type'] === 'success',
                    'text-bg-danger'  => $notification['type'] === 'error',
                    'text-bg-warning text-dark' => $notification['type'] === 'warning', // dark text for warning
                    'text-bg-info'    => $notification['type'] === 'info',
                ])
            >
                <div class="d-flex">
                    <div class="px-4 py-4 toast-body">
                        <div class="d-flex align-items-start">
                            <i class="fas {{ $this->getNotificationIcon($notification['type']) }} fs-3 me-3 mt-1 opacity-75"></i>
                            <div class="flex-grow-1">
                                <strong class="mb-2 d-block fs-5">{{ $notification['title'] }}</strong>
                                <p class="mb-2 opacity-90">{{ $notification['message'] }}</p>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="m-auto btn-close btn-close-black me-4" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endforeach

        <!-- Login Alert as Integrated Toast -->
        @if($loginAlert)
            <div
                class="overflow-hidden shadow-lg toast rounded-4"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
                data-bs-autohide="true"
                data-bs-delay="8000"
                x-data
                x-init="new bootstrap.Toast($el).show()"
                x-on:hidden.bs.toast="$wire.hideLoginAlert()"
                style="min-width: 380px; animation: slideInRight 0.5s ease-out forwards;"
            >
                <div class="px-4 py-3 text-white toast-header bg-primary">
                    <i class="fas fa-user-lock fs-4 me-3"></i>
                    <strong class="me-auto fs-5">Login Required</strong>
                    <small class="opacity-75">Just now</small>
                    <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="px-4 py-4 toast-body">
                    <p class="mb-4">Please login or register to continue.</p>
                    <div class="gap-3 d-flex justify-content-end">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                        <button type="button" class="btn btn-link text-muted" wire:click="hideLoginAlert">Continue as guest</button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Smooth Slide-In Animation -->
    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Nice spacing between stacked toasts */
        .toast-container .toast + .toast {
            margin-top: 1rem;
        }
    </style>
</div>
