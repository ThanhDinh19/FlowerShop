<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Bảng quản trị - Flower Shop')</title>

    <!-- Font Awesome + Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom style (reuse màu từ home_style.css nếu muốn) -->
    <style>
        body {
            background-color: #fff6f9; /* tone hồng pastel nhẹ */
            font-family: 'Segoe UI', sans-serif;
        }

        .admin-header {
            background-color: #d63384;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .admin-sidebar {
            background-color: #f8e8ef;
            min-height: 100vh;
            width: 240px;
            padding-top: 1.5rem;
            border-right: 2px solid #f2c8dc;
        }

        .admin-sidebar a {
            display: block;
            padding: 12px 20px;
            color: #444;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px 10px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .admin-sidebar a:hover,
        .admin-sidebar a.active {
            background-color: #d63384;
            color: #fff;
        }

        .admin-content {
            flex-grow: 1;
            padding: 2rem;
        }

        .logout-btn {
            background: none;
            border: none;
            color: white;
            font-weight: 500;
            cursor: pointer;
        }

        .logout-btn:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px;
            color: #777;
            border-top: 1px solid #e0e0e0;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- HEADER -->
    <header class="admin-header">
        <h1>🌸 Flower Shop Admin</h1>

        <div>
            <span class="me-3">Xin chào, <strong>{{ Auth::user()->LastName ?? 'Admin' }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn">Đăng xuất</button>
            </form>
        </div>
    </header>

    <!-- MAIN WRAPPER -->
    <div class="d-flex">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line me-2"></i> Bảng điều khiển
            </a>

            <a href="{{ route('admin.products.index') ?? '#' }}" class="{{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                <i class="fa-solid fa-seedling me-2"></i> Quản lý sản phẩm
            </a>
            <a href="{{ route('admin.categories.index') ?? '#' }}" class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                <i class="fa-solid fa-list me-2"></i> Loại sản phẩm
            </a>

            <a href="{{ route('admin.orders.index') ?? '#' }}" class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt me-2"></i> Quản lý đơn hàng
            </a>

            <a href="{{ route('admin.reviews.index') ?? '#' }}" class="{{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">
                <i class="fas fa-star"></i> Quản lý Reviews
            </a>
            
            <a href="{{ route('admin.contacts.index') ?? '#' }}" class="{{ request()->routeIs('admin.contacts.index') ? 'active' : '' }}">
                <i class="fas fa-id-card-alt"></i> Quản lý Liên hệ
            </a>

            {{-- <a href="{{ route('admin.users') ?? '#' }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fa-solid fa-users me-2"></i> Người dùng
            </a> --}}

            <a href="{{ route('home') }}">
                <i class="fa-solid fa-store me-2"></i> Về trang mua hàng
            </a>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-content">
            @include('layouts.alert')
            @yield('content')
        </main>
    </div>

    <!-- FOOTER -->
    <footer>
        © 2025 Flower Shop Admin 💐
    </footer>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
