<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // -------------------------
    // Gửi đánh giá
    // -------------------------
    public function store(Request $request, $productId)
    {
        $user = Auth::user();

        // Validate
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 1) Kiểm tra sản phẩm có tồn tại
        $product = Product::findOrFail($productId);

        // 2) Kiểm tra user đã mua sản phẩm này chưa
        $hasPurchased = OrderItem::where('ProductID', $productId)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('UserID', $user->UserID)
                    ->where('Status', 'delivered'); // chỉ đánh giá khi đơn hàng đã thanh toán
            })
            ->exists();

        if (! $hasPurchased) {
            return back()->withErrors([
                'review_error' => 'Bạn chỉ có thể đánh giá khi đã mua sản phẩm này trước đó.',
            ]);
        }

        // 3) Kiểm tra đã review rồi chưa
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', $user->UserID)
            ->first();

        if ($existingReview) {
            return back()->withErrors([
                'review_error' => 'Bạn đã đánh giá sản phẩm này rồi!',
            ]);
        }

        // 4) Lưu review
        Review::create([
            'product_id' => $productId,
            'user_id' => $user->UserID,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm ❤️');
    }

    // -------------------------
    // Cập nhật đánh giá
    // -------------------------
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['success' => true]);
    }

    // -------------------------
    // Xóa đánh giá
    // -------------------------
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id != Auth::id()) {
            abort(403, 'Không được phép xoá đánh giá này');
        }

        $review->delete();

        return back()->with('success', 'Xóa đánh giá thành công!');
    }
}
