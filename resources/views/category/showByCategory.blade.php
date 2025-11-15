@extends('layouts.app')

@section('content')
<section class="products">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">🌸 Danh mục: {{ $category->CategoryName }}</h2>

        {{-- Form sắp xếp (GET) --}}
        <form method="GET" action="{{ route('category.showByCategory', $category->CategoryID) }}" class="d-flex align-items-center">
            <label for="sort" class="me-2">Sắp xếp:</label>
            <select name="sort" id="sort" class="form-select form-select-sm" style="width:160px;" onchange="this.form.submit()">
                <option value="" {{ empty($sort) ? 'selected' : '' }}>Mặc định</option>
                <option value="price_asc" {{ (isset($sort) && $sort === 'price_asc') ? 'selected' : '' }}>Giá: thấp → cao</option>
                <option value="price_desc" {{ (isset($sort) && $sort === 'price_desc') ? 'selected' : '' }}>Giá: cao → thấp</option>
            </select>
        </form>
    </div>

    <div class="product-grid">
        @forelse ($products as $product)
            <div class="product">
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
        @empty
            <p>Không có sản phẩm nào trong danh mục này 😢</p>

        @endforelse
    </div>
    <div class="pagination-container" style="margin-top: 30px; text-align:center;">
    {{ $products->links('pagination::bootstrap-5') }}
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
</style>
@endsection