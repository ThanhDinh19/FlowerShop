@extends('layouts.app')
@section('title', $product->ProductName)

@section('content')
<div class="container-wrapper">
    <!-- Product Detail Section -->
    <div class="product-detail-card">
        <div class="product-image-section">
            <div class="image-container">
                <img src="{{ asset('assets/product_images/' . $product->Image) }}" alt="{{ $product->ProductName }}">
                <div class="image-badge">Hot</div>
            </div>
        </div>

        <div class="product-info-section">
            <div class="breadcrumb">
                <a href="{{ url('/') }}">Trang chủ</a>
                <span class="separator">›</span>
                <span class="current">{{ $product->ProductName }}</span>
            </div>

            <h1 class="product-title">{{ $product->ProductName }}</h1>

            <div class="rating-preview">
                @if(isset($averageRating))
                <div class="stars-inline">
                    @php $full = floor($averageRating); $half = ($averageRating - $full) >= 0.5; @endphp
                    @for($i=1;$i<=5;$i++)
                        @if($i <=$full)
                        <span class="star filled">★</span>
                        @elseif($half && $i == $full + 1)
                        <span class="star half">★</span>
                        @else
                        <span class="star">★</span>
                        @endif
                        @endfor
                </div>
                <span class="rating-text">{{ $averageRating }} ({{ $reviews->count() }} đánh giá)</span>
                @else
                <span class="rating-text">Chưa có đánh giá</span>
                @endif
            </div>

            <div class="price-section">
                <span class="current-price">{{ number_format($product->Price, 0, ',', '.') }} ₫</span>
            </div>

            <div class="description-section">
                <h3 class="section-label">Mô tả sản phẩm</h3>
                <p class="description-text">{{ $product->Description }}</p>
            </div>

            <div class="divider"></div>

            <div class="quantity-section">
                <label for="qty-input" class="qty-label">Số lượng</label>
                <div class="qty-controls" data-stock="{{ $product->StockQuantity }}">
                    <button type="button" class="qty-btn minus" onclick="decreaseQty()">−</button>
                    <input id="qty-input" type="number" name="quantity" min="1" value="1" readonly>
                    <button type="button" class="qty-btn plus" onclick="increaseQty()">+</button>
                </div>
            </div>

            <div class="action-buttons">

                @if ($product->StockQuantity > 0)

                {{-- Thêm vào giỏ --}}
                <form id="add-form" action="{{ route('cart.add', $product->ProductID) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn1 btn-add-cart">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        Thêm vào giỏ
                    </button>
                </form>

                {{-- Mua ngay --}}
                <form id="buy-form" action="{{ route('cart.add', $product->ProductID) }}" method="POST">
                    @csrf
                    <input type="hidden" name="checkout" value="1">
                    <input type="hidden" id="buy-now-qty" name="quantity" value="1">

                    <button type="submit" class="btn1 btn-buy-now" onclick="setBuyNowQuantity()">
                        Mua ngay
                    </button>
                </form>

                @else

                {{-- Nút disabled khi hết hàng --}}
                <button class="btn1 btn-disabled" disabled>
                    Hết hàng
                </button>

                <button class="btn1 btn-disabled" disabled>
                    Không thể mua
                </button>

                @endif
            </div>

        </div>
    </div>

    <!-- Reviews Section -->
    {{-- <div class="reviews-card">
        <div class="reviews-header">
            <h2 class="section-title">Đánh giá khách hàng</h2>
            @if(isset($averageRating))
                <div class="rating-summary">
                    <div class="rating-score">
                        <span class="score-number">{{ $averageRating }}</span>
    <span class="score-max">/5</span>
</div>
<div class="rating-stars">
    @php $full = floor($averageRating); $half = ($averageRating - $full) >= 0.5; @endphp
    @for($i=1;$i<=5;$i++)
        @if($i <=$full)
        <span class="star filled">★</span>
        @elseif($half && $i == $full + 1)
        <span class="star half">★</span>
        @else
        <span class="star">★</span>
        @endif
        @endfor
</div>
<div class="rating-count">{{ $reviews->count() }} đánh giá</div>
</div>
@endif
</div>

@if($reviews->count() > 0)
<div class="reviews-list">
    @foreach($reviews as $r)
    <div class="review-item">
        <div class="review-header">
            <div class="reviewer-info">
                <div class="reviewer-avatar">
                    @if(optional($r->user)->Avatar)
                    <img src="{{ $r->user->Avatar }}" alt="avatar">
                    @else
                    {{ strtoupper(substr(optional($r->user)->LastName ?? 'K', 0, 1)) }}
                    @endif
                </div>

                <div>
                    <!-- <div class="reviewer-name">
                        @php
                        $first = optional($r->user)->FirstName;
                        $last = optional($r->user)->LastName;
                        $fullName = trim($first . ' ' . $last);
                        @endphp
                        <div class="reviewer-name">
                            {{ $fullName ?: 'Khách' }}
                        </div>
                    </div> -->
                    <div class="reviewer-name">{{ $r->user->FirstName ?? 'Khách' }}</div>
                    <div class="review-date">{{ $r->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="review-stars">
                @for($i=1;$i<=5;$i++)
                    <span class="star {{ $i <= $r->rating ? 'filled' : '' }}">★</span>
                    @endfor
            </div>
        </div>

        @if($r->comment)
        <div class="review-comment">{{ $r->comment }}</div>
        @endif
    </div>
    @endforeach
</div>
@else
<div class="no-reviews">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
    </svg>
    <p>Chưa có đánh giá nào cho sản phẩm này</p>
</div>
@endif

<div class="review-form-section">
    @auth
    <h3 class="form-title">Viết đánh giá của bạn</h3>
    <form action="{{ route('reviews.store', $product->ProductID) }}" method="POST" class="review-form">
        @csrf
        <div class="form-group">
            <label class="form-label">Đánh giá của bạn</label>
            <div class="star-rating-input">
                @for($i=1;$i<=5;$i++)
                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                    <label for="star{{ $i }}" class="star-label">★</label>
                    @endfor
            </div>
        </div>
        <div class="form-group">
            <label for="comment" class="form-label">Nhận xét (tuỳ chọn)</label>
            <textarea name="comment" id="comment" rows="4" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm...">{{ old('comment') }}</textarea>
        </div>
        <button type="submit" class="btn1 btn-submit-review">Gửi đánh giá</button>

        @if($errors->any())
        <div class="error-message">{{ $errors->first() }}</div>
        @endif
    </form>
    @else
    <div class="login-prompt">
        <p>Vui lòng <a href="{{ route('login.form') }}" class="login-link">đăng nhập</a> để gửi đánh giá</p>
    </div>
    @endauth
</div>
</div> --}}

{{-- đạt cập nhật review--}}
<!-- Reviews Section -->
<div class="reviews-card">
    <div class="reviews-header">
        <h2 class="section-title">Đánh giá từ khách hàng</h2>

        @if($reviews->count())
        <div class="rating-summary">
            <div class="rating-score">
                <span class="score-number">{{ number_format($averageRating, 1) }}</span>
                <span class="score-max">/ 5</span>
            </div>

            <div class="rating-stars">
                @php
                $full = floor($averageRating);
                $half = ($averageRating - $full >= 0.5);
                @endphp

                @for($i = 1; $i <= 5; $i++)
                    @if($i <=$full)
                    <span class="star filled">★</span>
                    @elseif($half && $i == $full + 1)
                    <span class="star half">★</span>
                    @else
                    <span class="star">★</span>
                    @endif
                    @endfor
            </div>

            <div class="rating-count">{{ $reviews->count() }} đánh giá</div>
        </div>
        @else
        <p>Chưa có đánh giá</p>
        @endif
    </div>

    {{-- DANH SÁCH REVIEW --}}
    @if($reviews->count())
    <div class="reviews-list">
        @foreach($reviews as $r)
        <div class="review-item" id="review-{{ $r->id }}">
            <div class="review-header">
                <div class="reviewer-info">
                    <div class="reviewer-avatar">
                        {{ strtoupper(substr($r->user->FirstName ?? 'K', 0, 1)) }}
                    </div>
                    <div>
                        <div class="reviewer-name">{{ $r->user->FirstName ?? 'Khách' }}</div>
                        <div class="review-date">{{ $r->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>

                {{-- HIỂN THỊ SAO KHI CHƯA EDIT --}}
                <div class="review-stars display-mode">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $r->rating ? 'filled' : '' }}">★</span>
                        @endfor
                </div>


                {{-- FORM SAO KHI EDIT --}}
                <div class="review-stars edit-mode" style="display:none;">
                    @for($i=1;$i<=5;$i++)
                        <label>
                        <input type="radio" name="rating_{{ $r->id }}" value="{{ $i }}" {{ $i == $r->rating ? 'checked' : '' }}>
                        <span class="star {{ $i <= $r->rating ? 'filled' : '' }}">★</span>
                        </label>
                        @endfor
                </div>

                {{-- <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $r->rating ? 'filled' : '' }}">★</span>
                @endfor
            </div> --}}
        </div>

        {{-- COMMENT HIỂN THỊ --}}
        <div class="review-comment display-mode">
            {{ $r->comment }}
        </div>

        {{-- COMMENT EDIT --}}
        <div class="review-comment edit-mode" style="display:none;">
            <textarea class="form-control" id="edit-comment-{{ $r->id }}">{{ $r->comment }}</textarea>
        </div>


        {{-- @if($r->comment)
                            <div class="review-comment">{{ $r->comment }}
    </div>
    @endif --}}

    {{-- NÚT EDIT / DELETE --}}
    @auth
    @if(Auth::id() == $r->user_id)
    <div class="review-actions">
        <button class="review-btn edit" onclick="enterEditMode({{ $r->id }})">Sửa</button>

        <form action="{{ route('reviews.destroy', $r->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button class="review-btn delete" onclick="return confirm('Bạn chắc muốn xoá đánh giá này?')">Xóa</button>
        </form>

        {{-- NÚT CẬP NHẬT + HỦY (ẩn ban đầu) --}}
        {{-- <button class="btn1 btn-update edit-mode" style="display:none;" onclick="submitEdit({{ $r->id }})">Cập nhật</button>
        <button class="btn1 btn-cancel edit-mode" style="display:none;" onclick="cancelEdit({{ $r->id }})">Hủy</button> --}}
        <div class="edit-actions">
            <button class="review-btn save edit-mode" style="display:none;" onclick="submitEdit({{ $r->id }})">Cập nhật</button>
            <button class="review-btn cancel edit-mode" style="display:none;" onclick="cancelEdit({{ $r->id }})">Hủy</button>
        </div>
    </div>
    @endif
    @endauth
