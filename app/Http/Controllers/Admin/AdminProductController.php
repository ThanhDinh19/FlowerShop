<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortPrice = $request->query('sort_price');   // asc, desc, default
        $sortStock = $request->query('sort_stock');   // asc, desc, default
        $selectedCategories = $request->query('categories', []); // mảng các CategoryID đã chọn

        $query = Product::with('category');

        // ✅ Nếu có bộ lọc theo danh mục
        if (!empty($selectedCategories)) {
            $query->whereIn('CategoryID', $selectedCategories);
        }

        // ✅ Nếu sắp xếp theo giá
        if ($sortPrice === 'asc') {
            $query->orderBy('Price', 'asc');
        } elseif ($sortPrice === 'desc') {
            $query->orderBy('Price', 'desc');
        }

        // ✅ Nếu sắp xếp theo số lượng
        if ($sortStock === 'asc') {
            $query->orderBy('StockQuantity', 'asc');
        } elseif ($sortStock === 'desc') {
            $query->orderBy('StockQuantity', 'desc');
        }

        // ✅ Nếu cả hai đều “default” → mặc định sắp xếp theo ID
        if (! $sortPrice && ! $sortStock) {
            $query->oldest('ProductID');
        }

        $products = $query->paginate(10)->appends([
            'sort_price' => $sortPrice,
            'sort_stock' => $sortStock,
            'categories' => $selectedCategories,
        ]);

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'sortPrice', 'sortStock', 'selectedCategories', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'CategoryID' => 'required|exists:categories,CategoryID',
            'ProductName' => 'required|string|max:150',
            'Description' => 'nullable|string',
            'Price' => 'required|numeric|min:0',
            'StockQuantity' => 'required|integer|min:0',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['ProductName', 'Price', 'Description', 'CategoryID', 'StockQuantity']);

        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('assets/product_images'), $filename);
            $data['Image'] = $filename;
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'ProductName' => 'required|string|max:255',
            'Price' => 'required|numeric|min:0',
            'Description' => 'nullable|string',
            'CategoryID' => 'required|exists:categories,CategoryID',
            'StockQuantity' => 'required|integer|min:0',
            'Image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product->ProductName = $request->ProductName;
        $product->Price = $request->Price;
        $product->Description = $request->Description;
        $product->CategoryID = $request->CategoryID;
        $product->StockQuantity = $request->StockQuantity;

        // ✅ Nếu có upload ảnh mới
        if ($request->hasFile('Image')) {
            $file = $request->file('Image');
            $filename = time().'_'.$file->getClientOriginalName();

            // Xóa ảnh cũ (nếu tồn tại)
            $oldPath = public_path('assets/product_images/'.$product->Image);
            if (! empty($product->Image) && file_exists($oldPath)) {
                unlink($oldPath);
            }

            // Lưu ảnh mới
            $file->move(public_path('assets/product_images'), $filename);
            $product->Image = $filename;
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        // ✅ Xoá ảnh khỏi thư mục public/assets/product_images
        $imagePath = public_path('assets/product_images/'.$product->Image);
        if (! empty($product->Image) && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xoá sản phẩm thành công!');
    }

    // phương thức tìm kiếm sản phẩm
    public function search(Request $request){
        $keyword = $request->input('keyword');
        $sortPrice = $request->query('sort_price');
        $sortStock = $request->query('sort_stock');
        $selectedCategories = $request->query('categories', []);

        $query = Product::with('category');

        // ✅ Tìm kiếm theo từ khóa
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('ProductName', 'LIKE', "%{$keyword}%")
                  ->orWhere('Description', 'LIKE', "%{$keyword}%");
            });
        }

        // ✅ Lọc theo danh mục
        if (!empty($selectedCategories)) {
            $query->whereIn('CategoryID', $selectedCategories);
        }

        // ✅ Sắp xếp theo giá
        if ($sortPrice === 'asc') {
            $query->orderBy('Price', 'asc');
        } elseif ($sortPrice === 'desc') {
            $query->orderBy('Price', 'desc');
        }

        // ✅ Sắp xếp theo số lượng
        if ($sortStock === 'asc') {
            $query->orderBy('StockQuantity', 'asc');
        } elseif ($sortStock === 'desc') {
            $query->orderBy('StockQuantity', 'desc');
        }

        // ✅ Mặc định sắp xếp theo ID nếu không có filter
        if (!$sortPrice && !$sortStock) {
            $query->oldest('ProductID');
        }

        $products = $query->paginate(10)->appends([
            'keyword' => $keyword,
            'sort_price' => $sortPrice,
            'sort_stock' => $sortStock,
            'categories' => $selectedCategories,
        ]);

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'sortPrice', 'sortStock', 'selectedCategories', 'categories', 'keyword'));
    }
}
