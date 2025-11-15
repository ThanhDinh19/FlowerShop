<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'CategoryID';
    public $timestamps = true;

    protected $fillable = [
        'CategoryName'
    ];

    // 1 danh mục có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'CategoryID');
    }
}
