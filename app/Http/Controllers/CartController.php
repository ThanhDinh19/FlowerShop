<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị giỏ hàng của user hiện tại
    public function index()
    {
        $userId = Auth::id();

        $cartItems = CartItem::with('product')
            ->where('UserID', $userId)
            ->get();

        // ✅ Tính tổng đúng cột Quantity
        $total = $cartItems->sum(function ($item) {
            return $item->product->Price * $item->Quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }


    // Thêm sản phẩm vào giỏ (POST)
    public function add(Request $request, $productId)
    {
        $userId = Auth::id();
        $product = Product::findOrFail($productId);
        // Số lượng gửi lên (mặc định 1)
        $qty = (int) $request->input('quantity', 1);
        if ($qty < 1) $qty = 1;

        $cartItem = CartItem::where('UserID', $userId)
            ->where('ProductID', $product->ProductID)
            ->first();

        if ($cartItem) {
            // Tăng Quantity theo số lượng yêu cầu
            $cartItem->Quantity = ($cartItem->Quantity ?? $cartItem->quantity ?? 0) + $qty;
            $cartItem->save();
        } else {
            CartItem::create([
                'UserID' => $userId,
                'ProductID' => $product->ProductID,
                'Quantity' => $qty,
            ]);
        }

        // Nếu form gửi kèm flag 'checkout' thì chuyển ngay tới trang thanh toán
        if ($request->input('checkout')) {
            return redirect()->route('orders.checkout')->with('success', 'Đã thêm vào giỏ hàng, chuyển tới thanh toán.');
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    // Cập nhật số lượng (POST)
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::with('product')->findOrFail($id);

        if ($cartItem->UserID != Auth::id()) abort(403);

        $cartItem->Quantity = $request->quantity;
        $cartItem->save();

        // Tính lại tiền
        $itemTotal = $cartItem->product->Price * $cartItem->Quantity;
        $cartTotal = CartItem::where('UserID', Auth::id())
            ->with('product')
            ->get()
            ->sum(fn($item) => $item->product->Price * $item->Quantity);

        return response()->json([
            'item_total' => $itemTotal,
            'cart_total' => $cartTotal,
        ]);
    }


    // Xóa item khỏi giỏ (POST)
    public function remove(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        if ($cartItem->UserID != Auth::id()) abort(403);

        $cartItem->delete();
        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    // Xóa toàn bộ giỏ (tuỳ chọn)
    public function clear()
    {
        CartItem::where('UserID', Auth::id())->delete();
        return redirect()->back()->with('success', 'Đã xóa toàn bộ giỏ hàng');
    }
}
