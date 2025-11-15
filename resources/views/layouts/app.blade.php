<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Trang chủ - Shop Bán Hoa')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('home_style.css') }}">
    @stack('styles')
</head>

<body>

    <header>
        <h1>🌸 Flower Shop</h1>

        <nav>
            {{-- Tìm kiếm sản phẩm --}}
            <form action="{{ route('product.search') }}" method="GET"
                class="position-relative d-flex align-items-center " autocomplete="off" style=" width:100%; margin-left:0px">

                <div class="input-group">
                    <input type="text" id="searchInput" name="query" class="form-control rounded-start-pill"
                        placeholder="🔍 Tìm sản phẩm..." value="{{ request('query') }}"
                        style="border-right: none;">
                    <button type="submit" class="btn btn-pink rounded-end-pill text-white"
                        style="background-color:#d63384;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>

                <!-- Dropdown kết quả -->
                <div id="searchResults"
                    class="position-absolute bg-white shadow rounded-3 border mt-1 w-100"
                    style="top: 100%; left: 0; display: none; max-height: 300px; overflow-y: auto; z-index: 2000;">
                </div>
            </form>

            <ul class="mt-3">
                <li><a href="/">Trang chủ</a></li>

                <li class="dropdown-category">
                    <a href="#">Danh mục</a>
                    @php
                    $categories = \App\Models\Category::all();
                    @endphp
                    <ul class="dropdown-menu category-grid">
                        @foreach ($categories as $cat)
                        <li>
                            <a href="{{ route('category.showByCategory', $cat->CategoryID) }}">{{ $cat->CategoryName }}</a>
                        </li>
                        @endforeach
                    </ul>
                </li>

                <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                @auth
                    <li><a href="{{ route('orders.myOrders') }}" style="text-align:center"><i class="fa-solid fa-receipt"></i> Đơn hàng</a></li>
                @endauth
                </li>
                <li>
                    @php
                    $cartCount = \App\Models\CartItem::where('UserID', Auth::id())->count();
                    @endphp

                    <a href="{{ route('cart.index') }}">🛒 Giỏ hàng {{ $cartCount }}</a>
                </li>
            </ul>

        </nav>

        <!-- Avatar -->
        <div class="user-menu">
            @auth
            <span class="username">Xin chào, <strong>{{ Auth::user()->LastName }}</strong> 🌸</span>

            @endauth
            <img src="{{ asset('assets/images/default_avatar.png') }}" alt="User" class="avatar" id="avatarBtn">
            @auth

            <div class="dropdown" id="dropdownMenu">
                <a href="{{ route('profile.show') }}" style="text-align:center">Thông tin cá nhân</a>
                <div class="divider m-0"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Đăng xuất</button>
                </form>

                @else
            </div>
            <div class="dropdown" id="dropdownMenu">
                <a href="{{ route('login.form') }}">Đăng nhập</a>
                <a href="{{ route('signup.form') }}">Đăng ký</a>
                <div class="divider"></div>
                <button class="google-login">
                    <img src="{{ asset('assets/images/icon_gg.png') }}" alt="Google" width="18">
                    Đăng nhập với Google
                </button>
                @endauth
            </div>
        </div>
    </header>


    <main>


        @include('layouts.alert')
        @yield('content')
    </main>

    <!-- Footer -->
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>🌸 Flower Shop</h4>
                <p>Flower Shop mang đến những bó hoa tươi thắm, tinh tế và tràn đầy cảm xúc.
                    Hãy để chúng tôi giúp bạn gửi gắm lời yêu thương đến người đặc biệt.</p>
            </div>

            <div class="footer-column">
                <h4>Liên kết nhanh</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li><a href="#">Giới thiệu</a></li>
                    <li><a href="#">Sản phẩm</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Liên hệ</h4>
                <p>📍 140 Lê Trọng Tấn, Tân Phú, TP.HCM</p>
                <p>📞 0123 456 789</p>
                <p>✉️ contact@flowershop.vn</p>
            </div>

            <div class="footer-column">
                <h4>Kết nối với chúng tôi</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2025 Flower Shop 💐</p>
        </div>
    </footer>

    <!-- FontAwesome CDN -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>



<script>
    const avatarBtn = document.getElementById('avatarBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');

    avatarBtn.addEventListener('click', () => {
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Ẩn menu khi click ra ngoài
    window.addEventListener('click', function(e) {
        if (!avatarBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    // tự động ẩn sau 3 giây
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.display = 'none';
        });
    }, 3000);


    // Tìm kiếm sản phẩm với AJAX
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length < 1) {
            searchResults.style.display = 'none';
            return;
        }

        fetch(`/ajax-search?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    searchResults.innerHTML = data.map(product => `
                        <a href="/products/${product.ProductID}" class="search-item">
                            <img src="/assets/product_images/${product.Image}" alt="${product.ProductName}">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">${product.ProductName}</div>
                                <div class="search-price">${Number(product.Price).toLocaleString()} đ</div>
                            </div>
                        </a>
                    `).join('');
                    searchResults.style.display = 'block';
                } else {
                    searchResults.innerHTML = `
                        <div class="text-center text-muted p-2">Không tìm thấy sản phẩm nào 😢</div>
                    `;
                    searchResults.style.display = 'block';
                }
            })
            .catch(err => console.error(err));
    });

    // Ẩn dropdown khi click ra ngoài
    document.addEventListener('click', e => {
        if (!searchResults.contains(e.target) && !searchInput.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</html>