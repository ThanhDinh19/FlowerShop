<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 📌 Tìm kiếm sản phẩm
    public function search(Request $request)
    {
        $query = trim($request->input('query'));
        if (! $query) {
            return redirect()->route('home')->with('info', 'Vui lòng nhập từ khoá tìm kiếm.');
        }

        $products = Product::where('ProductName', 'LIKE', '%' . $query . '%')
            ->paginate(20);

        return view('products.search_results', compact('products', 'query'));
    }

    // 📌 Tìm kiếm sản phẩm (Ajax)
    public function ajaxSearch(Request $request)
    {
        $query = $request->get('query', '');

        // Nếu trống thì trả về mảng rỗng
        if (! $query) {
            return response()->json([]);
        }

        $products = Product::where('ProductName', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['ProductID', 'ProductName', 'Price', 'Image']);

        return response()->json($products);
    }

    // 📌 Hiển thị danh sách sản phẩm (có phân trang)
    public function index()
    {
        // Lấy danh sách sản phẩm, mỗi trang 20 sản phẩm
        $products = Product::paginate(20);

        // Trả dữ liệu sang view
        return view('products.index', compact('products'));
    }

    // 📌 Hiển thị chi tiết 1 sản phẩm
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $relatedProducts = collect();
        if ($product->CategoryID) {
            $relatedProducts = Product::where('CategoryID', $product->CategoryID)
                ->where('ProductID', '!=', $product->ProductID)
                ->limit(6)
                ->get();
        }

        $reviews = $product->reviews()->with('user')->latest()->get();
        $averageRating = $reviews->count() ? round($reviews->avg('rating'), 1) : null;

        $hasPurchased = false;
        $hasReviewed = false;
        if (Auth::check()) {
            $user = Auth::user();

            $hasPurchased = OrderItem::where('ProductID', $product->ProductID)
                ->whereHas('order', function ($q) use ($user) {
                    $q->where('UserID', $user->UserID)
                        ->where('Status', 'delivered');
                })
                ->exists();

            $hasReviewed = Review::where('product_id', $product->ProductID)
                ->where('user_id', $user->UserID)
                ->exists();
        }

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'reviews',
            'averageRating',
            'hasPurchased',
            'hasReviewed'
        ));
    }
}
