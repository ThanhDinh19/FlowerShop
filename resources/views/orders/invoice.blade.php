@extends('layouts.app')
@section('title', 'Hóa đơn')

@section('content')


<div class="invoice-container">
    <div class="invoice-info">
        <p><strong>Mã hóa đơn:</strong> #{{ $order->OrderID }}</p>
        <p><strong>Khách hàng:</strong> {{ $order->user->LastName }} {{ $order->user->FirstName }}</p>
        <p><strong>Email:</strong> {{ $order->user->Email }}</p>
        <p><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->OrderDate)->format('d/m/Y H:i') }}</p>
        <p><strong>Trạng thái:</strong>
            @if($order->Status == 'pending')
                <span class="badge bg-warning text-dark">Đang chờ xử lý</span>
            @elseif($order->Status == 'shipping')
                <span class="badge bg-info text-dark">Đang giao hàng</span>
            @elseif($order->Status == 'completed')
                <span class="badge bg-success">Giao hàng thành công</span>
            @else
                <span class="badge bg-secondary">{{ ucfirst($order->Status) }}</span>
            @endif
        </p>
    </div>

    <hr>

    <h4>🚚 Thông tin giao hàng</h4>
    <div class="shipping-info">
        <p><strong>Thời gian giao:</strong>
            {{ \Carbon\Carbon::parse($order->DeliveryDateTime)->format('d/m/Y H:i') }}
        </p>
        <p><strong>Địa chỉ người nhận:</strong> {{ $order->RecipientAddress }}</p>
        <p><strong>Phương thức thanh toán:</strong>
            @if($order->PaymentMethod == 'cash')
                Tiền mặt khi nhận hàng
            @else
                Thanh toán VNPay
            @endif
        </p>
        @if($order->MessageToRecipient)
        <div class="note-box">
            <strong>💌 Lời nhắn:</strong>
            <blockquote>{{ $order->MessageToRecipient }}</blockquote>
        </div>
        @endif
    </div>

    <hr>
    <table class="table">
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
        </tbody>
    </table>

    <h4 class="text-end mt-3">Tổng cộng:
        <span class="text-danger">{{ number_format($order->TotalAmount,0,',','.') }}đ</span>
    </h4>

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="btn btn-secondary">🏠 Quay về trang chủ</a>
    </div>
</div>

<style>
    /* Invoice Container */
    .invoice-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 50px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 50px rgba(255, 107, 157, 0.2);
        position: relative;
        overflow: hidden;
        border: 3px solid #ffd6e8;
    }

    .invoice-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: linear-gradient(90deg, #ff6b9d, #c44569, #ff6b9d);
        background-size: 200% 100%;
        animation: gradientMove 3s ease infinite;
    }

    @keyframes gradientMove {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .invoice-container::after {
        content: '🌸';
        position: absolute;
        font-size: 200px;
        bottom: -50px;
        right: -50px;
        opacity: 0.05;
        transform: rotate(-25deg);
        pointer-events: none;
    }

    /* Heading */   
    .invoice-container h2 {
        font-size: 36px;
        font-weight: 800;
        color: #c44569;
        text-align: center;
        margin-bottom: 35px;
        position: relative;
        padding-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .invoice-container h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 120px;
        height: 5px;
        background: linear-gradient(90deg, #ff6b9d, #c44569);
        border-radius: 3px;
    }

    /* Invoice Info */
    .invoice-container>p {
        font-size: 16px;
        color: #555;
        margin-bottom: 15px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #fff5f7 0%, #ffe8f0 100%);
        border-radius: 10px;
        border-left: 4px solid #ff6b9d;
        transition: all 0.3s ease;
    }

    .invoice-container>p:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(255, 107, 157, 0.15);
    }

    .invoice-container>p strong {
        color: #c44569;
        font-weight: 700;
        display: inline-block;
        min-width: 140px;
    }

    /* Table Styling */
    .invoice-container .table {
        background: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(255, 107, 157, 0.15);
        margin-top: 35px;
        margin-bottom: 30px;
        border: 2px solid #ffd6e8;
    }

    .invoice-container .table thead {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
    }

    .invoice-container .table thead th {
        color: #c44569;
        font-weight: 700;
        padding: 20px 15px;
        border: none;
        text-align: center;
        font-size: 16px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .invoice-container .table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #ffd6e8;
    }

    .invoice-container .table tbody tr:last-child {
        border-bottom: none;
    }

    .invoice-container .table tbody tr:hover {
        background: linear-gradient(135deg, #fff5f7 0%, #ffe8f0 100%);
        transform: scale(1.02);
        box-shadow: 0 3px 10px rgba(255, 107, 157, 0.1);
    }

    .invoice-container .table tbody td {
        padding: 20px 15px;
        vertical-align: middle;
        color: #555;
        font-size: 15px;
        text-align: center;
        border: none;
    }

    .invoice-container .table tbody td:first-child {
        font-weight: 600;
        color: #333;
    }

    .invoice-container .table tbody td:last-child {
        font-weight: 700;
        color: #ff6b9d;
    }

    /* Total Amount */
    .invoice-container h4 {
        font-size: 24px;
        font-weight: 800;
        color: #333;
        padding: 25px 30px;
        background: linear-gradient(135deg, #fff0f5 0%, #ffe8f0 100%);
        border-radius: 15px;
        border: 2px dashed #ff6b9d;
        margin-top: 30px;
    }

    .invoice-container h4 .text-danger {
        color: #c44569 !important;
        font-size: 28px;
        font-weight: 900;
        margin-left: 10px;
        display: inline-block;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    /* Button Styling */
    .invoice-container .text-center {
        margin-top: 40px;
    }

    .invoice-container .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border: none;
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        padding: 14px 40px;
        border-radius: 50px;
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        position: relative;
        overflow: hidden;
    }

    .invoice-container .btn-secondary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .invoice-container .btn-secondary:hover::before {
        left: 100%;
    }

    .invoice-container .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(108, 117, 125, 0.4);
        background: linear-gradient(135deg, #7d8a93 0%, #5a6268 100%);
        color: #ffffff;
    }

    .invoice-container .btn-secondary:active {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
    }

    /* Print Styling */
    @media print {
        .invoice-container {
            box-shadow: none;
            border: 1px solid #ddd;
            margin: 0;
        }

        .invoice-container .btn-secondary {
            display: none;
        }

        .invoice-container::before,
        .invoice-container::after {
            display: none;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .invoice-container {
            margin: 20px;
            padding: 30px 20px;
        }

        .invoice-container h2 {
            font-size: 28px;
        }

        .invoice-container>p {
            padding: 10px 15px;
            font-size: 14px;
        }

        .invoice-container>p strong {
            min-width: 100%;
            display: block;
            margin-bottom: 5px;
        }

        .invoice-container .table {
            font-size: 13px;
        }

        .invoice-container .table thead th,
        .invoice-container .table tbody td {
            padding: 15px 10px;
            font-size: 13px;
        }

        .invoice-container h4 {
            font-size: 20px;
            padding: 20px 15px;
        }

        .invoice-container h4 .text-danger {
            font-size: 24px;
            display: block;
            margin-top: 10px;
            margin-left: 0;
        }

        .invoice-container .btn-secondary {
            width: 100%;
            padding: 14px 20px;
        }
    }

    @media (max-width: 480px) {
        .invoice-container {
            padding: 25px 15px;
            margin: 15px;
        }

        .invoice-container h2 {
            font-size: 24px;
        }

        /* Make table scrollable */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .invoice-container .table thead th,
        .invoice-container .table tbody td {
            padding: 12px 8px;
            font-size: 12px;
        }
    }
</style>

@endsection