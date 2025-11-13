<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Bargain;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('admin.product.index', compact('categories', 'brands'));
    }

    public function store(ProductStoreRequest $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name) . '-' . rand(1000, 5000);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->qty = $request->qty;
        $product->price = $request->price;
        $product->min_price = $request->min_price;
        $product->weight = $request->weight;
        $product->discount_amount = $request->discount_amount;
        $product->youtube_video_link = $request->youtube_video_link;
        $product->status = $request->status;
        // Image upload
        $image = $request->file('image');
        $image_name = $product->slug . time() . '.' . $image->getClientOriginalExtension();
        $image->move('admin/product-image/', $image_name);
        $product->image = $image_name;
        $product->save();

        return redirect()->route('product.manage')->with('message', 'Product created successfully');
    }

    public function manage()
    {
        $products = Product::orderBy('id', 'desc')->get();

        return view('admin.product.manage', compact('products'));
    }

    public function view($id)
    {
        $product = Product::find($id);

        return view('admin.product.view', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::where('status', 1)->get();
        $brands = Brand::where('status', 1)->get();

        return view('admin.product.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name) . '-' . rand(1000, 5000);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->qty = $request->qty;
        $product->price = $request->price;
        $product->min_price = $request->min_price;
        $product->weight = $request->weight;
        $product->discount_amount = $request->discount_amount;
        $product->youtube_video_link = $request->youtube_video_link;
        $product->status = $request->status;
        // Image upload
        $image = $request->file('image');
        if ($image) {
            if (file_exists($product->image)) {
                unlink($product->image);
            }
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('admin/product-image/', $image_name);
            $product->image = $image_name;
        }
        $product->update();

        return redirect()->route('product.manage')->with('message', 'Product Updated Successfully');
    }

    public function status(Request $request)
    {
        $product = Product::find($request->product_id);
        if ($product->status == 1) {
            $product->status = 0;
        } else {
            $product->status = 1;
        }
        $product->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->product_id);
        $product->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function bargain(Request $request, $productId)
    {
        $request->validate([
            'offered_price' => 'required|numeric|min:1',
        ]);

        $product = Product::findOrFail($productId);
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['message' => 'Please log in to make a bargain.'], 401);
        }

        $offerCount = Bargain::where('user_id', $userId)
            ->where('product_id', $productId)
            ->count();

        if ($offerCount >= 3) {
            return response()->json(['message' => 'You already used all 3 bargain chances.'], 403);
        }

        $status = (float) $request->offered_price >= (float) $product->min_price ? 1 : 0;


        $bargain = Bargain::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'offered_price' => $request->offered_price,
            'status' => $status,
        ]);

        $remaining = 3 - ($offerCount + 1);

        return response()->json([
            'status' => $status,
            'message' => $status === 1
                ? '🎉 Offer accepted! You can now buy this product at your offered price.'
                : '❌ Offer rejected! Try again (you have ' . $remaining . ' tries left).',
        ]);
    }

    public function search(Request $request)
    {
        $name = $request->query('name');

        $products = Product::query();

        if ($name) {
            $name = strtolower($name);

            $products->where(function ($q) use ($name) {
                $q->where('name', 'LIKE', "%{$name}%")
                    ->orWhere('description', 'LIKE', "%{$name}%")
                    ->orWhere('short_description', 'LIKE', "%{$name}%");
            });
        }

        $products = $products->get(['id', 'name', 'price', 'qty', 'description', 'short_description']);

        return response()->json($products);
    }
}
