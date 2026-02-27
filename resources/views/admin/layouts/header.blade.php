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
</style>
</head>
<body class="body-padding body-p-top light-theme">