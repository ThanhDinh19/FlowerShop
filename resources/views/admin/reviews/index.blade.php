@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')

@section('content')
<h2 class="mb-4">📌 Danh sách đánh giá</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Sản phẩm</th>
            <th>Người dùng</th>
            <th>Rating</th>
            <th>Nhận xét</th>
            <th>Ngày</th>
            <th>Hành động</th>
        </tr>
    </thead>

    <tbody>
        @foreach($reviews as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->product->ProductName }}</td>
                <td>{{ $r->user->FirstName ?? '—' }}</td>
                <td>{{ $r->rating }}/5</td>
                <td>{{ Str::limit($r->comment, 50) }}</td>
                <td>{{ $r->created_at->format('d/m/Y') }}</td>

                <td>
                    <form action="{{ route('admin.reviews.destroy', $r->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            Xoá
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-3">
    {{ $reviews->links() }}
</div>

@endsection
