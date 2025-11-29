    @extends('layouts.app')
    @section('title','Giỏ hàng')

    @section('content')
    <div class="cart-container">
        <h2>🛒 Giỏ hàng của bạn</h2>

        @if(session('success'))
        <div class="flash">{{ session('success') }}</div>
        @endif

        @if($cartItems->isEmpty())
        <p class="empty">Giỏ hàng trống.</p>
        @else
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Hình</th>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="cart-body">
                @foreach($cartItems as $item)
                <tr data-id="{{ $item->CartItemID }}" data-stock="{{ $item->product->StockQuantity }}">
                    <td><img src="{{ asset('assets/product_images/'.$item->product->Image) }}" width="80" class="product-img"></td>
                    <td class="product-name">
                        {{ $item->product->ProductName }}
                        @if($item->product->StockQuantity == 0)
                            <span class="badge out-of-stock">Hết hàng 🌸</span>
                        @elseif($item->product->StockQuantity <= 5)
                            <span class="badge low-stock">Chỉ còn {{ $item->product->StockQuantity }} sản phẩm</span>
                        @endif
                    </td>
                    
                    <td class="unit-price" data-price="{{ $item->product->Price }}">{{ number_format($item->product->Price,0,',','.') }}đ</td>
                    <td>
                        <div class="qty-control">
                            <button class="qty-btn minus">−</button>
                            <input type="number" value="{{ $item->Quantity }}" min="1" class="qty-input" readonly>
                            <button class="qty-btn plus" {{ $item->product->StockQuantity == 0 ? 'disabled' : '' }}>+</button>
                        </div>
                    </td>
                    <td class="item-total">{{ number_format($item->product->Price * $item->Quantity,0,',','.') }}đ</td>
                    <td>
                        <form action="{{ route('cart.remove', $item->CartItemID) }}" method="POST" class="inline-form">
                            @csrf
                            <button type="submit" class="btn remove-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4"><strong>Tổng cộng</strong></td>
                    <td colspan="2"><strong class="total" id="cart-total">{{ number_format($total,0,',','.') }} VNĐ</strong></td>
                </tr>
            </tbody>
        </table>

        @if(!$cartItems->isEmpty())
        <div class="text-end mt-3">
            <a href="{{ route('orders.checkout') }}" class="btn btn-success rounded-pill px-4 py-2">💳 Thanh toán</a>
        </div>
        @endif
        @endif
    </div>

    {{-- ========== JS Tự động cập nhật ========== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = '{{ csrf_token() }}';

            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const row = this.closest('tr');
                    const input = row.querySelector('.qty-input');
                    const price = parseInt(row.querySelector('.unit-price').dataset.price);
                    const itemId = row.dataset.id;
                    const stock = parseInt(row.dataset.stock); // ✅ lấy tồn kho thực tế
                    let qty = parseInt(input.value);

                    if (this.classList.contains('plus')) {
                        if (qty < stock) {
                            qty++;
                        } else {
                            alert('⚠️ Sản phẩm chỉ còn ' + stock + ' sản phẩm trong kho! Không thêm được sản phẩm này nữa');
                            return;
                        }
                    } else if (this.classList.contains('minus') && qty > 1) {
                        qty--;
                    }

                    input.value = qty;

                    // Gọi AJAX cập nhật trên server
                    const response = await fetch(`/cart/update/${itemId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ quantity: qty })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.item_total && data.cart_total) {
                            row.querySelector('.item-total').textContent =
                                data.item_total.toLocaleString('vi-VN') + 'đ';
                            document.getElementById('cart-total').textContent =
                                data.cart_total.toLocaleString('vi-VN') + 'đ';
                        }
                    } else {
                        // Thử parse JSON lỗi từ server
                        let err = 'Cập nhật thất bại';
                        try {
                            const body = await response.json();
                            if (body && body.error) err = body.error;
                        } catch (e) {}
                        alert('⚠️ ' + err);
                        // Khôi phục giá trị input về giá trị cũ để tránh bất nhất
                        input.value = qty > stock ? stock : qty;
                    }
                });
            });
        });
        // document.addEventListener('DOMContentLoaded', function() {
        //     const csrfToken = '{{ csrf_token() }}';

        //     document.querySelectorAll('.qty-btn').forEach(btn => {
        //         btn.addEventListener('click', async function() {
        //             const row = this.closest('tr');
        //             const input = row.querySelector('.qty-input');
        //             const price = parseInt(row.querySelector('.unit-price').dataset.price);
        //             const itemId = row.dataset.id;
        //             let qty = parseInt(input.value);

        //             // tăng hoặc giảm
        //             if (this.classList.contains('plus')) qty++;
        //             else if (this.classList.contains('minus') && qty > 1) qty--;

        //             input.value = qty;

        //             // Gọi AJAX cập nhật trên server
        //             const response = await fetch(`/cart/update/${itemId}`, {
        //                 method: 'POST',
        //                 headers: {
        //                     'Content-Type': 'application/json',
        //                     'X-CSRF-TOKEN': csrfToken,
        //                     'Accept': 'application/json' // ✅ thêm dòng này ở đây
        //                 },
        //                 body: JSON.stringify({
        //                     quantity: qty
        //                 })
        //             });


        //             if (response.ok) {
        //                 try {
        //                     const data = await response.json();

        //                     if (data.item_total && data.cart_total) {
        //                         // Cập nhật thành tiền từng sản phẩm
        //                         row.querySelector('.item-total').textContent =
        //                             data.item_total.toLocaleString('vi-VN') + 'đ';

        //                         // Cập nhật tổng toàn giỏ
        //                         document.getElementById('cart-total').textContent =
        //                             data.cart_total.toLocaleString('vi-VN') + 'đ';
        //                     } else {
        //                         console.warn('Phản hồi không đúng định dạng JSON:', data);
        //                     }
        //                 } catch (e) {
        //                     console.error('Không parse được JSON:', e);
        //                 }
        //             } else {
        //                 console.error('Cập nhật thất bại:', response.status);
        //             }

        //         });
        //     });
        // });
    </script>

    {{-- ========== CSS ========== --}}
    <style>
    .low-stock {
        display: inline-block;
        background-color: #fff3cd;
        color: #856404;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        margin-left: 5px;
    }

    .out-of-stock {
        display: inline-block;
        background-color: #f8d7da;
        color: #842029;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
        margin-left: 5px;
    }

        .cart-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 25px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .cart-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th,
        .cart-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .product-img {
            border-radius: 10px;
        }

        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn {
            background: #3498db;
            color: #fff;
            border: none;
            width: 20px;
            height: 18px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        .qty-btn:hover {
            background: #2d83c7;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn.remove-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn.remove-btn:hover {
            background: #c0392b;
        }

        .total-row {
            background: #f9fafb;
            font-weight: bold;
        }

        .total {
            color: #e67e22;
            font-size: 18px;
        }
    </style>
    @endsection