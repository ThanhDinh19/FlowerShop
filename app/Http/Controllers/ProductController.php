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


    // Gửi đánh giá — chỉ cho người đã mua sản phẩm
    // public function submitReview(Request $request, $id)
    // {
    //     $request->validate([
    //         'rating' => 'required|integer|min:1|max:5',
    //         'comment' => 'nullable|string|max:2000',
    //     ]);

    //     $product = Product::findOrFail($id);

    //     // Kiểm tra user đã mua chưa
    //     $user = auth()->user();
    //     if (! $user) {
    //         return redirect()->route('login.form')->with('info', 'Vui lòng đăng nhập để đánh giá.');
    //     }

    //     $userId = auth()->id();

    //     $bought = Order::where('UserID', $userId)
    //         ->whereHas('items', function ($q) use ($product) {
    //             $q->where('ProductID', $product->ProductID);
    //         })->exists();

    //     if (! $bought) {
    //         return back()->with('error', 'Chỉ khách hàng đã mua mới có thể đánh giá sản phẩm này.');
    //     }

    //     // Tạo hoặc cập nhật đánh giá của người dùng cho sản phẩm (1 user 1 review)
    //     Review::updateOrCreate(
    //         ['product_id' => $product->ProductID, 'user_id' => $userId],
    //         ['rating' => $request->rating, 'comment' => $request->comment]
    //     );

    //     return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá!');
    // }

    // // 📌 (Tuỳ chọn) Thêm sản phẩm mới
    // public function create()
    // {
    //     return view('products.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name'        => 'required|string|max:255',
    //         'price'       => 'required|numeric|min:0',
    //         'description' => 'nullable|string',
    //         'image'       => 'nullable|image|max:2048',
    //     ]);

    //     $product = new Product();
    //     $product->name = $request->name;
    //     $product->price = $request->price;
    //     $product->description = $request->description;

    //     // Lưu ảnh (nếu có)
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $path = $file->store('uploads/products', 'public');
    //         $product->image = $path;
    //     }

    //     $product->save();

    //     return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công!');
    // }

    // // 📌 (Tuỳ chọn) Sửa sản phẩm
    // public function edit($id)
    // {
    //     $product = Product::findOrFail($id);
    //     return view('products.edit', compact('product'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $product = Product::findOrFail($id);

    //     $request->validate([
    //         'name'        => 'required|string|max:255',
    //         'price'       => 'required|numeric|min:0',
    //         'description' => 'nullable|string',
    //         'image'       => 'nullable|image|max:2048',
    //     ]);

    //     $product->update($request->except('image'));

    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $path = $file->store('uploads/products', 'public');
    //         $product->image = $path;
    //         $product->save();
    //     }

    //     return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    // }

    // // 📌 (Tuỳ chọn) Xóa sản phẩm
    // public function destroy($id)
    // {
    //     $product = Product::findOrFail($id);
    //     $product->delete();

    //     return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    // }
}
