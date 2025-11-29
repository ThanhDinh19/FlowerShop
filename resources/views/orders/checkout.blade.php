@extends('layouts.app')
@section('title', 'Xác nhận thanh toán')

@section('content')
<div class="checkout-container">
    <h2>Xác nhận thanh toán</h2>

    <form action="{{ route('orders.confirm') }}" method="POST">
        @csrf

        <div class="user-info">
            <h4>👤 Thông tin người dùng</h4>

            <p><strong>Khách Hàng Tên:</strong> {{ $user->LastName }}</p>
            <p><strong>Email:</strong> {{ $user->Email }}</p>

            <div class="mb-3">
                <label class="form-label fw-bold">📞 Số điện thoại</label>
                <input type="text" name="PhoneNumber"
                    class="form-control elegant-input @error('PhoneNumber') is-invalid @enderror"
                    value="{{ old('PhoneNumber', $user->PhoneNumber) }}">

                @error('PhoneNumber')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">📍 Địa chỉ</label>
                <input type="text" name="Address"
                    class="form-control elegant-input @error('Address') is-invalid @enderror"
                    value="{{ old('Address', $user->Address) }}">

                @error('Address')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h4 class="mt-4">🛒 Sản phẩm trong giỏ</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->ProductName }}</td>
                    <td>{{ $item->Quantity }}</td>
                    <td>{{ number_format($item->product->Price,0,',','.') }}đ</td>
                    <td>{{ number_format($item->product->Price * $item->Quantity,0,',','.') }}đ</td>
                </tr>
                @endforeach
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Tổng cộng:</td>
                    <td>{{ number_format($total,0,',','.') }}đ</td>
                </tr>
            </tbody>
        </table>

        @error('RecipientAddress')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror

        <h4 class="mt-4 section-title">🚚 Thông tin giao hàng</h4>

        @if ($errors->any())
        <div class="alert alert-danger rounded-4 py-3 shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="delivery-card p-4 rounded-4 shadow-sm mb-4">
            {{-- Thời gian giao hàng --}}
            <div class="mb-4">
                <label for="DeliveryDateTime" class="form-label fw-bold text-primary-emphasis">
                    ⏰ Thời gian giao hàng mong muốn
                </label>
                <input type="datetime-local" name="DeliveryDateTime" id="deliveryTime"
                    class="form-control elegant-input @error('DeliveryDateTime') is-invalid @enderror"
                    value="{{ old('DeliveryDateTime') }}" required>
                @error('DeliveryDateTime')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="text-muted fst-italic">
                    * Vui lòng chọn thời gian giao hàng cách hiện tại ít nhất <strong>2 giờ</strong>.
                </small>
            </div>

            {{-- Địa chỉ người nhận --}}
            <div class="mb-4">
                <label for="RecipientAddress" class="form-label fw-bold text-primary-emphasis">
                    📍 Địa chỉ người nhận
                </label>
                <input type="text" name="RecipientAddress" class="form-control elegant-input"
                    placeholder="VD: 140 Lê Trọng Tấn, Tân Phú, TP.HCM"
                    value="{{ old('RecipientAddress') }}">
                @error('RecipientAddress')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Lời nhắn --}}
            <div class="mb-4">
                <label for="MessageToRecipient" class="form-label fw-bold text-primary-emphasis">
                    💌 Lời nhắn đến người nhận
                </label>
                <textarea name="MessageToRecipient" class="form-control elegant-input"
                    rows="3" placeholder="Gửi tặng...">{{ old('MessageToRecipient') }}</textarea>
            </div>

            {{-- Phương thức thanh toán --}}
            <div class="mb-3">
                <label class="form-label fw-bold text-primary-emphasis mb-2">
                    💳 Phương thức thanh toán
                </label>

                <div class="payment-options d-flex flex-wrap gap-3">
                    <label class="payment-option">
                        <input type="radio" name="PaymentMethod" value="cash" {{ old('PaymentMethod', 'cash') === 'cash' ? 'checked' : '' }}>
                        <div class="option-content">
                            <span class="icon">💵</span> Tiền mặt khi nhận hàng
                        </div>
                    </label>

                    <label class="payment-option">
                        <input type="radio" name="PaymentMethod" value="vnpay" {{ old('PaymentMethod') === 'vnpay' ? 'checked' : '' }}>
                        <div class="option-content">
                            <span class="icon">💳</span> Thanh toán VNPay
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 shadow-sm fw-semibold">
                ✅ Xác nhận thanh toán
            </button>
        </div>
    </form>
</div>

