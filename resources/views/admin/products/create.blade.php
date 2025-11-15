@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    {{-- quay lại danh sách sản phẩm --}}
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
    </a>
    <h3 class="mb-4">➕ Thêm sản phẩm mới</h3>
</div>
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf
    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="ProductName" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="CategoryID" class="form-select" required>
            <option value="">-- Chọn danh mục --</option>
            @foreach ($categories as $c)
                <option value="{{ $c->CategoryID }}">{{ $c->CategoryName }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="Price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Số lượng</label>
        <input type="number" name="StockQuantity" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="Description" class="form-control" rows="4"></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh sản phẩm</label>
        <input type="file" name="Image" class="form-control">
    </div>

    <button type="submit" class="btn text-white" style="background-color:#d63384;">Lưu sản phẩm</button>
</form>
@endsection
