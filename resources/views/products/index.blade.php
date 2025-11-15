@extends('layouts.app')

@section('content')
<h2>Danh sách sản phẩm</h2>
<div class="grid">
    @foreach ($products as $product)
        <div class="product-item">
            <a href="{{ route('products.show', $product->ProductID) }}">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <h3>{{ $product->name }}</h3>
                <p>{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
            </a>
        </div>
    @endforeach
</div>

<!-- Phân trang -->
<div class="pagination">
    {{ $products->links() }}
</div>
@endsection