<style>
    .section-title {
        font-size: 24px;
        color: #c9184a;
        font-weight: 700;
        border-left: 6px solid #ff8fa3;
        padding-left: 15px;
        margin-bottom: 25px;
        text-align: left;
    }

    .delivery-card {
        background: #fff;
        border: 2px solid #ffe0eb;
        border-left: 6px solid #ff8fa3;
        transition: all 0.3s ease;
    }

    .delivery-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(255, 107, 157, 0.2);
    }

    .elegant-input {
        border: 1.8px solid #ffd6e8;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 15px;
        transition: all 0.25s ease;
    }

    .elegant-input:focus {
        border-color: #ff8fa3;
        box-shadow: 0 0 0 4px rgba(255, 143, 171, 0.2);
    }

    .is-invalid {
        border-color: #e74c3c !important;
        background-color: #fff5f5 !important;
    }

    .payment-options {
        gap: 15px;
    }

    .payment-option {
        flex: 1;
        min-width: 220px;
        cursor: pointer;
        position: relative;
        transition: all 0.25s ease;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .option-content {
        border: 2px solid #ffd6e8;
        background: #fff;
        border-radius: 14px;
        padding: 14px 18px;
        text-align: center;
        transition: all 0.25s ease;
        font-weight: 600;
        color: #c9184a;
    }

    .option-content .icon {
        font-size: 22px;
        margin-right: 8px;
    }

    .payment-option input[type="radio"]:checked+.option-content {
        background: linear-gradient(135deg, #ffb3c6, #ff8fa3);
        border-color: #ff4d6d;
        color: white;
        box-shadow: 0 4px 12px rgba(255, 77, 109, 0.3);
    }

    .payment-option:hover .option-content {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(255, 143, 171, 0.15);
    }


    /* Checkout Container */
    .checkout-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 40px;
        background: linear-gradient(135deg, #fff5f7 0%, #ffe8f0 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(255, 107, 157, 0.15);
        position: relative;
        overflow: hidden;
    }

    .checkout-container::before {
        content: '🌸';
        position: absolute;
        font-size: 150px;
        top: -30px;
        right: -30px;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .checkout-container h2 {
        font-size: 32px;
        font-weight: 700;
        color: #c44569;
        text-align: center;
        margin-bottom: 40px;
        position: relative;
        padding-bottom: 15px;
    }

    .checkout-container h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: linear-gradient(90deg, #ff6b9d, #c44569);
        border-radius: 2px;
    }

    /* User Info Section */
    .user-info {
        background: #ffffff;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 30px;
        border: 2px solid #ffd6e8;
        box-shadow: 0 5px 20px rgba(255, 107, 157, 0.1);
        transition: transform 0.3s ease;
    }

    .user-info:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(255, 107, 157, 0.2);
    }

    .user-info h4 {
        font-size: 20px;
        font-weight: 700;
        color: #ff6b9d;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ffd6e8;
    }

    .user-info p {
        font-size: 16px;
        color: #555;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .user-info p strong {
        color: #c44569;
        font-weight: 600;
        display: inline-block;
        min-width: 150px;
    }

    /* Section Heading */
    .checkout-container h4.mt-4 {
        font-size: 22px;
        font-weight: 700;
        color: #ff6b9d;
        margin-top: 30px;
        margin-bottom: 20px;
        padding-left: 15px;
        border-left: 5px solid #ff6b9d;
    }

    /* Table Styling */
    .table {
        background: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(255, 107, 157, 0.1);
        margin-bottom: 30px;
    }

    .table thead {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        color: #ffffff;
    }

    .table thead th {
        font-weight: 600;
        padding: 18px 15px;
        border: none;
        text-align: center;
        font-size: 15px;
        letter-spacing: 0.5px;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background-color: #fff5f7;
        transform: scale(1.01);
    }

    .table tbody td {
        padding: 18px 15px;
        vertical-align: middle;
        color: #555;
        font-size: 15px;
        border-color: #ffd6e8;
        text-align: center;
    }

    .table tbody tr.fw-bold {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe8f0 100%);
        font-weight: 700;
    }

    .table tbody tr.fw-bold td {
        color: #c44569;
        font-size: 18px;
        padding: 20px 15px;
    }

    .table tbody tr.fw-bold td:last-child {
        color: #ff6b9d;
        font-size: 20px;
        font-weight: 800;
    }

    .table-bordered {
        border: 2px solid #ffd6e8;
    }

    /* Form & Button */
    form.text-end {
        margin-top: 30px;
        text-align: right;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
        border: none;
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        padding: 14px 40px !important;
        border-radius: 50px !important;
        box-shadow: 0 8px 20px rgba(255, 107, 157, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255, 107, 157, 0.4);
        background: linear-gradient(135deg, #ff8ab3 0%, #d65679 100%);
    }

    .btn-primary:active {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(255, 107, 157, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .checkout-container {
            margin: 20px;
            padding: 25px;
        }

        .checkout-container h2 {
            font-size: 26px;
        }

        .user-info {
            padding: 20px;
        }

        .user-info p strong {
            min-width: 100%;
            display: block;
            margin-bottom: 5px;
        }

        .table {
            font-size: 13px;
        }

        .table thead th,
        .table tbody td {
            padding: 12px 8px;
            font-size: 13px;
        }

        .table tbody tr.fw-bold td {
            font-size: 15px;
        }

        .table tbody tr.fw-bold td:last-child {
            font-size: 17px;
        }

        .btn-primary {
            width: 100%;
            margin-top: 15px;
        }

        form.text-end {
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .checkout-container {
            padding: 20px;
        }

        .checkout-container h2 {
            font-size: 22px;
        }

        .user-info h4,
        .checkout-container h4.mt-4 {
            font-size: 18px;
        }

        /* Make table scrollable on small screens */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('deliveryTime');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // fix múi giờ
        input.min = now.toISOString().slice(0, 16);
    });
</script>
@endsection