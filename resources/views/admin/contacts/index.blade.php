@extends('layouts.admin')

@section('title', 'Quản lý liên hệ')

@section('content')
<h2 class="mb-4">📩 Danh sách liên hệ</h2>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Chủ đề</th>
            <th>Ngày gửi</th>
            <th>Chi tiết</th>
            <th>Xoá</th>
        </tr>
    </thead>

    <tbody>
        @foreach($contacts as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->name }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->subject ?? '(Không có)' }}</td>
                <td>{{ $c->created_at->format('d/m/Y') }}</td>

                <td>
                    <a href="{{ route('admin.contacts.show', $c->id) }}" class="btn btn-sm btn-primary">
                        Xem
                    </a>
                </td>

                <td>
                    <form action="{{ route('admin.contacts.destroy', $c->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Xoá</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $contacts->links() }}

@endsection
