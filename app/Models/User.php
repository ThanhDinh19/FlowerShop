<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'UserID';
    public $timestamps = true; // vì có created_at, updated_at

    protected $fillable = [
        'FirstName',
        'LastName',
        'PhoneNumber',
        'Address',
        'Email',
        'google_id',
        'Password',
        'Role'
    ];

    protected $hidden = ['Password'];

    // Quan hệ: 1 user có nhiều mục trong giỏ hàng
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'UserID');
    }
}
