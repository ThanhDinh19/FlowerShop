<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderItemID';

    protected $fillable = ['OrderID', 'ProductID', 'Quantity', 'UnitPrice', 'TotalPrice'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID');
    }
}
