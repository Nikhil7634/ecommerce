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
                        <a href="">
                            <img src="{{asset('admin-assets/assets/images/logo-black.png')}}" alt="Logo">
                        </a>
                    </div>
                    
                    <div class="logo-small">
                        <a href=" ">
                            <img src="{{asset('admin-assets/assets/images/logo-small.png')}}" alt="Logo">
                        </a>
                    </div>
                </div>
                <div class="nav-close-btn">
                    <button id="navClose"><i class="fa-light fa-bars-sort"></i></button>
                </div>
                <a href="#" target="_blank" class="btn btn-sm btn-primary site-view-btn"><i class="fa-light fa-globe me-1"></i> <span>View Website</span></a>
            </div>
            <div class="col-4 d-lg-none">
                <div class="mobile-logo">
                    <a href="">
                        <img src="{{asset('admin-assets/assets/images/logo-black.png')}}" alt="Logo">
                    </a>
                </div>
            </div>
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-4">
                <div class="header-right-btns d-flex justify-content-end align-items-center">
                    <div class="header-collapse-group">
                        <div class="p-0 header-right-btns d-flex justify-content-end align-items-center">
                            <form class="header-form">
                                <input type="search" name="search" placeholder="Search..." required>
                                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </form>
                            <div class="p-0 header-right-btns d-flex justify-content-end align-items-center">
                                <div class="lang-select">
                                    <span>Language:</span>
                                    <select>
                                        <option value="">EN</option>
                                        <option value="">BN</option>
                                        <option value="">FR</option>
                                    </select>
                                </div>
                                <div class="header-btn-box">
                                    <button class="header-btn" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-light fa-comment-dots"></i>
                                        <span class="badge bg-danger">3</span>
                                    </button>
                                    <ul class="message-dropdown dropdown-menu" aria-labelledby="messageDropdown">
                                        <li>
                                            <a href="#" class="d-flex">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar.png')}}" alt="image">
                                                </div>
                                                <div class="msg-txt">
                                                    <span class="name">Archer Cowie</span>
                                                    <span class="msg-short">There are many variations of passages of Lorem Ipsum.</span>
                                                    <span class="time">2 Hours ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-2.png')}}" alt="image">
                                                </div>
                                                <div class="msg-txt">
                                                    <span class="name">Cody Rodway</span>
                                                    <span class="msg-short">There are many variations of passages of Lorem Ipsum.</span>
                                                    <span class="time">2 Hours ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-3.png')}}" alt="image">
                                                </div>
                                                <div class="msg-txt">
                                                    <span class="name">Zane Bain</span>
                                                    <span class="msg-short">There are many variations of passages of Lorem Ipsum.</span>
                                                    <span class="time">2 Hours ago</span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="show-all-btn">Show all message</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="header-btn-box">
                                    <button class="header-btn" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-light fa-bell"></i>
                                        <span class="badge bg-danger">9+</span>
                                    </button>
                                    <ul class="notification-dropdown dropdown-menu" aria-labelledby="notificationDropdown">
                                        <li>
                                            <a href="#" class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar.png')}}" alt="image">
                                                </div>
                                                <div class="notification-txt">
                                                    <span class="notification-icon text-primary"><i class="fa-solid fa-thumbs-up"></i></span> <span class="fw-bold">Archer</span> Likes your post
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-2.png')}}" alt="image">
                                                </div>
                                                <div class="notification-txt">
                                                    <span class="notification-icon text-success"><i class="fa-solid fa-comment-dots"></i></span> <span class="fw-bold">Cody</span> Commented on your post
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-3.png')}}" alt="image">
                                                </div>
                                                <div class="notification-txt">
                                                    <span class="notification-icon"><i class="fa-solid fa-share"></i></span> <span class="fw-bold">Zane</span> Shared your post
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-4.png')}}" alt="image">
                                                </div>
                                                <div class="notification-txt">
                                                    <span class="notification-icon text-primary"><i class="fa-solid fa-thumbs-up"></i></span> <span class="fw-bold">Christopher</span> Likes your post
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-5.png')}}" alt="image">
                                                </div>
                                                <div class="notification-txt">
                                                    <span class="notification-icon text-success"><i class="fa-solid fa-comment-dots"></i></span> <span class="fw-bold">Charlie</span> Commented on your post
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <img src="{{asset('admin-assets/assets/images/avatar-6.png')}}" alt="image">
                                                </div>
                                                <div class="notification-txt">
                                                    <span class="notification-icon"><i class="fa-solid fa-share"></i></span> <span class="fw-bold">Jayden</span> Shared your post
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#" class="show-all-btn">Show all message</a>
                                        </li>
                                    </ul>
                                </div>
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
                                                            <td class="bg-danger">C</td>
                                                            <td class="bg-secondary">CE</td>
                                                            <td class="dgb-calc-oprator bg-primary">/</td>
                                                            <td class="dgb-calc-oprator bg-primary">*</td>
                                                        </tr>
                                                        <tr>
                                                            <td>7</td>
                                                            <td>8</td>
                                                            <td>9</td>
                                                            <td class="dgb-calc-oprator bg-primary">-</td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>5</td>
                                                            <td>6</td>
                                                            <td class="dgb-calc-oprator bg-primary">+</td>
                                                        </tr>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>2</td>
                                                            <td>3</td>
                                                            <td rowspan="2" class="dgb-calc-sum bg-primary">=</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">0</td>
                                                            <td>.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                                <button class="header-btn fullscreen-btn" id="btnFullscreen"><i class="fa-light fa-expand"></i></button>
                            </div>
                        </div>
                    </div>
                    <button class="header-btn header-collapse-group-btn d-lg-none"><i class="fa-light fa-ellipsis-vertical"></i></button>
                    <button class="header-btn theme-settings-btn d-lg-none"><i class="fa-light fa-gear"></i></button>
                    <div class="header-btn-box profile-btn-box">
                        <button class="profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{asset('admin-assets/assets/images/admin.png')}}" alt="image">
                        </button>
                        <ul class="dropdown-menu profile-dropdown-menu">
                            <li>
                                <div class="text-center dropdown-txt">
                                    <p class="mb-0">Shaikh Abu Dardah</p>
                                    <span class="d-block">Web Developer</span>
                                    <div class="d-flex justify-content-center">
                                        <div class="pt-3 form-check">
                                            <input class="form-check-input" type="checkbox" id="seeProfileAsSidebar">
                                            <label class="form-check-label" for="seeProfileAsSidebar">See as sidebar</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="view-profile.html"><span class="dropdown-icon"><i class="fa-regular fa-circle-user"></i></span> Profile</a></li>
                            <li><a class="dropdown-item" href="chat.html"><span class="dropdown-icon"><i class="fa-regular fa-message-lines"></i></span> Message</a></li>
                            <li><a class="dropdown-item" href="task.html"><span class="dropdown-icon"><i class="fa-regular fa-calendar-check"></i></span> Taskboard</a></li>
                            <li><a class="dropdown-item" href="#"><span class="dropdown-icon"><i class="fa-regular fa-circle-question"></i></span> Help</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="edit-profile.html"><span class="dropdown-icon"><i class="fa-regular fa-gear"></i></span> Settings</a></li>
                            <li><a class="dropdown-item" href="login.html"><span class="dropdown-icon"><i class="fa-regular fa-arrow-right-from-bracket"></i></span> Logout</a></li>
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
                            <p class="mb-0">Shaikh Abu Dardah</p>
                            <span class="d-block">Web Developer</span>
                            <div class="d-flex justify-content-center">
                                <div class="pt-3 form-check">
                                    <input class="form-check-input" type="checkbox" id="seeProfileAsDropdown">
                                    <label class="form-check-label" for="seeProfileAsDropdown">See as dropdown</label>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="view-profile.html"><span class="dropdown-icon"><i class="fa-regular fa-circle-user"></i></span> Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="chat.html"><span class="dropdown-icon"><i class="fa-regular fa-message-lines"></i></span> Message</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="task.html"><span class="dropdown-icon"><i class="fa-regular fa-calendar-check"></i></span> Taskboard</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#"><span class="dropdown-icon"><i class="fa-regular fa-circle-question"></i></span> Help</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="bottom-panel">
            <div class="button-group">
                <a href="edit-profile.html"><i class="fa-light fa-gear"></i><span>Settings</span></a>
                <a href="login.html"><i class="fa-light fa-power-off"></i><span>Logout</span></a>
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