</div>
@endforeach
</div>
@else
<div class="no-reviews">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
    </svg>
    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
</div>
@endif


{{-- FORM REVIEW --}}
<div class="review-form-section">
    @auth
    @if($hasPurchased && !$hasReviewed)
    <h3 class="form-title">Viết đánh giá của bạn</h3>

    <form action="{{ route('reviews.store', $product->ProductID) }}" method="POST" class="review-form">
        @csrf

        @if($errors->has('review_error'))
        <div class="error-message">{{ $errors->first('review_error') }}</div>
        @endif

        <div class="form-group">
            <label class="form-label">Đánh giá của bạn</label>
            <div class="star-rating-input">
                @for($i = 5; $i >= 1; $i--)
                <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                <label for="star{{ $i }}" class="star-label">★</label>
                @endfor
            </div>
        </div>


        <div class="form-group">
            <label class="form-label">Nhận xét</label>
            <textarea name="comment" rows="4" placeholder="Hãy chia sẻ cảm nhận của bạn..."></textarea>
        </div>

        <button type="submit" class="btn1 btn-submit-review">Gửi đánh giá</button>
    </form>

    @elseif(!$hasPurchased)
    <p class="text-muted">Bạn cần mua sản phẩm này trước khi đánh giá.</p>

    @elseif($hasReviewed)
    <p class="text-muted">Bạn đã đánh giá sản phẩm này.</p>
    @endif

    @else
    <p>Vui lòng <a href="{{ route('login.form') }}">đăng nhập</a> để viết đánh giá.</p>
    @endauth
