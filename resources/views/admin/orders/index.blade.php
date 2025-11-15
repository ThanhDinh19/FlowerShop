@extends('layouts.admin')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📦 Quản lý đơn hàng</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Cập nhật</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->OrderID }}</td>
                <td>{{ $order->user->LastName }} {{ $order->user->FirstName }}</td>
                <td>{{ $order->OrderDate }}</td>
                <td>{{ number_format($order->TotalAmount, 0, ',', '.') }} đ</td>
                <td>
                    @if($order->Status == 'pending')
                        <span class="badge bg-warning text-dark">Chờ xử lý</span>
                    @elseif($order->Status == 'shipping')
                        <span class="badge bg-info text-dark">Đang giao</span>
                    @elseif($order->Status == 'delivered')
                        <span class="badge bg-success">Hoàn tất</span>
                    @elseif($order->Status =='paid')
                        <span class="badge bg-info">Đã thanh toán</span>
                    @elseif($order->Status =='unpaid')
                        <span class="badge bg-danger">Thanh toán không thành công</span>
                    @endif
                </td>
                <td>
                    @if($order->PaymentMethod == 'cash')
                        <span class="badge bg-secondary">💵 Tiền mặt</span>
                    @else
                        <span class="badge bg-primary">🏦 Chuyển khoản</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.orders.updateStatus', $order->OrderID) }}" method="POST" class="d-flex align-items-center gap-2">
                        @csrf
                        <select name="Status" class="form-select form-select-sm" style="width: 150px;">
                            <option value="pending" {{ $order->Status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="shipping" {{ $order->Status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="delivered" {{ $order->Status == 'delivered' ? 'selected' : '' }}>Hoàn tất</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
