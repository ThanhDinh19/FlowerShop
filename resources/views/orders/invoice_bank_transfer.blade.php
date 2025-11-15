@extends('layouts.app')
@section('title', 'Hóa đơn chuyển khoản')

@section('content')
<div class="invoice-container">
    {{-- ✅ Thanh toán thành công --}}
    @if(session('success'))
    <div class="payment-success">
        <div class="checkmark">✅</div>
        <div class="message">{{ session('success') }}</div>
    </div>
    @endif

    <h2>💳 Hóa đơn thanh toán chuyển khoản</h2>

    <div class="section">
        <h4>👤 Thông tin khách hàng</h4>
        <p><strong>Tên khách hàng:</strong> {{ $order->user->LastName }}</p>
        <p><strong>Email:</strong> {{ $order->user->Email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->user->PhoneNumber }}</p>
        <p><strong>Địa chỉ giao hàng:</strong> {{ $order->RecipientAddress }}</p>
    </div>

    <div class="section">
        <h4>🛍️ Chi tiết đơn hàng</h4>
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->ProductName }}</td>
                    <td>{{ $item->Quantity }}</td>
                    <td>{{ number_format($item->UnitPrice,0,',','.') }}đ</td>
                    <td>{{ number_format($item->TotalPrice,0,',','.') }}đ</td>
                </tr>
                @endforeach
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Tổng cộng:</td>
                    <td>{{ number_format($order->TotalAmount,0,',','.') }}đ</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section payment-info">
        <h4>🏦 Thông tin thanh toán</h4>
        <p><strong>Phương thức:</strong> Chuyển khoản ngân hàng</p>
        <p><strong>Trạng thái:</strong> 
            <span class="badge bg-success">Đã thanh toán</span>
        </p>
        <p><strong>Ngân hàng:</strong> MB Bank</p>
        <p><strong>Số tài khoản:</strong> 123456789</p>
        <p><strong>Chủ tài khoản:</strong> CÔNG TY TNHH FLOWERSHOP</p>
        <p><strong>Mã đơn hàng:</strong> TT_{{ $order->OrderID }}</p>
        <p><strong>Thời gian giao hàng dự kiến:</strong> {{ $order->DeliveryDateTime }}</p>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">🏠 Về trang chủ</a>
        <a href="{{ route('orders.myOrders') }}" class="btn btn-primary ms-2">📦 Đơn hàng của tôi</a>
    </div>
</div>

<style>
.invoice-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 40px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(255, 107, 157, 0.15);
}

/* ✅ Thông báo thành công */
.payment-success {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    background: linear-gradient(90deg, #58d68d, #28a745);
    color: #fff;
    padding: 15px 25px;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    box-shadow: 0 5px 12px rgba(76,217,100,0.3);
    margin-bottom: 30px;
    animation: fadeIn 0.7s ease-out;
}
.payment-success .checkmark {
    font-size: 1.5rem;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.section {
    background: #fff9fb;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 3px 12px rgba(255, 182, 193, 0.15);
}
.section h4 {
    color: #c44569;
    border-bottom: 2px solid #ffd6e8;
    padding-bottom: 8px;
    margin-bottom: 15px;
}

.table {
    background: #fff;
    box-shadow: 0 2px 10px rgba(255, 107, 157, 0.1);
}
.table thead {
    background: linear-gradient(135deg, #ff6b9d, #c44569);
    color: #fff;
}
.table th, .table td {
    text-align: center;
    padding: 14px;
}
.payment-info {
    background: #f0fff5;
}
.badge.bg-success {
    background: #28a745 !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
setTimeout(() => {
  confetti({
    particleCount: 120,
    spread: 70,
    origin: { y: 0.7 }
  });
}, 400);
</script>

@endsection