</div>
</div>

{{-- đạt cập nhật --}}

<!-- Related Products -->
@if(isset($relatedProducts) && $relatedProducts->count())
<div class="related-section">
    <h2 class="section-title">Sản phẩm liên quan</h2>
    <div class="related-grid">
        @foreach($relatedProducts as $rp)
        <a href="{{ route('products.show', $rp->ProductID) }}" class="related-card">
            <div class="related-image">
                <img src="{{ asset('assets/product_images/' . $rp->Image) }}" alt="{{ $rp->ProductName }}">
                <div class="related-overlay">
                    <span class="view-detail">Xem chi tiết</span>
                </div>
            </div>
            <div class="related-info">
                <p class="related-name">{{ \Illuminate\Support\Str::limit($rp->ProductName, 50) }}</p>
                <p class="related-price">{{ number_format($rp->Price, 0, ',', '.') }} ₫</p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
</div>

{{-- Pop up sửa review --}}
<div id="editModal" class="edit-modal" style="display:none;">
    <form id="editForm" method="POST">
        @csrf
        @method('PUT')

        <h3>Sửa đánh giá</h3>

        <label>Đánh giá:</label>
        <select name="rating" id="editRating" class="form-control">
            @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}">{{ $i }} sao</option>
                @endfor
        </select>

        <label>Bình luận:</label>
        <textarea name="comment" id="editComment" class="form-control"></textarea>

        <button class="btn1 mt-2">Cập nhật</button>
    </form>
