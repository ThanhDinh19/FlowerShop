<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('OrderDate', 'desc')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['Status' => 'required|in:pending,shipping,delivered']);

        $order = Order::findOrFail($id);
        $order->Status = $request->Status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}
