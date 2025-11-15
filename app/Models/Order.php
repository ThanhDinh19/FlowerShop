<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderID';
    protected $fillable = ['UserID', 'OrderDate', 'TotalAmount', 'Status', 'DeliveryDateTime', 'RecipientAddress', 'MessageToRecipient', 'PaymentMethod','PaymentConfirmed'];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'OrderID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }
}
