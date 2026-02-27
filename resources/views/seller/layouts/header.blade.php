<!doctype html>
<html lang="en" data-bs-theme="blue-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Dashboard</title>

    <!--favicon-->
    <link rel="icon" href="{{ asset('seller-assets/assets/images/favicon-32x32.png') }}" type="image/png">

    <!-- loader -->
    <link href="{{ asset('seller-assets/assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('seller-assets/assets/js/pace.min.js') }}"></script>

    <!-- plugins -->
    <link href="{{ asset('seller-assets/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('seller-assets/assets/plugins/metismenu/metisMenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('seller-assets/assets/plugins/fancy-file-uploader/fancy_fileuplod.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('seller-assets/assets/plugins/metismenu/mm-vertical.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('seller-assets/assets/plugins/simplebar/css/simplebar.css') }}">

    <!-- bootstrap css -->
    <link href="{{asset('seller-assets/assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- main css -->
    <link href="{{ asset('seller-assets/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('seller-assets/sass/main.css') }}" rel="stylesheet">
    <link href="{{ asset('seller-assets/sass/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('seller-assets/sass/blue-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('seller-assets/sass/semi-dark.css') }}" rel="stylesheet">
    <link href="{{ asset('seller-assets/sass/bordered-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('seller-assets/sass/responsive.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">


    <script>
    // Select all checkbox
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.order-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Bulk update function
    function bulkUpdate(status) {
        const selected = document.querySelectorAll('.order-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select at least one order.');
            return;
        }
        
        let message = `Are you sure you want to mark ${selected.length} order(s) as ${status}?`;
        
        // Add warning for delivered status
        if (status === 'delivered') {
            message = `⚠️ WARNING: Marking orders as delivered will add earnings to your account. Are you sure?`;
        }
        
        if (confirm(message)) {
            const form = document.getElementById('bulk-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = status;
            form.appendChild(input);
            form.submit();
        }
    }

    // Update status function
    function updateStatus(orderId, currentStatus) {
        document.getElementById('modal_order_id').value = orderId;
        const statusSelect = document.getElementById('modal_status');
        statusSelect.value = currentStatus;
        
        // Show/hide tracking section based on status
        const trackingSection = document.getElementById('tracking_section');
        const trackingUrlSection = document.getElementById('tracking_url_section');
        const earningsPreview = document.getElementById('earnings_preview');
        const earningsMessage = document.getElementById('earnings_message');
        
        function toggleSections() {
            const selectedStatus = statusSelect.value;
            
            // Toggle tracking sections
            if (selectedStatus === 'shipped') {
                trackingSection.style.display = 'block';
                trackingUrlSection.style.display = 'block';
            } else {
                trackingSection.style.display = 'none';
                trackingUrlSection.style.display = 'none';
            }
            
            // Show earnings preview for delivered status
            if (selectedStatus === 'delivered' && currentStatus !== 'delivered') {
                earningsPreview.style.display = 'block';
                earningsMessage.textContent = '⚠️ Setting status to "Delivered" will add earnings to your account. This action cannot be undone.';
            } else {
                earningsPreview.style.display = 'none';
            }
        }
        
        statusSelect.addEventListener('change', toggleSections);
        toggleSections();
        
        const form = document.getElementById('statusForm');
        form.action = `/seller/orders/${orderId}/status`;
        
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    // Auto-submit filters on change for status dropdown
    document.querySelector('select[name="status"]')?.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });

    // Date validation
    document.querySelector('input[name="date_from"]')?.addEventListener('change', function() {
        document.querySelector('input[name="date_to"]').min = this.value;
    });
    
    document.querySelector('input[name="date_to"]')?.addEventListener('change', function() {
        document.querySelector('input[name="date_from"]').max = this.value;
    });
    
    // Form submission confirmation for delivered status
    document.getElementById('statusForm')?.addEventListener('submit', function(e) {
        const status = document.getElementById('modal_status').value;
        const currentStatus = document.getElementById('modal_status').getAttribute('data-current');
        
        if (status === 'delivered') {
            if (!confirm('⚠️ WARNING: Marking this order as delivered will add earnings to your account. Continue?')) {
                e.preventDefault();
            }
        }
    });
</script>

<style>
    .widgets-icons {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
    }
    
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .bg-light-success {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
    
    .badge {
        padding: 5px 10px;
        font-weight: 500;
    }
    
    .earnings-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Badge styling for sidebar */
.metismenu .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    margin-left: auto;
}

.metismenu .bg-danger {
    background-color: #dc3545 !important;
    color: white;
}
</style>




 </head>
<body>
