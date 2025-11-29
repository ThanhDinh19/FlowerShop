<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class OrderController extends Controller
{
    // Hiển thị trang xác nhận thanh toán
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = CartItem::with('product')->where('UserID', $user->UserID)->get();
        $total = $cartItems->sum(fn($item) => $item->product->Price * $item->Quantity);

        return view('orders.checkout', compact('user', 'cartItems', 'total'));
    }

    // Xử lý xác nhận thanh toán
    public function confirm(Request $request)
    {
        $user = Auth::user();
        // --- Lưu thông tin người dùng sửa lại trước khi tạo đơn ---
        $user->update([
            'PhoneNumber' => $request->PhoneNumber,
            'Address'     => $request->Address,
        ]);
        $cartItems = CartItem::with('product')->where('UserID', $user->UserID)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['error' => 'Giỏ hàng trống, không thể thanh toán.']);
        }

        $total = $cartItems->sum(fn($item) => $item->product->Price * $item->Quantity);

        $request->validate([
            'DeliveryDateTime' => 'required|date|after_or_equal:today',
            'RecipientAddress' => 'nullable|string|max:255',
            'MessageToRecipient' => 'nullable|string|max:500',
            'PaymentMethod' => 'required|in:cash,vnpay',
        ]);

        $deliveryTime = \Carbon\Carbon::parse($request->DeliveryDateTime, 'Asia/Ho_Chi_Minh');

        if (now('Asia/Ho_Chi_Minh')->diffInHours($deliveryTime, false) < 2) {
            return back()->withErrors(['DeliveryDateTime' => 'Thời gian giao hàng phải cách hiện tại ít nhất 2 giờ.'])->withInput();
        }

        // ----------------------------------------
        // CASE 1: THANH TOÁN VNPAY → KHÔNG TẠO ORDER
        // ----------------------------------------
        if ($request->PaymentMethod === 'vnpay') {

            // Tạo order giả (KHÔNG LƯU DB)
            $order = new Order();
            $order->OrderID = time(); // tạo ID tạm
            $order->TotalAmount = $total;

            // Lưu ORDERID & thông tin vào session để callback dùng
            session([
                'checkout_temp' => [
                    'order_id' => $order->OrderID,
                    'user_id' => $user->UserID,
                    'delivery_time' => $deliveryTime,
                    'address' => $request->RecipientAddress ?? $user->Address,
                    'message' => $request->MessageToRecipient,
                    'total' => $total
                ]
            ]);

            // Dùng được createVnpayUrl($order) mà KHÔNG lưu DB
            $paymentUrl = $this->createVnpayUrl($order);

            return redirect()->away($paymentUrl);
        }


        // ----------------------------------------
        // CASE 2: THANH TOÁN TIỀN MẶT → TẠO ORDER NGAY
        // ----------------------------------------

        $order = Order::create([
            'UserID' => $user->UserID,
            'OrderDate' => now('Asia/Ho_Chi_Minh'),
            'DeliveryDateTime' => $deliveryTime,
            'RecipientAddress' => $request->RecipientAddress ?? $user->Address,
            'MessageToRecipient' => $request->MessageToRecipient,
            'PaymentMethod' => 'cash',
            'TotalAmount' => $total,
            'Status' => 'pending',
            'PaymentConfirmed' => false,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'OrderID' => $order->OrderID,
                'ProductID' => $item->ProductID,
                'Quantity' => $item->Quantity,
                'UnitPrice' => $item->product->Price,
                'TotalPrice' => $item->product->Price * $item->Quantity,
            ]);
        }

        CartItem::where('UserID', $user->UserID)->delete();

        return redirect()->route('orders.invoice', ['id' => $order->OrderID])
            ->with('success', 'Thanh toán thành công!');
    }


    // Trang hiển thị hóa đơn
    public function invoice($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        return view('orders.invoice', compact('order'));
    }

    // Hiển thị danh sách đơn hàng của người dùng
    public function myOrders()
    {
        $user = Auth::user();
        $orders = Order::with('items.product')
            ->where('UserID', $user->UserID)
            ->orderBy('OrderDate', 'desc')
            ->get();

        return view('orders.my_orders', compact('orders'));
    }

    // Hiển thị  mã qr để thanh toán
    public function showQr($id)
    {
        $order = Order::findOrFail($id);

        // Tạo signed url tạm thời (ví dụ 30 phút)
        $signedUrl = URL::temporarySignedRoute(
            'orders.confirmPayment',
            now()->addMinutes(30),
            ['order' => $order->OrderID]
        );

        // Tạo url QR (sử dụng dịch vụ tạo QR miễn phí)
        $qrData = urlencode($signedUrl);
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={$qrData}";

        return view('orders.qr_payment', compact('order', 'qrImageUrl', 'signedUrl'));
    }

    // Xử lý xác nhận thanh toán từ QR code
    public function confirmPayment(Request $request, $order)
    {
        // Xác thực signed url
        if (! $request->hasValidSignature()) {
            abort(403, 'Liên kết không hợp lệ hoặc hết hạn.');
        }

        $order = Order::findOrFail($order);

        // Đánh dấu đã thanh toán
        if (! $order->PaymentConfirmed) {
            $order->update([
                'PaymentConfirmed' => true,
                'Status' => 'paid',
            ]);
        }

        return view('orders.invoice_bank_transfer', compact('order'))
            ->with('success', '🎉 Thanh toán chuyển khoản thành công! Cảm ơn bạn đã mua hàng 💐');
    }

    //
    public function checkStatus($id)
    {
        $order = Order::findOrFail($id);

        return response()->json([
            'status' => $order->Status,
            'payment_confirmed' => $order->PaymentConfirmed,
        ]);
    }

    // Khi người dùng thoát hoặc hủy thanh toán
    public function cancelPayment($id)
    {
        $order = Order::findOrFail($id);

        // Chỉ cập nhật nếu chưa xác nhận thanh toán
        if (! $order->PaymentConfirmed && $order->Status === 'pending') {
            $order->update([
                'Status' => 'unpaid',
            ]);
        }

        return redirect()->route('home')->with('info', 'Đơn hàng của bạn chưa được thanh toán và đã tạm lưu.');
    }


    // thanh toán vnpay
    private function createVnpayUrl($order)
    {
        $vnp_TmnCode = "F7OIWXUC";
        $vnp_HashSecret = "3HPJ0FZBXRROEKVYUTXGRQVDAZKIJ44K";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = $order->OrderID;
        $vnp_OrderInfo = "Thanh toan don hang " . $order->OrderID;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $order->TotalAmount * 100;
        $vnp_Locale = "vn";
        $vnp_IpAddr = request()->ip();

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        ksort($inputData);

        $query = "";
        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }

            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnp_Url .= "?" . $query . "vnp_SecureHash=" . $vnpSecureHash;

        return $vnp_Url;
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = "3HPJ0FZBXRROEKVYUTXGRQVDAZKIJ44K";

        $inputData = $request->all();
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;
        unset($inputData['vnp_SecureHash']);

        ksort($inputData);

        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $vnp_SecureHash) {
            return redirect()->route('cart.index')
                ->with('error', 'Sai chữ ký bảo mật!');
        }

        if ($request->vnp_ResponseCode == "00") {

            $data = session('checkout_temp');

            if (!$data) {
                return redirect()->route('cart.index')->with('error', 'Không tìm thấy dữ liệu đơn hàng.');
            }

            $user = Auth::user();

            // Tạo đơn hàng sau khi thanh toán thành công ⭐
            $order = Order::create([
                'UserID' => $user->UserID,
                'OrderDate' => now('Asia/Ho_Chi_Minh'),
                'DeliveryDateTime' => $data['delivery_time'],
                'RecipientAddress' => $data['address'],
                'MessageToRecipient' => $data['message'],
                'PaymentMethod' => 'vnpay',
                'TotalAmount' => $data['total'],
                'Status' => 'paid',
                'PaymentConfirmed' => true,
            ]);

            // Tạo chi tiết đơn hàng
            $cartItems = CartItem::with('product')->where('UserID', $user->UserID)->get();
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'OrderID' => $order->OrderID,
                    'ProductID' => $item->ProductID,
                    'Quantity' => $item->Quantity,
                    'UnitPrice' => $item->product->Price,
                    'TotalPrice' => $item->product->Price * $item->Quantity,
                ]);
            }

            // Xóa giỏ hàng
            CartItem::where('UserID', $user->UserID)->delete();

            // Xóa session tạm
            session()->forget('checkout_temp');

            return view("orders.invoice", compact("order"))
                ->with("success", "Thanh toán VNPay thành công!");
        }

        return redirect()->route('cart.index')->with('error', 'Thanh toán thất bại!');
    }
}
