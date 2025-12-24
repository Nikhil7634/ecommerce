
    <script src="{{ asset('admin-assets/assets/vendor/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/apexcharts.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('admin-assets/assets/vendor/js/bootstrap.bundle.min.js') }}"></script>
    
    <script src="{{ asset('admin-assets/assets/js/main.js') }}"></script>
    <!-- for demo purpose -->
    <script>
        var rtlReady = $('html').attr('dir', 'ltr');
        if (rtlReady !== undefined) {
            localStorage.setItem('layoutDirection', 'ltr');
        }
    </script>
    <script>
        let blueMode = localStorage.getItem('blueMode');
        const enableBlueMode = () => {
            $('body').removeClass('light-theme dark-theme');
            $('.header .main-logo .logo-big img, .mobile-logo img, .logo img').attr('src', '{{ asset("admin-assets/assets/images/logo-black.png") }}');
            localStorage.setItem("blueMode", "enabled");
            localStorage.removeItem("darkMode");
        };
        if (blueMode === "enabled") {
            enableBlueMode();
            localStorage.removeItem("darkTheme");
            $('#blueTheme').addClass('active').parent().siblings().find('.dashboard-icon').removeClass('active');
        }
        $('#blueTheme').on('click', function() {
            enableBlueMode();
        });

        // ====== Enable Dark Theme From Settings ======
        let darkMode = localStorage.getItem('darkMode');
        const enableDarkMode = () => {
            $('body').removeClass('light-theme').addClass('dark-theme');
            $('.header .main-logo .logo-big img, .mobile-logo img, .logo img').attr('src', '{{ asset("admin-assets/assets/images/logo-black.png") }}');
            localStorage.setItem("darkMode", "enabled");
            localStorage.removeItem("blueMode");
        };
    </script>
    <!-- for demo purpose -->
</body>

 </html>