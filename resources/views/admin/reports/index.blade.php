<!--start header-->
@include('admin.layouts.header')
<!--end top header-->

@include('admin.layouts.navbar')

<!--start sidebar-->
@include('admin.layouts.aside')

<!-- main content start -->
<div class="main-content">
    <div class="dashboard-breadcrumb mb-25">
        <h2>Reports</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item ">Reports</li>
            </ol>
        </nav>
    </div>

    <!-- Report Filters -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="panel">
                <div class="panel-header">
                    <h5>Report Filters</h5>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Report Type</label>
                            <select name="type" class="form-select">
                                <option value="sales" {{ $type == 'sales' ? 'selected' : '' }}>Sales Report</option>
                                <option value="products" {{ $type == 'products' ? 'selected' : '' }}>Products Report</option>
                                <option value="users" {{ $type == 'users' ? 'selected' : '' }}>Users Report</option>
                                <option value="earnings" {{ $type == 'earnings' ? 'selected' : '' }}>Earnings Report</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Period</label>
                            <select name="period" class="form-select">
                                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa-light fa-filter me-2"></i>Generate Report
                            </button>
                            <a href="{{ route('admin.reports.export', request()->all()) }}" class="btn btn-success">
                                <i class="fa-light fa-download me-2"></i>Export CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($type == 'sales')
        @include('admin.reports.partials.sales-report')
    @elseif($type == 'products')
        @include('admin.reports.partials.products-report')
    @elseif($type == 'users')
        @include('admin.reports.partials.users-report')
    @elseif($type == 'earnings')
        @include('admin.reports.partials.earnings-report')
    @endif
</div>
<!-- main content end -->

@include('admin.layouts.footer')