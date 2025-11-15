@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>📊 Bảng điều khiển</h3>
    </div>
    <div class="container p-0">
        <!-- Thống kê tổng quan -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-semibold mb-0">Thống kê tổng quan</h4>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2">
                        <label class="text-muted small mb-0">Chọn tháng:</label>
                        <select name="month" class="form-select form-select-sm" style="width: 200px;" onchange="this.form.submit()">
                            <option value="" @if(empty($selectedMonth)) selected @endif>Tất cả - ({{ $totalOrders ?? 0 }} đơn hàng)</option>
                            @for ($i = 1; $i <= 12; $i++)
                                @php $cnt = $months[$i] ?? 0; @endphp
                                <option value="{{ $i }}" @if(isset($selectedMonth) && $selectedMonth == $i) selected @endif>Tháng {{ $i }} - ({{ $cnt }} đơn hàng)</option>
                            @endfor
                        </select>
                    </form>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="stats-card bg-primary bg-opacity-10 border-0 rounded-3 p-4 h-100">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="text-primary text-uppercase small fw-semibold mb-2 opacity-75">Số lượng đã bán</p>
                                    <h2 class="fw-bold text-primary mb-0">
                                        @if(isset($selectedMonth) && $selectedMonth >= 1)
                                            {{ number_format($monthlySoldQuantity ?? 0, 0, ',', '.') }}
                                        @else
                                            {{ number_format($totalSoldQuantity ?? 0, 0, ',', '.') }}
                                        @endif
                                    </h2>
                                </div>
                                <div class="bg-primary bg-opacity-25 rounded-circle p-3">
                                    <svg width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stats-card bg-success bg-opacity-10 border-0 rounded-3 p-4 h-100">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="text-success text-uppercase small fw-semibold mb-2 opacity-75">Đơn hàng</p>
                                    <h2 class="fw-bold text-success mb-0">
                                        @if(isset($selectedMonth) && $selectedMonth >= 1)
                                            {{ number_format($monthlyOrders ?? 0, 0, ',', '.') }}
                                        @else
                                            {{ number_format($totalOrders ?? 0, 0, ',', '.') }}
                                        @endif
                                    </h2>
                                </div>
                                <div class="bg-success bg-opacity-25 rounded-circle p-3">
                                    <svg width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stats-card bg-danger bg-opacity-10 border-0 rounded-3 p-4 h-100">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <p class="text-danger text-uppercase small fw-semibold mb-2 opacity-75">Doanh thu</p>
                                    <h2 class="fw-bold text-danger mb-0">
                                        @php
                                            if(isset($selectedMonth) && $selectedMonth >= 1) {
                                                $rev = $monthlyRevenue ?? 0;
                                            } else {
                                                $rev = $totalRevenue ?? 0;
                                            }
                                        @endphp
                                        {{ number_format($rev ?? 0, 0, ',', '.') }} ₫
                                    </h2>
                                </div>
                                <div class="bg-danger bg-opacity-25 rounded-circle p-3">
                                    <svg width="24" height="24" fill="currentColor" class="text-danger" viewBox="0 0 16 16">
                                        <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo ngày -->
        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-semibold mb-0">Thống kê theo ngày</h4>
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2">
                        @if(isset($selectedMonth) && $selectedMonth)
                            <input type="hidden" name="month" value="{{ $selectedMonth }}" />
                        @endif
                        <input type="hidden" name="preserve_top" value="1" />
                        <label class="text-muted small mb-0">Chọn ngày:</label>
                        <input type="date" name="date" class="form-control form-control-sm" style="width: 200px;" value="{{ $selectedDate ?? request('date') }}" max="{{ date('Y-m-d') }}" onchange="this.form.submit()" />
                    </form>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="stats-card border rounded-3 p-4 h-100 bg-light bg-opacity-50">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary rounded-3 p-3">
                                    <svg width="20" height="20" fill="white" viewBox="0 0 16 16">
                                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Số lượng đã bán</p>
                                    <h3 class="fw-bold mb-0 text-primary">
                                        @if(!empty($selectedDate))
                                            {{ number_format($dailySoldQuantity ?? 0, 0, ',', '.') }}
                                        @elseif(isset($selectedMonth) && $selectedMonth >= 1)
                                            {{ number_format($monthlySoldQuantity ?? 0, 0, ',', '.') }}
                                        @else
                                            {{ number_format($totalSoldQuantity ?? 0, 0, ',', '.') }}
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stats-card border rounded-3 p-4 h-100 bg-light bg-opacity-50">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-success rounded-3 p-3">
                                    <svg width="20" height="20" fill="white" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Đơn hàng</p>
                                    <h3 class="fw-bold mb-0 text-success">
                                        @if(!empty($selectedDate))
                                            {{ number_format($dailyOrders ?? 0, 0, ',', '.') }}
                                        @elseif(isset($selectedMonth) && $selectedMonth >= 1)
                                            {{ number_format($monthlyOrders ?? 0, 0, ',', '.') }}
                                        @else
                                            {{ number_format($totalOrders ?? 0, 0, ',', '.') }}
                                        @endif
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stats-card border rounded-3 p-4 h-100 bg-light bg-opacity-50">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-danger rounded-3 p-3">
                                    <svg width="20" height="20" fill="white" viewBox="0 0 16 16">
                                        <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-muted small mb-1">Doanh thu</p>
                                    <h3 class="fw-bold mb-0 text-danger">
                                        @php
                                            if(!empty($selectedDate)) {
                                                $rev2 = $dailyRevenue ?? 0;
                                            } elseif(isset($selectedMonth) && $selectedMonth >= 1) {
                                                $rev2 = $monthlyRevenue ?? 0;
                                            } else {
                                                $rev2 = $totalRevenue ?? 0;
                                            }
                                        @endphp
                                        {{ number_format($rev2 ?? 0, 0, ',', '.') }} ₫
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-semibold mb-0">Biểu đồ doanh thu theo ngày trong tháng</h4>
                </div>
                <canvas id="revenueDayChart" height="100"></canvas>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-semibold mb-0">Biểu đồ doanh thu theo tháng trong năm</h4>
                </div>
                <canvas id="revenueMonthChart" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js CDN và script render --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            // Data từ server
            const dayLabels = {!! json_encode($revenueByDayLabels ?? []) !!};
            const dayData = {!! json_encode($revenueByDayData ?? []) !!};

            const monthLabels = {!! json_encode($revenueByMonthLabels ?? []) !!};
            const monthData = {!! json_encode($revenueByMonthData ?? []) !!};

            // Biểu đồ doanh thu theo ngày trong tháng
            const ctxDay = document.getElementById('revenueDayChart').getContext('2d');
            new Chart(ctxDay, {
                type: 'line',
                data: {
                    labels: dayLabels,
                    datasets: [{
                        label: 'Doanh thu (₫)',
                        data: dayData,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,0.1)',
                        tension: 0.2,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Ngày' } },
                        y: { title: { display: true, text: 'Doanh thu (₫)' }, beginAtZero: true }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Biểu đồ doanh thu theo tháng trong năm
            const ctxMonth = document.getElementById('revenueMonthChart').getContext('2d');
            new Chart(ctxMonth, {
                type: 'bar',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Doanh thu (₫)',
                        data: monthData,
                        backgroundColor: 'rgba(220,53,69,0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Tháng' } },
                        y: { title: { display: true, text: 'Doanh thu (₫)' }, beginAtZero: true }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        })();
    </script>

    <style>
        .stats-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
@endsection
