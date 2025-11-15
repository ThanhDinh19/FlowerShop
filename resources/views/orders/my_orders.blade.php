@extends('layouts.app')
@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center">🧾 Đơn hàng của tôi</h2>

    @if($orders->isEmpty())
        <p class="text-center text-muted">Bạn chưa có đơn hàng nào.</p>
    @else
    <table class="table table-bordered align-middle">
        <thead class="table-pink">
            <tr>
                <th>Mã đơn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->OrderID }}</td>
                <td>{{ \Carbon\Carbon::parse($order->OrderDate)->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($order->TotalAmount, 0, ',', '.') }} đ</td>
                <td>
                    @if($order->Status == 'pending')
                        <span class="badge bg-warning text-dark">⏳ Chờ xử lý</span>
                    @elseif($order->Status == 'shipping')
                        <span class="badge bg-info text-dark">🚚 Đang giao hàng</span>
                    @elseif($order->Status == 'delivered')
                        <span class="badge bg-success">✅ Giao hàng thành công</span>
                    @elseif($order->Status =='paid')
                        <span class="badge bg-info">Đã thanh toán</span>
                    @elseif($order->Status =='unpaid')
                        <span class="badge bg-danger">Thanh toán không thành công</span>
                    @endif
                </td>
                <td><a href="{{ route('orders.invoice', $order->OrderID) }}" class="btn btn-sm btn-outline-pink">Xem</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>

<style>
.table-pink { background-color: #ffe0eb; }
.btn-outline-pink {
    border: 1px solid #d63384;
    color: #d63384;
}
.btn-outline-pink:hover {
    background-color: #d63384;
    color: white;
}
</style>
@endsection
