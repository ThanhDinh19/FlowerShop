@extends('layouts.admin')
@section('title', 'Sửa loại sản phẩm')

@section('content')
<div class="container mt-4">
    <h2 class="text-pink fw-bold mb-3">Sửa loại sản phẩm </h2>

    <form action="{{ route('admin.categories.update',$category->CategoryID) }}" method="POST" class="bg-white p-4 rounded-4 shadow-sm">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tên loại sản phẩm</label>
            <input type="text" name="CategoryName" class="form-control elegant-input" required value="{{ old('CategoryName',$category->CategoryName) }}">
            @error('CategoryName')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        
        <div class="text-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4">💾 Lưu</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">↩️ Quay lại</a>
        </div>
    </form>
</div>
@endsection
