@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="profile-container">
    <h2>Thông tin cá nhân</h2>

    {{-- @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif --}}

    <div class="profile-card">
        <img src="{{ $user->avatar ?? asset('assets/images/default_avatar.png') }}" alt="Avatar" class="profile-avatar">

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            <label>Họ</label>
            <input type="text" name="FirstName" value="{{ old('FirstName', $user->FirstName) }}" required>

            <label>Tên</label>
            <input type="text" name="LastName" value="{{ old('LastName', $user->LastName) }}" required>

            <label>Email</label>
            <input type="email" value="{{ $user->Email }}" disabled>

            <label>Số điện thoại</label>
            <input type="text" name="PhoneNumber" value="{{ old('PhoneNumber', $user->PhoneNumber) }}">

            <label>Địa chỉ</label>
            <textarea name="Address">{{ old('Address', $user->Address) }}</textarea>

            <button type="submit" class="btn_submit_profile">💾 Lưu thay đổi</button>
        </form>
    </div>
</div>

<style>
.profile-container {
    max-width: 600px;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
}

.profile-container h2 {
    text-align: center;
    color: #444;
    margin-bottom: 20px;
}

.profile-avatar {
    display: block;
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    border-radius: 50%;
    border: 2px solid #eee;
}

form label {
    font-weight: 600;
    display: block;
    margin-top: 10px;
}

form input, form textarea {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-top: 5px;
}

.btn_submit_profile {
    background-color: #f47fb4;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    margin-top: 15px;
    cursor: pointer;
    width: 100%;
}

button :hover {
    background-color: #e35a99;
}

.alert.success {
    background-color: #d4edda;
    padding: 10px;
    color: #155724;
    border-radius: 5px;
    text-align: center;
}
</style>
@endsection