</div>


<script>
    // đạt cập nhật review
    function enterEditMode(id) {
        let container = document.getElementById('review-' + id);

        container.querySelectorAll('.display-mode').forEach(el => el.style.display = 'none');
        container.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'block');
    }

    function cancelEdit(id) {
        let container = document.getElementById('review-' + id);

        container.querySelectorAll('.display-mode').forEach(el => el.style.display = 'block');
        container.querySelectorAll('.edit-mode').forEach(el => el.style.display = 'none');
    }

    function submitEdit(id) {
        let rating = document.querySelector('input[name="rating_' + id + '"]:checked').value;
        let comment = document.getElementById('edit-comment-' + id).value;

        fetch('/reviews/' + id, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    rating: rating,
                    comment: comment
                })
            })
            .then(response => {
                if (response.ok) location.reload();
                else alert('Lỗi cập nhật đánh giá');
            });
    }
    // đạt cập nhật review

    function decreaseQty() {
        let input = document.getElementById('qty-input');
        let value = parseInt(input.value);

        if (value > 1) {
            input.value = value - 1;

        }
    }

    function increaseQty() {
        let input = document.getElementById('qty-input');
        let value = parseInt(input.value);
        let maxStock = parseInt(document.querySelector('.qty-controls').dataset.stock);

        if (value < maxStock) {
            input.value = value + 1;

        } else {
            alert("Số lượng tối đa là " + maxStock + ". Không thể thêm nữa.");
        }
    }


    function syncQuantity() {
        var qtyInput = document.getElementById('qty-input');
        var addFormQty = document.querySelector('#add-form input[name="quantity"]');
        var buyFormQty = document.querySelector('#buy-form input[name="quantity"]');
        var value = parseInt(qtyInput.value) || 1;
        if (value < 1) value = 1;
        qtyInput.value = value;
        addFormQty.value = value;
        buyFormQty.value = value;
    }


    function setBuyNowQuantity() {
        let qty = document.getElementById('qty-input').value;
        document.getElementById('buy-now-qty').value = qty;
    }

    document.getElementById('qty-input').addEventListener('change', syncQuantity);
    syncQuantity();
</script>

