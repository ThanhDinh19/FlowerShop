<?php

namespace App\Http\Controllers;

use App\Models\Product;


class HomeController extends Controller
{
    public function index()
    {
        // Lấy danh sách sản phẩm, mỗi trang 20 sản phẩm
       $products = Product::paginate(20)->onEachSide(1);

        // Truyền dữ liệu sang view
        return view('index.home', compact('products'));
    }
}
