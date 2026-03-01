  <!-- preloader start -->
<div class="preloader d-none">
    <div class="loader">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<!-- preloader end -->

<!-- header start -->
<div class="header">
    <div class="row g-0 align-items-center">
        <div class="gap-20 col-xxl-6 col-xl-5 col-4 d-flex align-items-center">
            <div class="main-logo d-lg-block d-none">
                <div class="logo-big">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('admin-assets/assets/images/logo-black.png') }}" alt="Logo">
                    </a>
                </div>
                
                <div class="logo-small">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('admin-assets/assets/images/logo-small.png') }}" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="nav-close-btn">
                <button id="navClose"><i class="fa-light fa-bars-sort"></i></button>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-primary site-view-btn">
                <i class="fa-light fa-globe me-1"></i> <span>View Website</span>
            </a>
        </div>
        <div class="col-4 d-lg-none">
            <div class="mobile-logo">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('admin-assets/assets/images/logo-black.png') }}" alt="Logo">
                </a>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-7 col-lg-8 col-4">
            <div class="header-right-btns d-flex justify-content-end align-items-center">
                <div class="header-collapse-group">
                    <div class="p-0 header-right-btns d-flex justify-content-end align-items-center">
                        
                        <!-- Search Form -->
                        <form class="header-form" action="{{ route('admin.search') }}" method="GET">
                            <input type="search" name="query" placeholder="Search orders, products, users..." value="{{ request('query') }}" required>
                            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                        
                        <div class="p-0 header-right-btns d-flex justify-content-end align-items-center">
                            
                            
                            
                            <!-- Messages Dropdown -->
                            <div class="header-btn-box">
                                <button class="header-btn" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-light fa-comment-dots"></i>
                                    <span class="badge bg-danger message-count">{{ $unreadMessagesCount ?? 0 }}</span>
                                </button>
                                <ul class="message-dropdown dropdown-menu" aria-labelledby="messageDropdown" id="messageDropdownMenu">
                                    <div class="p-3 dropdown-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Recent Messages</h6>
                                        <button class="p-0 btn btn-sm btn-link" onclick="markAllMessagesAsRead()">Mark all read</button>
                                    </div>
                                    <div class="message-list-container" style="max-height: 300px; overflow-y: auto;">
                                        <!-- Messages will be loaded here via AJAX -->
                                        <div class="p-4 text-center">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <p class="mt-2 mb-0 ">Loading messages...</p>
                                        </div>
                                    </div>
                                    <li>
                                        <a href="{{ route('admin.messages.index') }}" class="p-2 text-center show-all-btn d-block">
                                            View all messages
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
                             
                            
                            <!-- Calculator (Optional - keep as is) -->
                            <div class="header-btn-box">
                                <div class="dropdown">
                                    <button class="header-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="fa-light fa-calculator"></i>
                                    </button>
                                    <ul class="dropdown-menu calculator-dropdown">
                                        <div class="dgb-calc-box">
                                            <div>
                                                <input type="text" id="dgbCalcResult" placeholder="0" autocomplete="off" readonly>
                                            </div>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td class="bg-danger" onclick="clearCalculator()">C</td>
                                                        <td class="bg-secondary" onclick="clearEntry()">CE</td>
                                                        <td class="dgb-calc-oprator bg-primary" onclick="appendOperator('/')">/</td>
                                                        <td class="dgb-calc-oprator bg-primary" onclick="appendOperator('*')">*</td>
                                                    </tr>
                                                    <tr>
                                                        <td onclick="appendNumber(7)">7</td>
                                                        <td onclick="appendNumber(8)">8</td>
                                                        <td onclick="appendNumber(9)">9</td>
                                                        <td class="dgb-calc-oprator bg-primary" onclick="appendOperator('-')">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td onclick="appendNumber(4)">4</td>
                                                        <td onclick="appendNumber(5)">5</td>
                                                        <td onclick="appendNumber(6)">6</td>
                                                        <td class="dgb-calc-oprator bg-primary" onclick="appendOperator('+')">+</td>
                                                    </tr>
                                                    <tr>
                                                        <td onclick="appendNumber(1)">1</td>
                                                        <td onclick="appendNumber(2)">2</td>
                                                        <td onclick="appendNumber(3)">3</td>
                                                        <td rowspan="2" class="dgb-calc-sum bg-primary" onclick="calculateResult()">=</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" onclick="appendNumber(0)">0</td>
                                                        <td onclick="appendDecimal()">.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Fullscreen Button -->
                            <button class="header-btn fullscreen-btn" id="btnFullscreen" onclick="toggleFullscreen()">
                                <i class="fa-light fa-expand"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button class="header-btn header-collapse-group-btn d-lg-none">
                    <i class="fa-light fa-ellipsis-vertical"></i>
                </button>
                <button class="header-btn theme-settings-btn d-lg-none">
                    <i class="fa-light fa-gear"></i>
                </button>
                
                <!-- User Profile Dropdown -->
                <div class="header-btn-box profile-btn-box">
                    <button class="profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar_url ?? asset('admin-assets/assets/images/admin.png') }}" alt="{{ Auth::user()->name }}">
                    </button>
                    <ul class="dropdown-menu profile-dropdown-menu">
                        <li>
                            <div class="text-center dropdown-txt">
                                <p class="mb-0">{{ Auth::user()->name }}</p>
                                <span class="d-block">{{ Auth::user()->role ?? 'Administrator' }}</span>
                                <div class="d-flex justify-content-center">
                                    <div class="pt-3 form-check">
                                        <input class="form-check-input" type="checkbox" id="seeProfileAsSidebar">
                                        <label class="form-check-label" for="seeProfileAsSidebar">See as sidebar</label>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                                <span class="dropdown-icon"><i class="fa-regular fa-circle-user"></i></span> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.messages.index') }}">
                                <span class="dropdown-icon"><i class="fa-regular fa-message-lines"></i></span> Messages
                            </a>
                        </li>
                         
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                                <span class="dropdown-icon"><i class="fa-regular fa-gear"></i></span> Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <span class="dropdown-icon"><i class="fa-regular fa-arrow-right-from-bracket"></i></span> Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- header end -->

