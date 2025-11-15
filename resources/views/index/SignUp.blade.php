@extends('layouts.app')
@section('title','Đăng ký')
@section('content')
<div class="login-box">
    <!-- Logo shop -->
    <img src="https://cdn-icons-png.flaticon.com/512/3081/3081559.png" alt="Shop Logo">
    <h2>Đăng ký tài khoản</h2>

    <form action="{{ route('signup') }}" method="post">
        @csrf
        <div class="input-box">
            <i class="fa fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-box">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Mật khẩu" required>
        </div>

        <div class="input-box">
            <i class="fa fa-user"></i>
            <input type="text" name="first_name" placeholder="Họ" required>
        </div>

        <div class="input-box">
            <i class="fa fa-user"></i>
            <input type="text" name="last_name" placeholder="Tên" required>
        </div>

        <div class="input-box">
            <i class="fa fa-phone"></i>
            <input type="text" name="phone_number" placeholder="Số điện thoại" required>
        </div>

        <div class="input-box">
            <i class="fa fa-map-marker"></i>
            <input type="text" name="address" placeholder="Địa chỉ" required>
        </div>

        <button type="submit" class="btn_signup">Đăng ký</button>
    </form>

    <div class="links">
        <p>Đã có tài khoản? <a href="/login">Đăng nhập ngay</a></p>
        <p><a href="/forgot">Quên mật khẩu?</a></p>
    </div>
</div>



<style>
    /* ==== LOGIN BOX ==== */
    .login-box {
        width: 400px;
        margin: 80px auto;
        background: #fff;
        padding: 40px 35px;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
        animation: fadeUp 0.6s ease;
    }

    .login-box img {
        width: 80px;
        height: 80px;
        margin-bottom: 15px;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.15));
    }

    .login-box h2 {
        font-size: 1.8rem;
        color: #c9184a;
        margin-bottom: 25px;
        font-weight: 700;
    }

    /* ==== INPUT FIELDS ==== */
    .input-box {
        position: relative;
        margin-bottom: 20px;
    }

    .input-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #c9184a;
        font-size: 1rem;
    }

    .input-box input {
        width: 100%;
        padding: 12px 12px 12px 38px;
        border: 2px solid #f3d5df;
        border-radius: 10px;
        font-size: 1rem;
        outline: none;
        transition: all 0.3s ease;
        background-color: #fffafc;
    }

    .input-box input:focus {
        border-color: #c9184a;
        background-color: #fff;
        box-shadow: 0 0 6px rgba(201, 24, 74, 0.25);
    }

    /* ==== BUTTON ==== */
    .btn_signup {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #ffb3c6, #c9184a);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn_signup:hover {
        background: linear-gradient(135deg, #c9184a, #800f2f);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(201, 24, 74, 0.3);
    }

    /* ==== LINKS ==== */
    .links {
        margin-top: 25px;
    }

    .links p {
        margin: 8px 0;
        font-size: 0.95rem;
        color: #555;
    }

    .links a {
        color: #c9184a;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .links a:hover {
        color: #800f2f;
        text-decoration: underline;
    }

    /* ==== ANIMATION ==== */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(25px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ==== RESPONSIVE ==== */
    @media (max-width: 480px) {
        .login-box {
            width: 90%;
            padding: 30px 20px;
        }

        .login-box h2 {
            font-size: 1.5rem;
        }

        .input-box input {
            font-size: 0.95rem;
        }
    }
</style>

@endsection