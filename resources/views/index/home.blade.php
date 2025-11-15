@extends('layouts.app')
@section('title','Trang chủ')
@section('content')
<!-- Hero section -->
<section class="hero">
    <div>
        <h2>Hoa Tươi Mỗi Ngày</h2>
        <p>Làm đẹp không gian sống cùng những bó hoa rực rỡ</p>
        <button>Mua ngay</button>
    </div>
</section>

<!-- Products section -->
<section class="products">
    <h2>Sản phẩm nổi bật</h2>
    <div class="product-grid">
        @foreach($products as $product)
        <div class="product {{ $product->StockQuantity == 0 ? 'out-of-stock' : '' }}">
            <a href="{{ route('products.show', $product->ProductID) }}">
                <img src="{{ asset('assets/product_images/'.$product->Image) }}" alt="{{ $product->ProductName }}">
            </a>
            <h3>
                <a href="{{ route('products.show', $product->ProductID) }}">{{ $product->ProductName }}</a>
            </h3>
            <p>{{ number_format($product->Price, 0, ',', '.') }}đ</p>

            {{-- thông báo tồn kho --}}
            @if ($product->StockQuantity==0)
                <span class="badge out-of-stock">Hết hàng</span>
            @elseif ($product->StockQuantity <= 5)
                <span class="badge low-stock">⚠️ Chỉ còn {{ $product->StockQuantity }} sản phẩm</span>
            @endif

            @if ($product->StockQuantity>0)
                <form action="{{ route('cart.add', $product->ProductID) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn add-cart-btn">🛍️ Thêm vào giỏ hàng</button>
                </form>
            @else
                <button class="btn add-cart-btn" disabled>🛍️ Không thể mua hàng</button>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="pagination">
        @if ($products->onFirstPage())
        <span class="disabled">«</span>
        @else
        <a href="{{ $products->previousPageUrl() }}">«</a>
        @endif

        @foreach ($products->links()->elements[0] ?? [] as $page => $url)
        @if ($page == $products->currentPage())
        <span class="active">{{ $page }}</span>
        @else
        <a href="{{ $url }}">{{ $page }}</a>
        @endif
        @endforeach

        @if ($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}">»</a>
        @else
        <span class="disabled">»</span>
        @endif
    </div>

</section>


<style>

    /* ===== STOCK BADGE ===== */
    .badge {
        display: inline-block;
        margin-bottom: 8px;
        font-size: 0.9rem;
        border-radius: 8px;
        padding: 5px 10px;
        font-weight: 600;
    }

    .low-stock {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .out-of-stock {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }

    /* ===== DISABLED BUTTON ===== */
    .disabled-btn {
        background: #ccc;
        cursor: not-allowed;
        color: #666;
        border-radius: 25px;
        padding: 10px 18px;
        border: none;
        font-weight: 500;
    }


    /* ===== HERO SECTION ===== */

    .product a {
        text-decoration: none;
        /* bỏ gạch dưới */
        color: #c9184a;
        /* đỏ hồng nhẹ */
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .product a:hover {
        color: #ff4d6d;
        /* đỏ sáng hơn khi hover */
        text-decoration: underline;
        /* có thể thêm gạch chân khi hover nếu thích */
    }

    .hero {
        position: relative;
        background: linear-gradient(135deg, rgba(255, 192, 203, 0.7), rgba(255, 182, 193, 0.4)),
            url('https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
        height: 70vh;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding-left: 10%;
        color: #fff;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .hero div {
        max-width: 500px;
    }

    .hero h2 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: #fff;
        letter-spacing: 1px;
    }

    .hero p {
        font-size: 1.2rem;
        margin-bottom: 25px;
        color: #f9f9f9;
    }

    .hero button {
        background: linear-gradient(135deg, #ff8fa3, #ff4d6d);
        color: #fff;
        border: none;
        padding: 12px 28px;
        font-size: 1rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(255, 77, 109, 0.3);
    }

    .hero button:hover {
        background: linear-gradient(135deg, #ff4d6d, #c9184a);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(201, 24, 74, 0.4);
    }

    /* ===== PRODUCTS SECTION ===== */
    .products {
        text-align: center;
        padding: 70px 8%;
        background-color: #fff8f9;
    }

    .products h2 {
        font-size: 2.2rem;
        color: #c9184a;
        text-transform: uppercase;
        margin-bottom: 45px;
        position: relative;
    }

    .products h2::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: #ff8fa3;
        margin: 10px auto 0;
        border-radius: 2px;
    }

    /* GRID */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 30px;
    }

    /* CARD */
    .product {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .product:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(255, 143, 171, 0.25);
    }

    .product img {
        width: 100%;
        height: 230px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .product:hover img {
        transform: scale(1.05);
    }

    .product h3 {
        font-size: 1.1rem;
        color: #222;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .product p {
        color: #c9184a;
        font-weight: 700;
        margin-bottom: 12px;
        font-size: 1rem;
    }

    /* BUTTON */
    .product button {
        padding: 10px 18px;
        background: linear-gradient(135deg, #ffb3c6, #c9184a);
        border: none;
        border-radius: 25px;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(201, 24, 74, 0.25);
    }

    .product button:hover {
        background: linear-gradient(135deg, #c9184a, #800f2f);
        transform: translateY(-3px);
        box-shadow: 0 6px 14px rgba(201, 24, 74, 0.35);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .hero {
            height: 55vh;
            padding-left: 5%;
            text-align: center;
            justify-content: center;
        }

        .hero div {
            max-width: 90%;
        }

        .hero h2 {
            font-size: 2rem;
        }

        .hero p {
            font-size: 1rem;
        }

        .product img {
            height: 190px;
        }
    }


    /* phân trang */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 40px;
        font-size: 1rem;
    }

    .pagination a,
    .pagination span {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid #ffb3c6;
        color: #c9184a;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background: #ffb3c6;
        color: white;
    }

    .pagination .active {
        background: linear-gradient(135deg, #ff8fa3, #c9184a);
        color: white;
        border: none;
    }

    .pagination .disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

@endsection