<!-- profile right sidebar start -->
<div class="profile-right-sidebar">
    <button class="right-bar-close"><i class="fa-light fa-angle-right"></i></button>
    <div class="top-panel">
        <div class="profile-content scrollable">
            <ul>
                <li>
                    <div class="text-center dropdown-txt">
                        <p class="mb-0">{{ Auth::user()->name }}</p>
                        <span class="d-block">{{ Auth::user()->role ?? 'Administrator' }}</span>
                        <div class="d-flex justify-content-center">
                            <div class="pt-3 form-check">
                                <input class="form-check-input" type="checkbox" id="seeProfileAsDropdown">
                                <label class="form-check-label" for="seeProfileAsDropdown">See as dropdown</label>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                        <span class="dropdown-icon"><i class="fa-regular fa-circle-user"></i></span> Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('admin.messages.index') }}">
                        <span class="dropdown-icon"><i class="fa-regular fa-message-lines"></i></span> Messages
                    </a>
                </li>
                 
                <li>
                    <a class="dropdown-item" href="{{ route('admin.settings.index') }}">
                        <span class="dropdown-icon"><i class="fa-regular fa-gear"></i></span> Settings
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bottom-panel">
        <div class="button-group">
            <a href="{{ route('admin.settings.index') }}"><i class="fa-light fa-gear"></i><span>Settings</span></a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-side').submit();">
                <i class="fa-light fa-power-off"></i><span>Logout</span>
            </a>
            <form id="logout-form-side" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
