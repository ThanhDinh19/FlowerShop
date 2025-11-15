@extends('layouts.app')
@section('title', 'Giới thiệu - Flower Shop')
@section('content')

<div class="about-hero text-center text-white py-5">
    <h1 class="fw-bold display-4 animate-fade-in">🌸 Flower Shop 🌸</h1>
    <p class="lead animate-fade-in-delay">Nâng niu từng cánh hoa – Gửi trọn yêu thương</p>
</div>

<div class="container my-5">
    <div class="p-5 bg-white rounded-4 shadow-sm info-section mb-5">
        <h3 class="fw-bold text-pink mb-3">💐 Về Flower Shop</h3>
        <p>
            <strong>Flower Shop</strong> được thành lập với sứ mệnh mang lại những khoảnh khắc hạnh phúc
            và cảm xúc chân thành qua từng bông hoa. Chúng tôi tin rằng mỗi cánh hoa là một biểu tượng
            của tình yêu, niềm vui và sự gắn kết giữa con người với nhau.
        </p>
        <p>
            Từ một cửa hàng nhỏ với niềm đam mê hoa cỏ, chúng tôi đã phát triển thành một thương hiệu được
            yêu thích bởi sự tận tâm, sáng tạo và chất lượng vượt trội. Mỗi sản phẩm tại <strong>Flower Shop</strong>
            đều được chọn lựa kỹ lưỡng, tỉ mỉ trong từng chi tiết – từ sắc hoa, kiểu dáng đến thông điệp gửi gắm.
        </p>
    </div>

    <div class="p-5 bg-white rounded-4 shadow-sm info-section mb-5">
        <h3 class="fw-bold text-pink mb-3">🌷 Sứ mệnh & Giá trị cốt lõi</h3>
        <p>
            Chúng tôi không chỉ bán hoa – chúng tôi mang đến cảm xúc và trải nghiệm.
            Mỗi bó hoa được gói ghém bằng cả trái tim, thể hiện niềm đam mê trong nghệ thuật hoa tươi.
        </p>
        <ul class="list-unstyled ms-3">
            <li>🌸 Mang đến <strong>niềm vui</strong> và <strong>sự kết nối</strong> giữa con người.</li>
            <li>🌼 Đặt <strong>chất lượng và sự tươi mới</strong> làm ưu tiên hàng đầu.</li>
            <li>🌺 Luôn <strong>sáng tạo</strong> trong phong cách cắm hoa và thiết kế.</li>
            <li>🌹 Góp phần <strong>lan tỏa năng lượng tích cực</strong> trong cuộc sống.</li>
        </ul>
    </div>

    <div class="p-5 bg-white rounded-4 shadow-sm info-section mb-5">
        <h3 class="fw-bold text-pink mb-3">🌻 Dịch vụ của chúng tôi</h3>
        <p>
            Tại <strong>Flower Shop</strong>, bạn sẽ dễ dàng tìm thấy hoa phù hợp cho mọi dịp – từ những
            khoảnh khắc nhỏ bé đời thường đến những sự kiện trọng đại:
        </p>
        <ul class="list-unstyled ms-3">
            <li>🎂 <strong>Hoa sinh nhật</strong> – gửi lời chúc ngọt ngào và tràn đầy yêu thương.</li>
            <li>💍 <strong>Hoa cưới</strong> – điểm nhấn tinh tế cho ngày trọng đại của đôi uyên ương.</li>
            <li>🎓 <strong>Hoa tốt nghiệp</strong> – tôn vinh thành công và nỗ lực của mỗi người.</li>
            <li>🎁 <strong>Hoa sự kiện & trang trí</strong> – làm đẹp không gian và ghi dấu ấn riêng biệt.</li>
        </ul>
    </div>

    <div class="p-5 bg-white rounded-4 shadow-sm text-center info-section">
        <h3 class="fw-bold text-pink mb-3">📞 Liên hệ với chúng tôi</h3>
        <p class="mb-1">📍 140 Lê Trọng Tấn, Quận Tân Phú, TP.HCM</p>
        <p class="mb-1">📞 0909 123 456</p>
        <p class="mb-4">✉️ support@flowershop.vn</p>
        <a href="{{ route('home') }}" class="btn btn-pink rounded-pill px-4 py-2">🌼 Quay lại Trang chủ</a>
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

    /* 🌸 Hero Banner */
    .about-hero {
        background: var(--pink-gradient);
        position: relative;
        overflow: hidden;
    }

    .about-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: url("{{ asset('assets/images/flower_pattern.png') }}") center/cover no-repeat;
        opacity: 0.08;
    }

    .about-hero h1, .about-hero p {
        position: relative;
        z-index: 1;
    }

    /* 🌸 Content */
    .info-section {
        transition: all 0.3s ease;
    }

    .info-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(255, 107, 156, 0.15);
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
        box-shadow: 0 8px 20px rgba(255, 107, 156, 0.4);
    }

    /* 🌸 Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in { animation: fadeIn 1s ease forwards; }
    .animate-fade-in-delay {
        opacity: 0;
        animation: fadeIn 1.2s ease forwards;
        animation-delay: 0.3s;
    }
</style>
@endsection
