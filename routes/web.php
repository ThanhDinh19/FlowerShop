<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {

    // Route::get('/', [AppController::class, 'index'])->name('app');
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/signup', [AuthController::class, 'showSignUpForm'])->name('signup.form');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');

    Route::post('/logout', function () {
        Auth::logout();

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    })->name('logout');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    // Route::post('/products/{id}/review', [ProductController::class, 'submitReview'])->name('products.review');

    // Các route yêu cầu phải login
    Route::middleware('auth')->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{ProductID}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove/{ProductID}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::get('/orders/my-orders', [OrderController::class, 'myOrders'])->name('orders.myOrders');

    });

    // route hiển thị danh sách từng loại sản phẩm
    Route::get('/category/{id}', [CategoryController::class, 'showByCategory'])->name('category.showByCategory');

    // Route tìm kiếm sản phẩm bằng Ajax
    Route::get('/ajax-search', [ProductController::class, 'ajaxSearch'])->name('product.ajaxSearch');

    // Route tìm kiếm sản phẩm
    Route::get('/search', [ProductController::class, 'search'])->name('product.search');

    // Route thông tin cá nhân người dùng
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // trang thới thiêu
    Route::get('/about', function () {
        return view('index.about');
    })->name('about');

    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

    Route::middleware(['auth'])->group(function () {
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/checkout/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
        Route::get('/invoice/{id}', [OrderController::class, 'invoice'])->name('orders.invoice');

        // Trang QR và xác nhận từ điện thoại
        Route::get('/payment/qr/{id}', [OrderController::class, 'showQr'])->name('orders.qr');
        Route::get('/payment/confirm/{order}', [OrderController::class, 'confirmPayment'])->name('orders.confirmPayment');
        Route::get('/payment/status/{id}', [OrderController::class, 'checkStatus'])->name('orders.checkStatus');
        Route::get('/payment/cancel/{id}', [OrderController::class, 'cancelPayment'])->name('orders.cancel');
    });

    // Route đánh giá một sản phẩm yêu cầu người dùng phải login
    // Các route yêu cầu phải login
    Route::middleware('auth')->group(function () {

        // Route gửi đánh giá
        Route::post('/products/{id}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

        // Route chỉnh sửa đánh giá
        Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');

        // Route xóa đánh giá
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    });

    Route::get('/vnpay/return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');

});

// Route dành cho admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('dashboard');

    // Route quản lý sản phẩm
    Route::resource('products', AdminProductController::class);

    // Route quản lý đơn hàng
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/update-status', [OrderAdminController::class, 'updateStatus'])->name('orders.updateStatus');

    // Route quản lý loại sản phẩm
    Route::resource('categories', AdminCategoryController::class);

    // QUẢN LÝ REVIEWS
    Route::resource('reviews', \App\Http\Controllers\Admin\AdminReviewController::class)
        ->only(['index', 'destroy']);

    // QUẢN LÝ CONTACTS
    Route::resource('contacts', \App\Http\Controllers\Admin\AdminContactController::class)
        ->only(['index', 'show', 'destroy']);

});