<!-- profile right sidebar end -->


 
    <div class="right-sidebar-btn d-lg-block d-none">
        <button class="header-btn theme-settings-btn"><i class="fa-light fa-gear"></i></button>
    </div>

    <!-- right sidebar start -->
    <div class="right-sidebar">
        <button class="right-bar-close"><i class="fa-light fa-angle-right"></i></button>
        <div class="sidebar-title">
            <h3>Layout Settings</h3>
        </div>
        <div class="sidebar-body scrollable">
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Nav Position <span><i class="fa-light fa-angle-up"></i></span></span>
                <div class="settings-row">
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex active" id="verticalMenu">
                            <div class="px-1 pt-1 pb-2 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="mb-1 border border-primary">
                                    <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                    <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                </div>
                                <div class="border border-primary">
                                    <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                    <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                </div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Vertical</span>
                        </div>
                    </div>
                    <div class="settings-col d-lg-block d-none">
                        <div class="gap-1 border rounded dashboard-icon d-flex h-100" id="horizontalMenu">
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="p-1 bg-menu border-bottom">
                                        <div class="p-1 rounded-circle bg-nav w-max-content"></div>
                                    </div>
                                    <div class="gap-1 p-1 mb-1 bg-menu d-flex">
                                        <div class="px-2 pt-1 rounded w-max-content bg-nav"></div>
                                        <div class="px-2 pt-1 rounded w-max-content bg-nav"></div>
                                        <div class="px-2 pt-1 rounded w-max-content bg-nav"></div>
                                        <div class="px-2 pt-1 rounded w-max-content bg-nav"></div>
                                    </div>
                                </div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Horizontal</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex" id="twoColumnMenu">
                            <div class="p-1 bg-menu"></div>
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Two column</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex" id="flushMenu">
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Flush</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Theme Direction <span><i class="fa-light fa-angle-up"></i></span></span>
                <div>
                    <div class="btn-group d-flex">
                        <button class="btn btn-primary active w-50" id="dirLtr">LTR</button>
                        <button class="btn btn-primary w-50" id="dirRtl">RTL</button>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Primary Color <span><i class="fa-light fa-angle-up"></i></span></span>
                <div class="settings-row-2">
                    <button class="color-palette color-palette-1 active" data-color="blue-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-2" data-color="orange-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-3" data-color="pink-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-4" data-color="eagle_green-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-5" data-color="purple-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-6" data-color="gold-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-7" data-color="green-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-8" data-color="deep_pink-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-9" data-color="tea_green-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button class="color-palette color-palette-10" data-color="yellow_green-color">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Theme Color <span><i class="fa-light fa-angle-up"></i></span></span>
                <div class="settings-row">
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex bg-blue-theme" id="blueTheme">
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Blue Theme</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex bg-body-secondary light-theme-btn active" id="lightTheme">
                            <div class="px-1 pt-1 pb-4 bg-dark-subtle">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-primary"></div>
                                <div class="px-2 pt-1 mb-1 bg-primary"></div>
                                <div class="px-2 pt-1 mb-1 bg-primary"></div>
                                <div class="px-2 pt-1 mb-1 bg-primary"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-dark-subtle"></div>
                                <div class="px-2 py-1 bg-dark-subtle"></div>
                            </div>
                            <span class="part-txt">Light Theme</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex bg-dark" id="darkTheme">
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Dark Theme</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-group" id="navBarSizeGroup">
                <span class="sidebar-subtitle">Navbar Size <span><i class="fa-light fa-angle-up"></i></span></span>
                <div class="settings-row">
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex active" id="sidebarDefault">
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Default</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex" id="sidebarSmall">
                            <div class="pt-1 pb-4 bg-menu">
                                <div class="p-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="pt-1 mb-1 ps-1 bg-nav"></div>
                                <div class="pt-1 mb-1 ps-1 bg-nav"></div>
                                <div class="pt-1 mb-1 ps-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Small icon</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex" id="sidebarHover">
                            <div class="pt-1 pb-4 bg-menu">
                                <div class="p-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="pt-1 mb-1 ps-1 bg-nav"></div>
                                <div class="pt-1 mb-1 ps-1 bg-nav"></div>
                                <div class="pt-1 mb-1 ps-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Expand on hover</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Sidebar Background <span><i class="fa-light fa-angle-up"></i></span></span>
                <div>
                    <div class="sidebar-bg-btn-box">
                        <button id="noBackground">
                            <span><i class="fa-light fa-xmark"></i></span>
                        </button>
                        <button class="sidebar-bg-btn" data-img="{{asset('admin-assets/assets/images/nav-bg-1.jpg')}}"></button>
                        <button class="sidebar-bg-btn" data-img="{{asset('admin-assets/assets/images/nav-bg-2.jpg')}}"></button>
                        <button class="sidebar-bg-btn" data-img="{{asset('admin-assets/assets/images/nav-bg-3.jpg')}}"></button>
                        <button class="sidebar-bg-btn" data-img="{{asset('admin-assets/assets/images/nav-bg-4.jpg')}}"></button>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Main Background <span><i class="fa-light fa-angle-up"></i></span></span>
                <div>
                    <div class="main-content-bg-btn-box">
                        <button id="noBackground2">
                            <span><i class="fa-light fa-xmark"></i></span>
                        </button>
                        <button class="main-content-bg-btn" data-img="{{asset('admin-assets/assets/images/main-bg-1.jpg')}}"></button>
                        <button class="main-content-bg-btn" data-img="{{asset('admin-assets/assets/images/main-bg-2.jpg')}}"></button>
                        <button class="main-content-bg-btn" data-img="{{asset('admin-assets/assets/images/main-bg-3.jpg')}}"></button>
                        <button class="main-content-bg-btn" data-img="{{asset('admin-assets/assets/images/main-bg-4.jpg')}}"></button>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-group">
                <span class="sidebar-subtitle">Main preloader <span><i class="fa-light fa-angle-up"></i></span></span>
                <div class="settings-row">
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex" id="enableLoader">
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <div class="preloader-small">
                                <div class="loader">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                            <span class="part-txt">Enable</span>
                        </div>
                    </div>
                    <div class="settings-col">
                        <div class="gap-1 border rounded dashboard-icon d-flex active" id="disableLoader">
                            <div class="px-1 pt-1 pb-4 bg-menu">
                                <div class="px-2 py-1 mb-2 rounded-pill bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                                <div class="px-2 pt-1 mb-1 bg-nav"></div>
                            </div>
                            <div class="w-100 d-flex flex-column justify-content-between">
                                <div class="px-2 py-1 bg-menu"></div>
                                <div class="px-2 py-1 bg-menu"></div>
                            </div>
                            <span class="part-txt">Disable</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- right sidebar end -->