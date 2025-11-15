<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $primaryKey = 'CartItemID';
    protected $fillable = ['UserID', 'ProductID', 'Quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID'); // ✅
    }
}
