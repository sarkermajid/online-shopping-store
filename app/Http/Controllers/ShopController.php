<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('id', 'desc')->get();
        $brands = Brand::where('status', 1)->orderBy('id', 'desc')->get();
        $products = Product::where('status', 1)->paginate(9);
        $latestProducts = Product::where('status', 1)->orderBy('id', 'desc')->limit(8)->get();
        $offerProducts = Product::whereNotNull('discount_amount')->get();

        return view('frontend.shop.index', compact(
            'categories',
            'brands',
            'products',
            'offerProducts',
            'latestProducts'
        ));
    }

    public function categoryProduct($slug)
    {
        $categories = Category::where('status', 1)->orderBy('id', 'desc')->get();
        $brands = Brand::where('status', 1)->get();
        $latestProducts = Product::where('status', 1)->orderBy('id', 'desc')->limit(4)->get();
        $category = Category::where('slug', $slug)->first();
        $products = Product::where('category_id', $category->id)
                                ->where('status', 1)
                                ->paginate(9);

        return view('frontend.shop.category-wise-product', compact(
            'categories',
            'products',
            'latestProducts',
            'brands'
        ));
    }

    public function brandProduct($slug)
    {
        $categories = Category::where('status', 1)->orderBy('id', 'desc')->get();
        $brands = Brand::where('status', 1)->get();
        $latestProducts = Product::where('status', 1)->orderBy('id', 'desc')->limit(4)->get();
        $brand = Brand::where('slug', $slug)->first();
        $products = Product::where('brand_id', $brand->id)
                                ->where('status', 1)
                                ->paginate(9);

        return view('frontend.shop.brand-wise-product', compact(
            'categories',
            'products',
            'latestProducts',
            'brands'));
    }

    public function singleProduct($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $product->trending = $product->trending + 1;
        $product->save();
        $relatedProducts = Product::where('category_id', $product->category_id)->get();

        return view('frontend.shop.single-product-view', compact('product', 'relatedProducts'));
    }

    public function priceRangeSearch(Request $request)
    {
        $products = Product::whereBetween('price', [$request->left_value, $request->right_value])->get();

        return view('frontend.shop.price-filter', compact('products'))->render();
    }
}
