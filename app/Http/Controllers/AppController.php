<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //hiển thị danh sách sản phẩm
    public function index()
    {

        $products = Product::all();            // hoặc ->paginate(12)
        return view('index.home', compact('products'));
    }
}
