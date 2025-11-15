@extends('layouts.admin')

@section('title', 'Chi tiết liên hệ')

@section('content')
<h2>📩 Chi tiết liên hệ #{{ $contact->id }}</h2>

<div class="card p-4 mt-3">
    <p><strong>Tên:</strong> {{ $contact->name }}</p>
    <p><strong>Email:</strong> {{ $contact->email }}</p>
    <p><strong>Chủ đề:</strong> {{ $contact->subject ?? '(không có)' }}</p>
    <p><strong>Nội dung:</strong></p>
    <p>{{ $contact->message }}</p>

    <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary mt-3">← Quay lại</a>
</div>
@endsection
