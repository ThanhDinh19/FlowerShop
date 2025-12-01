@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>📦 Danh sách sản phẩm</h3>
    <a href="{{ route('admin.products.create') }}" class="btn btn-pink text-white" style="background-color:#d63384;">
        <i class="fa-solid fa-plus"></i> Thêm sản phẩm
    </a>
</div>

<form action="{{ route('admin.products.search') }}" method="GET" class="mb-3">
    <div class="row g-2">
        <div class="col-md-8">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm theo tên hoặc mô tả..." value="{{ isset($keyword) ? $keyword : '' }}">
                <button class="btn btn-pink text-white" type="submit" style="background-color:#d63384;">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-arrow-rotate-left"></i> Xóa bộ lọc
            </a>
        </div>
    </div>
    <!-- Hidden inputs để giữ các filter khi tìm kiếm -->
    <input type="hidden" name="sort_price" value="{{ $sortPrice ?? '' }}">
    <input type="hidden" name="sort_stock" value="{{ $sortStock ?? '' }}">
    @if(!empty($selectedCategories))
        @foreach($selectedCategories as $cat)
            <input type="hidden" name="categories[]" value="{{ $cat }}">
        @endforeach
    @endif
</form>

<table class="table table-bordered align-middle shadow-sm">
    <thead class="table-pink" style="background-color:#f9d3e3;">
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>

            {{-- Cột danh mục --}}
            <th style="position: relative;">
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none fw-bold dropdown-toggle p-0" 
                            type="button" 
                            id="dropdownCategoryBtn" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        Danh mục
                    </button>

                    <ul class="dropdown-menu p-3" aria-labelledby="dropdownCategoryBtn" style="max-height: 300px; overflow-y: auto;">
                        <form id="categoryFilterForm" action="{{ route('admin.products.index') }}" method="GET">
                            <input type="hidden" name="sort_price" value="{{ $sortPrice }}">
                            <input type="hidden" name="sort_stock" value="{{ $sortStock }}">

                            @foreach ($categories as $cat)
                                <li>
                                    <label class="form-check-label d-flex align-items-center">
                                        <input 
                                            class="form-check-input me-2" 
                                            type="checkbox" 
                                            name="categories[]" 
                                            value="{{ $cat->CategoryID }}" 
                                            onchange="document.getElementById('categoryFilterForm').submit();" 
                                            {{ in_array($cat->CategoryID, $selectedCategories) || empty($selectedCategories) ? 'checked' : '' }}>
                                        {{ $cat->CategoryName }}
                                    </label>
                                </li>
                            @endforeach
                        </form>
                    </ul>
                </div>
            </th>

            @php
                // xác định trạng thái sắp xếp kế tiếp
                $nextSortPrice = $sortPrice === 'asc' ? 'desc' : ($sortPrice === 'desc' ? 'default' : 'asc');
                $nextSortStock = $sortStock === 'asc' ? 'desc' : ($sortStock === 'desc' ? 'default' : 'asc');
            @endphp

            {{-- Cột giá --}}
            <th style="cursor: pointer; width: 120px;">
                <a href="{{ route('admin.products.index', [
                    'sort_price' => $nextSortPrice,
                    'sort_stock' => $sortStock, // giữ nguyên trạng thái cột số lượng
                ]) }}" class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <span>Giá</span>
                    @if ($sortPrice === 'asc')
                        <i class="fa-solid fa-arrow-up-wide-short text-success"></i>
                    @elseif ($sortPrice === 'desc')
                        <i class="fa-solid fa-arrow-down-wide-short text-danger"></i>
                    @else
                        <i class="fa-solid fa-arrows-up-down text-muted"></i>
                    @endif
                </a>
            </th>

            {{-- Cột số lượng --}}
            <th style="cursor: pointer; width: 120px;">
                <a href="{{ route('admin.products.index', [
                    'sort_stock' => $nextSortStock,
                    'sort_price' => $sortPrice, // giữ nguyên trạng thái cột giá
                ]) }}" class="text-decoration-none text-dark d-flex align-items-center justify-content-between">
                    <span>Số lượng</span>
                    @if ($sortStock === 'asc')
                        <i class="fa-solid fa-arrow-up-wide-short text-success"></i>
                    @elseif ($sortStock === 'desc')
                        <i class="fa-solid fa-arrow-down-wide-short text-danger"></i>
                    @else
                        <i class="fa-solid fa-arrows-up-down text-muted"></i>
                    @endif
                </a>
            </th>

            <th>Mô tả</th>
            <th class="text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($products as $p)
            <tr>
                <td>{{ $p->ProductID }}</td>
                <td>
                    @if($p->Image)
                        <img src="{{ asset('assets/product_images/'.$p->Image) }}" alt="" width="70" class="rounded">
                    @else
                        <span class="text-muted fst-italic">Chưa có ảnh</span>
                    @endif
                </td>
                <td>{{ $p->ProductName }}</td>
                <td>{{ $p->category->CategoryName ?? 'Không có' }}</td>
                <td>{{ number_format($p->Price, 0, ',', '.') }} đ</td>
                <td>{{ $p->StockQuantity }}</td>
                <td style="max-width: 250px;">{{ Str::limit($p->Description, 50) }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.products.edit', $p->ProductID) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.products.destroy', $p->ProductID) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fa-solid fa-inbox text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2">
                        @if(isset($keyword) && !empty($keyword))
                            Không tìm thấy sản phẩm với từ khóa "<strong>{{ $keyword }}</strong>"
                        @else
                            Không có sản phẩm nào
                        @endif
                    </p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="pagination-container" style="margin-top: 30px; text-align:center;">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

<style>
th a:hover {
    color: #d63384;
    transition: color 0.2s ease;
}

.dropdown-menu {
    width: 250px;
    background-color: #fffafc;
    border: 1px solid #f2c8dc;
    border-radius: 10px;
}

.dropdown-toggle::after {
    display: none;
}

/* .dropdown:hover .dropdown-menu {
    display: block;
} */

.form-check-label:hover {
    background-color: #f8e8ef;
    border-radius: 5px;
}
</style>


@endsection
