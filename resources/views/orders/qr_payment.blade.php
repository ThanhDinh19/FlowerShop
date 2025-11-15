@extends('layouts.app')
@section('title', 'Thanh toán chuyển khoản')

@section('content')
<div class="qr-container text-center">
    <h2>🏦 Thanh toán chuyển khoản</h2>
    <p>Vui lòng dùng điện thoại quét mã QR để hoàn tất thanh toán đơn #{{ $order->OrderID }}</p>

    <div class="qr-box">
        <img src="{{ $qrImageUrl }}" alt="QR Code" class="qr-img">
    </div>

    <div class="bank-info">
        <h4>💳 Thông tin chuyển khoản</h4>
        <p><strong>Ngân hàng:</strong> MB Bank</p>
        <p><strong>Số tài khoản:</strong> 123456789</p>
        <p><strong>Chủ tài khoản:</strong> CÔNG TY TNHH FLOWERSHOP</p>
        <p><strong>Số tiền:</strong> {{ number_format($order->TotalAmount,0,',','.') }}đ</p>
        <p><strong>Nội dung:</strong> TT_{{ $order->OrderID }}</p>
    </div>

    <p id="countdown" class="text-muted mt-3">⏳ Đang chờ xác nhận thanh toán...</p>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <a href="{{ route('orders.cancel', ['id' => $order->OrderID]) }}" class="btn btn-outline-secondary">🏠 Quay về trang chủ</a>
         <a href="{{ $signedUrl }}" class="btn btn-success">
        💰 Tôi đã thanh toán</a>

    </div>
</div>

<style>
.qr-container { max-width:700px; margin:60px auto; background:#fff; border-radius:20px; padding:40px;
    box-shadow:0 8px 30px rgba(255,143,171,0.25); }
.qr-img { border:8px solid #ffe0eb; border-radius:10px; background:#fff; }
.bank-info { text-align:left; margin:30px auto; padding:20px; background:#fff5f8; border-radius:15px; }
</style>

<script>
    const checkUrl = "{{ route('orders.checkStatus', ['id' => $order->OrderID]) }}";
    const redirectUrl = "{{ route('orders.confirmPayment', ['order' => $order->OrderID]) }}";

    let tries = 0;
    const interval = setInterval(async () => {
        tries++;
        const res = await fetch(checkUrl, {headers: {'Accept':'application/json'}});
        const data = await res.json();
        if (data.status === 'paid') {
            clearInterval(interval);
            window.location.href = redirectUrl;
        }
        if (tries > 60) clearInterval(interval); // Dừng sau 5 phút
    }, 5000);

</script>
@endsection
