@extends('layouts.admin')
@section('title', 'Quản lý loại sản phẩm')

@section('content')
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Danh sách loại sản phẩm</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-pink text-white" style="background-color:#d63384;">
            <i class="fa-solid fa-plus"></i> Thêm loại mới
        </a>
    </div>
   
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên loại</th>
                       
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->CategoryID }}</td>
                            <td>{{ $cat->CategoryName }}</td>
                           
                            <td class="text-end">
                                <a href="{{ route('admin.categories.edit', $cat->CategoryID) }}" class="btn btn-sm btn-outline-info rounded-pill">✏️ Sửa</a>
                                <form action="{{ route('admin.categories.destroy', $cat->CategoryID) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill"
                                        onclick="return confirm('Bạn có chắc muốn xóa loại này không? ')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
