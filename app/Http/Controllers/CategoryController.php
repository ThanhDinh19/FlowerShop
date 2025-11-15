<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function showByCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Lấy tất cả sản phẩm thuộc danh mục này theo phân trang
        $query = Product::where('CategoryID', $id);

        // Hỗ trợ sắp xếp theo giá: price_asc hoặc price_desc
        $sort = $request->get('sort');
        if ($sort === 'price_asc') {
            $query->orderBy('Price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('Price', 'desc');
        } else {
            // Mặc định: theo id giảm dần (mới nhất trước)
            $query->orderBy('ProductID', 'desc');
        }

        $products = $query->paginate(12)->appends($request->only('sort'));

        return view('category.showByCategory', compact('category', 'products', 'sort'));
    }
}
