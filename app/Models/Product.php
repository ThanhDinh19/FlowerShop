<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'ProductID';
    public $timestamps = true;

    protected $fillable = [
        'CategoryID',
        'ProductName',
        'Description',
        'Price',
        'StockQuantity',
        'Image'
    ];

    // 1 sản phẩm thuộc về 1 danh mục
    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID');
    }

    // 1 sản phẩm có thể nằm trong nhiều giỏ hàng
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'ProductID');
    }

    // Reviews left by customers
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
}
