@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<section class="products">
    <!-- Search bar giống UI trang chủ -->
    <div class="search-hero mb-4" style="max-width:900px;margin:0 auto;">
        <form action="{{ route('product.search') }}" method="GET" class="position-relative d-flex align-items-center" autocomplete="off">
            <div class="input-group">
                <input type="text" id="pageSearchInput" name="query" class="form-control rounded-start-pill"
                    placeholder="🔍 Tìm sản phẩm..." value="{{ request('query', $query) }}"
                    style="border-right: none;">
                <button type="submit" class="btn btn-pink rounded-end-pill text-white"
                    style="background-color:#d63384;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            <!-- Dropdown kết quả -->
            <div id="pageSearchResults"
                class="position-absolute bg-white shadow rounded-3 border mt-1 w-100"
                style="top: 100%; left: 0; display: none; max-height: 300px; overflow-y: auto; z-index: 2000;">
            </div>
        </form>
    </div>

    <h3 class="mb-4">🔎 Kết quả tìm kiếm cho: <em>"{{ $query }}"</em></h3>
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
                <form action="{{ route('cart.add', $product->ProductID) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn add-cart-btn">🛍️ Thêm vào giỏ hàng</button>
                </form>
            </div>
        @empty
            <p class="mt-4">Không tìm thấy sản phẩm nào </p>

        @endforelse
    </div>

    <div class="pagination-container" style="margin-top: 30px; text-align:center;">
    {{ $products->appends(['query' => $query])->links('pagination::bootstrap-5') }}
</div>
</section>


<style>
   
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

    .product-grid {
        justify-content: center;
    }

    .product-grid:has(.product:only-child) {
        display: flex;
        justify-content: center;
    }

    .product-grid:has(.product:only-child) .product {
        width: 320px;
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

<script>
    // AJAX tìm kiếm cho trang kết quả (id riêng để không conflict với header)
    (function(){
        const input = document.getElementById('pageSearchInput');
        const results = document.getElementById('pageSearchResults');
        if (!input || !results) return;

        let timer = null;
        input.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(timer);
            if (query.length < 1) {
                results.style.display = 'none';
                return;
            }
            timer = setTimeout(() => {
                fetch(`/ajax-search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            results.innerHTML = data.map(product => `
                                <a href="/products/${product.ProductID}" class="search-item d-flex align-items-center p-2 border-bottom">
                                    <img src="/assets/product_images/${product.Image}" alt="${product.ProductName}" style="width:56px;height:56px;object-fit:cover;border-radius:6px;margin-right:10px;">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">${product.ProductName}</div>
                                        <div class="search-price text-muted">${Number(product.Price).toLocaleString()} đ</div>
                                    </div>
                                </a>
                            `).join('');
                            results.style.display = 'block';
                        } else {
                            results.innerHTML = `<div class="text-center text-muted p-2">Không tìm thấy sản phẩm nào 😢</div>`;
                            results.style.display = 'block';
                        }
                    })
                    .catch(err => console.error(err));
            }, 200);
        });

        document.addEventListener('click', e => {
            if (!results.contains(e.target) && !input.contains(e.target)) {
                results.style.display = 'none';
            }
        });
    })();
</script>

@endsection
