<div class="col-lg-3 wow fadeInUp">
    <div style="max-height: max-content" class="dashboard_sidebar">
        <div class="dashboard_sidebar_area">
            @php
            use Illuminate\Support\Str;
            @endphp

            <div class="text-center dashboard_sidebar_user">
                <div class="relative inline-block img">
                    <img id="profileImagePreview"
                        src="{{ Auth::user()->avatar
                                ? (Str::startsWith(Auth::user()->avatar, 'http')
                                    ? Auth::user()->avatar
                                    : asset('storage/' . Auth::user()->avatar))
                                : 'https://cdn-icons-png.flaticon.com/512/8792/8792047.png' }}"
                        alt="dashboard"
                         >

                    <label for="profile_photo">
                        <i class="text-gray-600 far fa-camera"></i>
                    </label>

                    <input type="file" id="profile_photo" accept="image/*" hidden>
                </div>

                <h3 class="mt-3 font-semibold">{{ Auth::user()->name ?? 'Guest User' }}</h3>
                <p class="text-gray-500">{{ Auth::user()->email ?? 'guest@example.com' }}</p>
            </div>


            <div class="dashboard_sidebar_menu">
                <ul>
                    <li><p>dashboard</p></li>

                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.dashboard' --}}
                        <a href="{{ route('buyer.dashboard') }}" class="{{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                                </svg>
                            </span>
                            Overview
                        </a>
                    </li>

                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.orders' --}}
                        <a href="{{ route('buyer.orders') }}" class="{{ request()->routeIs('buyer.orders') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993
                                        1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125
                                        1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125
                                        0 0 1 5.513 7.5h12.974c.576 0 1.059.435
                                        1.119 1.007Z" />
                                </svg>
                            </span>
                            Orders
                        </a>
                    </li>

                     

                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.return.policy' --}}
                        <a href="{{ route('buyer.return.policy') }}" class="{{ request()->routeIs('buyer.return.policy') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 9.75h4.875a2.625 2.625 0 0 1
                                        0 5.25H12M8.25 9.75 10.5 7.5M8.25
                                        9.75 10.5 12m9-7.243V21.75l-3.75
                                        -1.5-3.75 1.5-3.75-1.5-3.75
                                        1.5V4.757c0-1.108.806-2.057
                                        1.907-2.185a48.507 48.507
                                        0 0 1 11.186 0c1.1.128
                                        1.907 1.077 1.907 2.185Z" />
                                </svg>
                            </span>
                            Return Policy
                        </a>
                    </li>

                    <li><p>Account settings</p></li>

                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.profile' --}}
                        <a href="{{ route('buyer.profile') }}" class="{{ request()->routeIs('buyer.profile') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5
                                        0 3.75 3.75 0 0 1 7.5 0ZM4.501
                                        20.118a7.5 7.5 0 0 1 14.998
                                        0A17.933 17.933 0 0 1 12
                                        21.75c-2.676 0-5.216-.584
                                        -7.499-1.632Z" />
                                </svg>
                            </span>
                            Personal Info
                        </a>
                    </li>

                     
                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.wishlist' --}}
                        <a href="{{ route('buyer.wishlist') }}" class="{{ request()->routeIs('buyer.wishlist') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5
                                        -4.688-4.5-1.935 0-3.597
                                        1.126-4.312 2.733-.715
                                        -1.607-2.377-2.733
                                        -4.313-2.733C5.1 3.75
                                        3 5.765 3 8.25c0 7.22
                                        9 12 9 12s9-4.78
                                        9-12Z" />
                                </svg>
                            </span>
                            Wishlist
                        </a>
                    </li>

                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.reviews' --}}
                        <a href="{{ route('buyer.reviews') }}" class="{{ request()->routeIs('buyer.reviews') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1
                                        1.04 0l2.125 5.111a.563.563
                                        0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204
                                        3.602a.563.563 0 0 0-.182.557l1.285
                                        5.385a.562.562 0 0 1-.84.61l-4.725
                                        -2.885a.562.562 0 0 0-.586 0L6.982
                                        20.54a.562.562 0 0 1-.84-.61l1.285
                                        -5.386a.562.562 0 0 0-.182-.557
                                        l-4.204-3.602a.562.562 0 0 1
                                        .321-.988l5.518-.442a.563.563
                                        0 0 0 .475-.345L11.48 3.5Z" />
                                </svg>
                            </span>
                            Reviews
                        </a>
                    </li>

                    <li>
                        {{-- Dynamic 'active' class: Check if current route is 'buyer.change-password' or 'buyer/change-password' (for absolute URLs) --}}
                        <a href="{{ url('buyer/change-password') }}" class="{{ request()->is('buyer/change-password') ? 'active' : '' }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5
                                        0 1 0-9 0v3.75m-.75 11.25h10.5
                                        a2.25 2.25 0 0 0 2.25-2.25
                                        v-6.75a2.25 2.25 0 0 0
                                        -2.25-2.25H6.75a2.25
                                        2.25 0 0 0-2.25
                                        2.25v6.75a2.25
                                        2.25 0 0 0
                                        2.25 2.25Z" />
                                </svg>
                            </span>
                            Change Password
                        </a>
                    </li>

                    <li>
                        <a style="cursor: pointer"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="flex items-center space-x-2 text-gray-700 transition hover:text-red-600">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                </svg>
                            </span>
                            <span>Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>


                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Profile Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-end modal-dialog-centered">
    <div class="border-0 shadow-lg modal-content rounded-3">
      <div class="py-4 text-center modal-body">
        <i id="statusIcon" class="mb-2 fa-solid fa-check-circle text-success fa-2x"></i>
        <h6 id="statusMessage" class="mb-0 fw-semibold"></h6>
      </div>
    </div>
  </div>
</div>


<script>
function showStatusModal(message, type = 'success') {
    const modalEl = document.getElementById('statusModal');
    const iconEl = document.getElementById('statusIcon');
    const msgEl = document.getElementById('statusMessage');

    // Set message
    msgEl.textContent = message;

    // Update icon and color
    if (type === 'success') {
        iconEl.className = 'fa-solid fa-check-circle text-success fa-2x mb-2';
    } else {
        iconEl.className = 'fa-solid fa-triangle-exclamation text-danger fa-2x mb-2';
    }

    // Show Bootstrap modal
    const modal = new bootstrap.Modal(modalEl);
    modal.show();

    // Auto-hide after 2.5 seconds
    setTimeout(() => {
        modal.hide();
    }, 2500);
}

document.getElementById('profile_photo').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    // Preview instantly
    const reader = new FileReader();
    reader.onload = function (event) {
        document.getElementById('profileImagePreview').src = event.target.result;
    };
    reader.readAsDataURL(file);

    // Upload to server
    const formData = new FormData();
    formData.append('avatar', file);
    formData.append('_token', '{{ csrf_token() }}');

    fetch("{{ route('buyer.profile.avatar.update') }}", {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showStatusModal('Profile picture updated successfully!', 'success');
        } else {
            showStatusModal('Something went wrong while updating.', 'error');
        }
    })
    .catch(() => showStatusModal('Network error. Please try again.', 'error'));
});
</script>