<style>
    .btn-disabled {
        background: #ccc;
        cursor: not-allowed;
        opacity: 0.7;
    }

    /* Wrapper nút */
    .review-actions,
    .edit-actions {
        display: flex;
        gap: 10px;
        /* margin-top: 10px; */
    }

    .review-actions {
        margin-top: 10px;
    }

    /* Base button */
    .review-btn {
        padding: 6px 14px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Edit button */
    .review-btn.edit {
        background: #ffe3f0;
        color: #c2185b;
        border: 1px solid #ffc1d9;
    }

    .review-btn.edit:hover {
        background: #ffc7dd;
    }

    /* Delete button */
    .review-btn.delete {
        background: #ffe2e2;
        color: #d32f2f;
        border: 1px solid #ffbebe;
    }

    .review-btn.delete:hover {
        background: #ffbebe;
    }

    /* Save button */
    .review-btn.save {
        background: #e4f7e9;
        color: #2e7d32;
        border: 1px solid #b6e4c1;
    }

    .review-btn.save:hover {
        background: #c9efd1;
    }

    /* Cancel button */
    .review-btn.cancel {
        background: #f0f0f0;
        color: #555;
        border: 1px solid #d6d6d6;
    }

    .review-btn.cancel:hover {
        background: #dcdcdc;
    }


    .container-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* Product Detail Card */
    .product-detail-card {
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(201, 24, 74, 0.08);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        margin-bottom: 30px;
    }

    .product-image-section {
        background: linear-gradient(135deg, #fff5f7 0%, #ffe8ed 100%);
        padding: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .image-container {
        position: relative;
        width: 100%;
        max-width: 500px;
    }

    .image-container img {
        width: 100%;
        height: auto;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(201, 24, 74, 0.15);
        transition: transform 0.4s ease;
    }

    .image-container:hover img {
        transform: scale(1.05);
    }

    .image-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #ff4d6d, #c9184a);
        color: white;
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(201, 24, 74, 0.3);
    }

    .product-info-section {
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .breadcrumb {
        font-size: 0.9rem;
        margin-bottom: 20px;
        color: #666;
    }

    .breadcrumb a {
        color: #c9184a;
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb a:hover {
        color: #ff4d6d;
    }

    .breadcrumb .separator {
        margin: 0 8px;
        color: #ccc;
    }

    .breadcrumb .current {
        color: #333;
    }

    .product-title {
        font-size: 2.5rem;
        color: #c9184a;
        font-weight: 800;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .rating-preview {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
    }

    .stars-inline {
        display: flex;
        gap: 4px;
    }

    .stars-inline .star {
        color: #ddd;
        font-size: 1.2rem;
    }

    .stars-inline .star.filled {
        color: #ffb400;
    }

    .stars-inline .star.half {
        color: #ffb400;
    }

    .rating-text {
        color: #666;
        font-size: 0.95rem;
    }

    .price-section {
        margin-bottom: 30px;
    }

    .current-price {
        font-size: 2.2rem;
        font-weight: 800;
        color: #ff4d6d;
        letter-spacing: -1px;
    }

    .description-section {
        margin-bottom: 30px;
    }

    .section-label {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }

    .description-text {
        line-height: 1.8;
        color: #555;
        font-size: 1rem;
    }

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #ffe8ed, transparent);
        margin: 30px 0;
    }

    .quantity-section {
        margin-bottom: 30px;
    }

    .qty-label {
        display: block;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
        font-size: 1rem;
    }

    .qty-controls {
        display: inline-flex;
        align-items: center;
        border: 2px solid #ffe8ed;
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }

    .qty-btn {
        width: 45px;
        height: 45px;
        border: none;
        background: #fff5f7;
        color: #c9184a;
        font-size: 1.5rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .qty-btn:hover {
        background: #c9184a;
        color: white;
    }

    #qty-input {
        width: 70px;
        height: 45px;
        border: none;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        background: transparent;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .action-buttons form {
        flex: 1;
    }

    .btn1 {
        width: 100%;
        padding: 16px 32px;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #ff8fa3, #ff4d6d);
        color: white;
    }

    .btn-add-cart:hover {
        background: linear-gradient(135deg, #ff4d6d, #c9184a);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(201, 24, 74, 0.3);
    }

    .btn-buy-now {
        background: linear-gradient(135deg, #ffd93d, #ffb400);
        color: white;
    }

    .btn-buy-now:hover {
        background: linear-gradient(135deg, #ffb400, #ff9500);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 180, 0, 0.3);
    }

    /* Reviews Card */
    .reviews-card {
        background: white;
        border-radius: 24px;
        padding: 50px;
        box-shadow: 0 8px 32px rgba(201, 24, 74, 0.08);
        margin-bottom: 30px;
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 25px;
        border-bottom: 2px solid #ffe8ed;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: #c9184a;
    }

    .rating-summary {
        text-align: center;
    }

    .rating-score {
        margin-bottom: 8px;
    }

    .score-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #c9184a;
    }

    .score-max {
        font-size: 1.2rem;
        color: #999;
    }

    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-bottom: 8px;
    }

    .rating-stars .star {
        color: #ddd;
        font-size: 1.4rem;
    }

    .rating-stars .star.filled {
        color: #ffb400;
    }

    .rating-count {
        color: #666;
        font-size: 0.9rem;
    }

    .reviews-list {
        margin-bottom: 40px;
    }

    .review-item {
        padding: 25px;
        border-radius: 16px;
        background: #fafafa;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .review-item:hover {
        background: #fff5f7;
        box-shadow: 0 4px 16px rgba(201, 24, 74, 0.08);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .reviewer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff8fa3, #c9184a);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }

    .reviewer-name {
        font-weight: 700;
        color: #333;
        font-size: 1rem;
    }

    .review-date {
        font-size: 0.85rem;
        color: #999;
        margin-top: 2px;
    }

    .review-stars {
        display: flex;
        gap: 4px;
    }

    .review-stars .star {
        color: #ddd;
        font-size: 1.2rem;
    }

    .review-stars .star.filled {
        color: #ffb400;
    }

    .review-comment {
        color: #555;
        line-height: 1.7;
        font-size: 0.95rem;
    }

    .no-reviews {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .no-reviews svg {
        stroke: #ddd;
        margin-bottom: 20px;
    }

    .no-reviews p {
        font-size: 1.1rem;
    }

    /* Review Form */
    .review-form-section {
        background: linear-gradient(135deg, #fff5f7 0%, #ffe8ed 100%);
        border-radius: 16px;
        padding: 35px;
    }

    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #c9184a;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .star-rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 8px;
    }

    .star-rating-input input[type="radio"] {
        display: none;
    }

    .star-label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: all 0.2s;
    }

    .star-rating-input input[type="radio"]:checked~.star-label,
    .star-rating-input .star-label:hover,
    .star-rating-input .star-label:hover~.star-label {
        color: #ffb400;
        transform: scale(1.1);
    }

    textarea {
        width: 100%;
        padding: 15px;
        border: 2px solid #ffe8ed;
        border-radius: 12px;
        font-size: 0.95rem;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.3s;
    }

    textarea:focus {
        outline: none;
        border-color: #ff8fa3;
    }

    .btn-submit-review {
        background: linear-gradient(135deg, #ff8fa3, #c9184a);
        color: white;
        padding: 14px 40px;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
        box-shadow: 0 6px 20px rgba(201, 24, 74, 0.2);
    }

    .btn-submit-review:hover {
        background: linear-gradient(135deg, #c9184a, #a01040);
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(201, 24, 74, 0.3);
    }

    .error-message {
        margin-top: 15px;
        padding: 12px 18px;
        background: #ffe0e0;
        color: #d9534f;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .login-prompt {
        text-align: center;
        padding: 30px;
    }

    .login-prompt p {
        font-size: 1.1rem;
        color: #666;
    }

    .login-link {
        color: #c9184a;
        text-decoration: none;
        font-weight: 700;
        border-bottom: 2px solid transparent;
        transition: border-color 0.3s;
    }

    .login-link:hover {
        border-bottom-color: #c9184a;
    }

    /* Related Products */
    .related-section {
        background: white;
        border-radius: 24px;
        padding: 50px;
        box-shadow: 0 8px 32px rgba(201, 24, 74, 0.08);
    }

    .related-section .section-title {
        margin-bottom: 35px;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 25px;
    }

    .related-card {
        background: #fafafa;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .related-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(201, 24, 74, 0.15);
    }

    .related-image {
        position: relative;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .related-card:hover .related-image img {
        transform: scale(1.1);
    }

    .related-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(201, 24, 74, 0.8), rgba(255, 77, 109, 0.8));
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .related-card:hover .related-overlay {
        opacity: 1;
    }

    .view-detail {
        color: white;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    .related-info {
        padding: 20px;
    }

    .related-name {
        color: #333;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .related-price {
        color: #c9184a;
        font-weight: 800;
        font-size: 1.2rem;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .product-detail-card {
            grid-template-columns: 1fr;
        }

        .product-image-section {
            padding: 40px;
        }

        .product-info-section {
            padding: 40px 30px;
        }

        .reviews-card,
        .related-section {
            padding: 35px;
        }
    }

    @media (max-width: 768px) {
        .container-wrapper {
            padding: 20px 15px;
        }

        .product-title {
            font-size: 1.8rem;
        }

        .current-price {
            font-size: 1.8rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .reviews-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .rating-summary {
            text-align: left;
        }

        .reviews-card,
        .related-section {
            padding: 25px;
        }

        .section-title {
            font-size: 1.6rem;
        }

        .related-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 15px;
        }

        .related-image img {
            height: 160px;
        }

        .related-info {
            padding: 15px;
        }

        .related-name {
            font-size: 0.9rem;
        }

        .related-price {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .product-image-section {
            padding: 25px;
        }

        .product-info-section {
            padding: 25px 20px;
        }

        .product-title {
            font-size: 1.5rem;
        }

        .current-price {
            font-size: 1.5rem;
        }

        .btn {
            padding: 14px 24px;
            font-size: 0.9rem;
        }

        .form-title {
            font-size: 1.2rem;
        }

        .star-label {
            font-size: 1.6rem;
        }
    }

    /* header search is styled in layout (shared) to match homepage */
</style>

@endsection