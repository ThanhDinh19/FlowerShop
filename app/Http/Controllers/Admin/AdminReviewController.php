<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'product'])
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Xoá đánh giá thành công!');
    }
}
