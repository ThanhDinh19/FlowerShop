@extends('layouts.app')
@section('title', 'Liên hệ - Flower Shop')
@section('content')

<div class="contact-hero text-center text-white py-5">
    <h1 class="fw-bold display-4 animate-fade-in">💌 Liên hệ với Flower Shop</h1>
    <p class="lead animate-fade-in-delay">Chúng tôi luôn sẵn sàng lắng nghe bạn 🌷</p>
</div>

<div class="container my-5">
    <div class="row g-5">
        <!-- Thông tin liên hệ -->
        <div class="col-lg-5">
            <div class="contact-info bg-white rounded-4 shadow-sm p-4 mb-4">
                <h4 class="fw-bold text-pink mb-3">🌸 Thông tin cửa hàng</h4>
                <p><strong>Địa chỉ:</strong> 140 Lê Trọng Tấn, Quận Tân Phú, TP.HCM</p>
                <p><strong>Điện thoại:</strong> 0909 123 456</p>
                <p><strong>Email:</strong> support@flowershop.vn</p>
                <p><strong>Giờ mở cửa:</strong> 7:30 - 21:30 (T2 - CN)</p>
            </div>

            <div class="contact-map bg-white rounded-4 shadow-sm p-4 text-center">
                <h5 class="fw-semibold text-pink mb-3">📍 Bản đồ</h5>
                <div class="map-container rounded-4 overflow-hidden">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.110874554306!2d106.63032777480446!3d10.801908489344266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ee021a98a1f%3A0x6e15fcb0c6b2bb7b!2zMTQwIMSQLiBMw6ogVHLhu5FuZyBU4bqjbiwgVMOibiBQaMO6LCBUUC5IQ00!5e0!3m2!1svi!2s!4v1730730142803!5m2!1svi!2s"
                        width="100%"
                        height="300"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>

        <!-- Form liên hệ -->
        <div class="col-lg-7">
            <div class="contact-form bg-white rounded-4 shadow-sm p-4">
                <h4 class="fw-bold text-pink mb-3">📬 Gửi lời nhắn cho chúng tôi</h4>
                <p class="text-muted mb-4">Điền thông tin bên dưới, Flower Shop sẽ phản hồi bạn trong thời gian sớm nhất.</p>

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" name="name" id="name" class="form-control rounded-pill" placeholder="Nhập họ tên của bạn" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="email" class="form-control rounded-pill" placeholder="example@gmail.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label fw-semibold">Chủ đề</label>
                        <input type="text" name="subject" id="subject" class="form-control rounded-pill" placeholder="Ví dụ: Đặt hoa sinh nhật">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label fw-semibold">Nội dung</label>
                        <textarea name="message" id="message" rows="4" class="form-control rounded-4" placeholder="Nhập nội dung liên hệ..." required></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-pink rounded-pill px-4 py-2">🌼 Gửi liên hệ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --pink-main: #ff6b9c;
        --pink-light: #fff5f9;
        --pink-gradient: linear-gradient(135deg, #ffaec9, #ff6b9c);
        --text-dark: #333;
    }

    body {
        background-color: var(--pink-light);
        font-family: 'Poppins', sans-serif;
    }

    /* 🌸 Hero */
    .contact-hero {
        background: var(--pink-gradient);
        position: relative;
        overflow: hidden;
    }

    .contact-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: url("{{ asset('assets/images/flower_pattern.png') }}") center/cover no-repeat;
        opacity: 0.08;
    }

    .contact-hero h1,
    .contact-hero p {
        position: relative;
        z-index: 1;
    }

    /* 🌸 Info Section */
    .contact-info p {
        margin-bottom: 8px;
        color: var(--text-dark);
    }

    .text-pink {
        color: var(--pink-main);
    }

    .btn-pink {
        background: var(--pink-gradient);
        color: white;
        border: none;
        transition: 0.3s;
    }

    .btn-pink:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(255, 107, 156, 0.4);
    }

    .form-control {
        border: 1.5px solid #ffe1eb;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--pink-main);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 156, 0.2);
    }

    .map-container {
        position: relative;
        width: 100%;
        height: 300px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(255, 107, 156, 0.15);
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* 🌸 Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease forwards;
    }

    .animate-fade-in-delay {
        opacity: 0;
        animation: fadeIn 1.2s ease forwards;
        animation-delay: 0.3s;
    }
</style>

@endsection