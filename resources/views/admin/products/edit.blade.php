@extends('layouts.admin')
@section('title', 'Chỉnh sửa sản phẩm')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    {{-- quay lại danh sách sản phẩm --}}
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
    </a>
    <h3 class="mb-4">✏️ Chỉnh sửa sản phẩm</h3>
</div>
<form action="{{ route('admin.products.update', $product->ProductID) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="ProductName" class="form-control" value="{{ $product->ProductName }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="CategoryID" class="form-select" required>
            @foreach ($categories as $c)
                <option value="{{ $c->CategoryID }}" {{ $product->CategoryID == $c->CategoryID ? 'selected' : '' }}>
                    {{ $c->CategoryName }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="Price" class="form-control" value="{{ $product->Price }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Số lượng</label>
        <input type="number" name="StockQuantity" class="form-control" min="0" value="{{ $product->StockQuantity }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="Description" class="form-control" rows="4">{{ $product->Description }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh hiện tại</label><br>
        @if($product->Image)
            <img src="{{ asset('assets/product_images/'.$product->Image) }}" width="100" class="rounded mb-2">
        @else
            <span class="text-muted">Chưa có ảnh</span>
        @endif
        <input type="file" name="Image" class="form-control mt-2">
    </div>

    <button type="submit" class="btn text-white" style="background-color:#d63384;">Cập nhật</button>
</form>
@endsection
