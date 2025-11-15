@extends('layouts.app')
@section('title','Trang chủ')
@section('content')
<!-- Hero section -->
<section class="hero">
    <div class="hero-content">
        <span class="hero-badge">🌸 Chào mừng đến với shop hoa</span>
        <h2>Hoa Tươi Mỗi Ngày</h2>
        <p>Làm đẹp không gian sống cùng những bó hoa rực rỡ</p>
        <div class="hero-stats">
            <div class="stat-item">
                <span class="stat-number">500+</span>
                <span class="stat-label">Sản phẩm</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">1000+</span>
                <span class="stat-label">Khách hàng</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Hoa tươi</span>
            </div>
        </div>
    </div>
</section>

<!-- Products section -->
<section class="products">
    <div class="section-header">
        <h2>Sản phẩm nổi bật</h2>
        <p class="section-subtitle">Khám phá bộ sưu tập hoa tươi đẹp nhất</p>
    </div>
    
    <div class="product-grid">
        @foreach($products as $product)
        <div class="product-card {{ $product->StockQuantity == 0 ? 'out-of-stock' : '' }}">
            <div class="product-image-wrapper">
                <a href="{{ route('products.show', $product->ProductID) }}">
                    <img src="{{ asset('assets/product_images/'.$product->Image) }}" alt="{{ $product->ProductName }}">
                </a>
                
                {{-- Badge tồn kho --}}
                @if ($product->StockQuantity == 0)
                    <span class="stock-badge badge-out">Hết hàng</span>
                @elseif ($product->StockQuantity <= 5)
                    <span class="stock-badge badge-low">Còn {{ $product->StockQuantity }}</span>
                @endif
            </div>
            
            <div class="product-info">
                <h3>
                    <a href="{{ route('products.show', $product->ProductID) }}">{{ $product->ProductName }}</a>
                </h3>
                <div class="product-price">
                    <span class="price">{{ number_format($product->Price, 0, ',', '.') }}₫</span>
                </div>

                @if ($product->StockQuantity > 0)
                    <form action="{{ route('cart.add', $product->ProductID) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-add-cart">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Thêm vào giỏ
                        </button>
                    </form>
                @else
                    <button class="btn-add-cart btn-disabled" disabled>
                        Tạm hết hàng
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        @if ($products->onFirstPage())
        <span class="page-item disabled">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </span>
        @else
        <a href="{{ $products->previousPageUrl() }}" class="page-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </a>
        @endif

        @foreach ($products->links()->elements[0] ?? [] as $page => $url)
            @if ($page == $products->currentPage())
                <span class="page-item active">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-item">{{ $page }}</a>
            @endif
        @endforeach

        @if ($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}" class="page-item">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
        @else
        <span class="page-item disabled">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </span>
        @endif
    </div>
</section>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ===== HERO SECTION ===== */
    .hero {
        position: relative;
        background: linear-gradient(135deg, rgba(255, 105, 135, 0.85), rgba(255, 182, 193, 0.6)),
            url('https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1600&q=80') center/cover no-repeat;
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 5%;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-content {
        max-width: 700px;
        text-align: center;
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero h2 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        color: #fff;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        letter-spacing: -1px;
    }

    .hero p {
        font-size: 1.3rem;
        margin-bottom: 40px;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 300;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .hero-stats {
        display: flex;
        gap: 40px;
        justify-content: center;
        margin-top: 40px;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 20px 30px;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* ===== PRODUCTS SECTION ===== */
    .products {
        padding: 80px 5%;
        background: linear-gradient(to bottom, #fff 0%, #fff8f9 100%);
    }

    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        color: #2d3436;
        font-weight: 700;
        margin-bottom: 12px;
        position: relative;
        display: inline-block;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #ff6b9d, #c9184a);
        border-radius: 2px;
    }

    .section-subtitle {
        color: #636e72;
        font-size: 1.1rem;
        margin-top: 20px;
    }

    /* PRODUCT GRID */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    /* PRODUCT CARD */
    .product-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(255, 107, 157, 0.2);
    }

    .product-card.out-of-stock {
        opacity: 0.7;
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 280px;
        background: #f8f9fa;
    }

    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image-wrapper img {
        transform: scale(1.08);
    }

    /* STOCK BADGE */
    .stock-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        z-index: 2;
    }

    .badge-low {
        background: rgba(255, 193, 7, 0.95);
        color: #856404;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .badge-out {
        background: rgba(220, 53, 69, 0.95);
        color: #fff;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    /* PRODUCT INFO */
    .product-info {
        padding: 20px;
    }

    .product-info h3 {
        margin-bottom: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .product-info h3 a {
        color: #2d3436;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .product-info h3 a:hover {
        color: #ff6b9d;
    }

    .product-price {
        margin-bottom: 15px;
    }

    .price {
        font-size: 1.4rem;
        font-weight: 700;
        color: #c9184a;
    }

    /* ADD TO CART BUTTON */
    .btn-add-cart {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #ff6b9d 0%, #c9184a 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(201, 24, 74, 0.3);
    }

    .btn-add-cart:active {
        transform: translateY(0);
    }

    .btn-disabled {
        background: linear-gradient(135deg, #b2bec3 0%, #95a5a6 100%);
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-disabled:hover {
        transform: none;
        box-shadow: none;
    }

    /* ===== PAGINATION ===== */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        margin-top: 60px;
    }

    .page-item {
        min-width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #fff;
        border: 2px solid #f1f3f5;
        color: #495057;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .page-item:hover:not(.disabled):not(.active) {
        background: #fff5f7;
        border-color: #ffb3c6;
        color: #c9184a;
        transform: translateY(-2px);
    }

    .page-item.active {
        background: linear-gradient(135deg, #ff6b9d, #c9184a);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 12px rgba(201, 24, 74, 0.25);
    }

    .page-item.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .hero {
            min-height: 60vh;
            padding: 40px 5%;
        }

        .hero h2 {
            font-size: 2.2rem;
        }

        .hero p {
            font-size: 1.1rem;
        }

        .hero-stats {
            flex-wrap: wrap;
            gap: 15px;
        }

        .stat-item {
            padding: 15px 20px;
        }

        .stat-number {
            font-size: 1.5rem;
        }

        .section-header h2 {
            font-size: 2rem;
        }

        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-image-wrapper {
            height: 240px;
        }

        .pagination {
            margin-top: 40px;
        }

        .page-item {
            min-width: 38px;
            height: 38px;
        }
    }

    @media (max-width: 480px) {
        .hero h2 {
            font-size: 1.8rem;
        }

        .product-grid {
            grid-template-columns: 1fr;
        }

        .hero-stats {
            gap: 10px;
        }

        .stat-item {
            flex: 1;
            padding: 12px 15px;
        }
    }
</style>

@endsection