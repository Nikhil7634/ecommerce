<!-- End::app-content -->
            <div
                class="modal fade"
                id="searchModal"
                tabindex="-1"
                aria-labelledby="searchModal"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="input-group">
                                <a
                                    href="javascript:void(0);"
                                    class="input-group-text"
                                    id="Search-Grid"
                                    ><i
                                        class="fe fe-search header-link-icon fs-18"
                                    ></i
                                ></a>
                                <input
                                    type="search"
                                    class="px-2 border-0 form-control"
                                    placeholder="Search"
                                    aria-label="Username"
                                />
                                <a
                                    href="javascript:void(0);"
                                    class="input-group-text"
                                    id="voice-search"
                                    ><i class="fe fe-mic header-link-icon"></i
                                ></a>
                                <a
                                    href="javascript:void(0);"
                                    class="btn btn-light btn-icon"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >
                                    <i class="fe fe-more-vertical"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            >Action</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            >Another action</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            >Something else here</a
                                        >
                                    </li>
                                    <li><hr class="m-0 dropdown-divider" /></li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            href="javascript:void(0);"
                                            >Separated link</a
                                        >
                                    </li>
                                </ul>
                            </div>
                            <div class="mt-4">
                                <p class="mb-2 font-weight-semibold text-muted">
                                    Are You Looking For...
                                </p>
                                <span class="search-tags"
                                    ><i class="fe fe-user me-2"></i>People<a
                                        href="javascript:void(0)"
                                        class="tag-addon"
                                        ><i class="fe fe-x"></i></a
                                ></span>
                                <span class="search-tags"
                                    ><i class="fe fe-file-text me-2"></i>Pages<a
                                        href="javascript:void(0)"
                                        class="tag-addon"
                                        ><i class="fe fe-x"></i></a
                                ></span>
                                <span class="search-tags"
                                    ><i class="fe fe-align-left me-2"></i
                                    >Articles<a
                                        href="javascript:void(0)"
                                        class="tag-addon"
                                        ><i class="fe fe-x"></i></a
                                ></span>
                                <span class="search-tags"
                                    ><i class="fe fe-server me-2"></i>Tags<a
                                        href="javascript:void(0)"
                                        class="tag-addon"
                                        ><i class="fe fe-x"></i></a
                                ></span>
                            </div>
                            <div class="my-4">
                                <p class="mb-2 font-weight-semibold text-muted">
                                    Recent Search :
                                </p>
                                <div
                                    class="p-2 mb-2 border br-5 d-flex align-items-center text-muted alert"
                                >
                                    <a href="notifications.html"
                                        ><span>Notifications</span></a
                                    >
                                    <a
                                        class="ms-auto lh-1"
                                        href="javascript:void(0);"
                                        data-bs-dismiss="alert"
                                        aria-label="Close"
                                        ><i class="fe fe-x text-muted"></i
                                    ></a>
                                </div>
                                <div
                                    class="p-2 mb-2 border br-5 d-flex align-items-center text-muted alert"
                                >
                                    <a href="alerts.html"
                                        ><span>Alerts</span></a
                                    >
                                    <a
                                        class="ms-auto lh-1"
                                        href="javascript:void(0);"
                                        data-bs-dismiss="alert"
                                        aria-label="Close"
                                        ><i class="fe fe-x text-muted"></i
                                    ></a>
                                </div>
                                <div
                                    class="p-2 mb-0 border br-5 d-flex align-items-center text-muted alert"
                                >
                                    <a href="mail.html"><span>Mail</span></a>
                                    <a
                                        class="ms-auto lh-1"
                                        href="javascript:void(0);"
                                        data-bs-dismiss="alert"
                                        aria-label="Close"
                                        ><i class="fe fe-x text-muted"></i
                                    ></a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group ms-auto">
                                <button class="btn btn-sm btn-primary-light">
                                    Search
                                </button>
                                <button class="btn btn-sm btn-primary">
                                    Clear Recents
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Start -->
            <footer class="py-3 mt-auto text-center bg-white footer">
                <div class="container">
                    <span class="text-default">
                        Copyright © <span id="year">2025</span>
                        <a
                            href="javascript:void(0);"
                            class="text-primary fw-semibold"
                            >Admitro</a
                        >. Designed with
                        <span class="bi bi-heart-fill text-danger"></span> by
                        <a
                            href="https://spruko.com/"
                            target="_blank"
                            class="text-primary"
                        >
                            <span class="">Spruko</span>
                        </a>
                        All rights reserved
                    </span>
                </div>
            </footer>
            <!-- Footer End -->
        </div>
        <!-- Scroll To Top -->
        <div class="scrollToTop" style="display: flex">
            <span class="arrow"><i class="fe fe-chevrons-up fs-16"></i></span>
        </div>
        <div id="responsive-overlay"></div>
        <!-- Scroll To Top -->


<script src="{{asset('admin-assets/assets/libs/%40popperjs/core/umd/popper.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/js/defaultmenu.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/libs/node-waves/waves.min.js')}}"></script>
 
 
<script src="{{asset('admin-assets/assets/js/sticky.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/libs/simplebar/simplebar.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/js/simplebar.js')}}"></script>
 
</script>
 
<script src="{{asset('admin-assets/assets/libs/@simonwep/pickr/pickr.es5.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/libs/apexcharts/apexcharts.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/libs/echarts/echarts.min.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/js/index.js')}}"></script>
 
<script src="{{asset('admin-assets/assets/js/custom-switcher.min.js')}}"></script>
 
 
<script src="{{asset('admin-assets/assets/js/custom.js')}}"></script>
</body>
</html>