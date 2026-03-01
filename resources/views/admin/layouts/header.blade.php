<!DOCTYPE html>
<html lang="en" data-menu="vertical" data-nav-size="nav-default">

 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eCommerce Dashboard | Digiboard</title>
    
    <link rel="shortcut icon" href="favicon.png">
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/vendor/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/assets/css/style.css') }}">
    <link rel="stylesheet" id="primaryColor" href="{{ asset('admin-assets/assets/css/blue-color.css') }}">
    <link rel="stylesheet" id="rtlStyle" href="#">
    

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Sales Analytics Chart
    var options = {
        series: [{
            name: 'Sales',
            data: {!! json_encode($monthlySales ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!}
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#0d6efd'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yaxis: {
            labels: {
                formatter: function(value) {
                    return '₹' + value.toFixed(2);
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(value) {
                    return '₹' + value.toFixed(2);
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#saleAnalytics"), options);
    chart.render();

    function changeChartPeriod(period) {
        // Update active button
        document.querySelectorAll('.btn-outline-primary').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // You can implement period change logic here
        // For now, just reload with period parameter
        window.location.href = '{{ route("admin.dashboard") }}?period=' + period;
    }

    // Date range filter
    if (typeof $('#dashboardFilter').daterangepicker === 'function') {
        $('#dashboardFilter').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#dashboardFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            // Reload with date range
            window.location.href = '{{ route("admin.dashboard") }}?start=' + picker.startDate.format('YYYY-MM-DD') + '&end=' + picker.endDate.format('YYYY-MM-DD');
        });

        $('#dashboardFilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            window.location.href = '{{ route("admin.dashboard") }}';
        });
    }
</script>
 
<style>
    .subscription-table td {
        padding: 0.75rem 0;
    }
    
    .new-customer .part-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 10px;
    }
    
    .new-customer .part-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .new-customer .part-txt .customer-name {
        font-weight: 600;
        margin-bottom: 2px;
    }
    
    .new-customer .part-txt span {
        font-size: 12px;
        color: #6c757d;
    }
    
    .btn-outline-primary.active {
        background-color: #0d6efd;
        color: white;
    }
    
    .badge {
        padding: 5px 10px;
        font-weight: 500;
    }
    
    .panel-body {
        padding: 1.5rem;
    }


    .main-menu {
  
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

/* Remove arrows (Chrome, Edge, Safari) */
.main-menu::-webkit-scrollbar-button {
    display: none;
    height: 0;
    width: 0;
}

/* Scrollbar width */
.main-menu::-webkit-scrollbar {
    width: 8px;
}

/* Transparent track */
.main-menu::-webkit-scrollbar-track {
    background: transparent;
}

/* Premium glass thumb */
.main-menu::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.7); /* Indigo glass */
    backdrop-filter: blur(6px);
    border-radius: 10px;
    transition: all 0.3s ease;
}

/* Hover effect */
.main-menu::-webkit-scrollbar-thumb:hover {
    background: rgba(79, 70, 229, 0.9);
}

/* Firefox support */
.main-menu {
    scrollbar-width: thin;
    scrollbar-color: rgba(99, 102, 241, 0.7) transparent;
}
</style>


 <script>
    document.addEventListener('DOMContentLoaded', function() {
        loadRecentMessages();
        
        // Refresh messages every 30 seconds
        setInterval(loadRecentMessages, 30000);
        
        // Load messages when dropdown is opened
        const messageDropdown = document.getElementById('messageDropdown');
        if (messageDropdown) {
            messageDropdown.addEventListener('show.bs.dropdown', function() {
                loadRecentMessages();
            });
        }
    });

    function loadRecentMessages() {
        fetch('{{ route("admin.messages.recent") }}')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateMessagesDropdown(data.messages);
                    updateMessageCount(data.unread_count);
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                const container = document.querySelector('#messageDropdownMenu .message-list-container');
                if (container) {
                    container.innerHTML = `
                        <div class="p-4 text-center">
                            <i class="mb-2 fa-light fa-exclamation-triangle text-danger fa-2x"></i>
                            <p class="mb-0 text-muted">Error loading messages</p>
                        </div>
                    `;
                }
            });
    }

    function updateMessagesDropdown(messages) {
        const container = document.querySelector('#messageDropdownMenu .message-list-container');
        if (!container) return;

        if (!messages || messages.length === 0) {
            container.innerHTML = `
                <div class="p-4 text-center">
                    <i class="mb-2 fa-light fa-inbox fa-2x text-muted"></i>
                    <p class="mb-0 text-muted">No messages</p>
                </div>
            `;
            return;
        }

        let html = '';
        messages.forEach(message => {
            const messagePreview = message.message ? message.message.substring(0, 50) : '';
            const displayPreview = messagePreview + (message.message && message.message.length > 50 ? '...' : '');
            
            html += `
                <a href="{{ url('admin/messages') }}/${message.id}" class="p-3 d-flex text-decoration-none border-bottom">
                    <div class="flex-shrink-0 avatar me-2">
                        <img src="${message.sender.avatar}" alt="${message.sender.name}" 
                             width="40" height="40" class="rounded-circle" style="object-fit: cover;">
                    </div>
                    <div class="min-w-0 flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small">${escapeHtml(message.sender.name)}</span>
                            <small class="text-muted ms-2">${escapeHtml(message.time)}</small>
                        </div>
                        <div class="small text-muted text-truncate">${escapeHtml(displayPreview)}</div>
                        ${!message.is_read && !message.is_outgoing ? '<span class="mt-1 badge bg-danger">New</span>' : ''}
                    </div>
                </a>
            `;
        });

        container.innerHTML = html;
    }

    function updateMessageCount(count) {
        const badge = document.querySelector('.message-count');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
        
        // Also update the notification badge if it exists
        const notificationBadge = document.querySelector('.notification-badge');
        if (notificationBadge) {
            // You can update notification count here if needed
        }
    }

    function markAllMessagesAsRead() {
        fetch('{{ route("admin.messages.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateMessageCount(0);
                loadRecentMessages();
                
                // Optional: Show a small notification
                showToast('All messages marked as read', 'success');
            }
        })
        .catch(error => {
            console.error('Error marking messages as read:', error);
            showToast('Error marking messages as read', 'error');
        });
    }

    function showToast(message, type = 'info') {
        // You can implement a toast notification here
        console.log(`${type}: ${message}`);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
 </head>
<body class="body-padding body-p-top light-theme">