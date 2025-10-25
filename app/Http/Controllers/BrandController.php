<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandStoreRequest;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.brand.index');
    }

    public function store(BrandStoreRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name).'-'.rand(1000, 5000);
        $brand->description = strip_tags(html_entity_decode($request->description));
        $brand->status = $request->status;
        // image upload
        $image = $request->file('image');
        $image_name = $brand->slug.time().'.'.$image->getClientOriginalExtension();
        $image->move('admin/brand-image/', $image_name);
        $brand->image = $image_name;
        $brand->save();

        return redirect()->route('brand.manage')->with('message', 'Brand created successfully');
    }

    public function manage()
    {
        $brands = Brand::orderBy('id', 'desc')->get();

        return view('admin.brand.manage', compact('brands'));
    }

    public function view($id)
    {
        $brand = Brand::find($id);

        return view('admin.brand.view', compact('brand'));
    }

    public function status(Request $request)
    {
        $brand = Brand::find($request->brand_id);
        if ($brand->status == 1) {
            $brand->status = 0;
        } else {
            $brand->status = 1;
        }
        $brand->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function edit($id)
    {
        $brand = Brand::find($id);

        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name).'-'.rand(1000, 5000);
        $brand->description = strip_tags(html_entity_decode($request->description));
        if ($request->status == 'on') {
            $brand->status = 1;
        } else {
            $brand->status = 0;
        }
        // Image upload
        $image = $request->file('image');
        if ($image) {
            if (file_exists($brand->image)) {
                unlink($brand->image);
            }
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $image->move('admin/brand-image/', $image_name);
            $brand->image = $image_name;
        }
        $brand->update();

        return redirect()->route('brand.manage')->with('message', 'Brand updated successfully');
    }

    public function delete(Request $request)
    {
        $brand = Brand::find($request->brand_id);
        $products = Product::all();
        foreach ($products as $product) {
            if ($brand->id == $product->brand_id) {
                return response()->json([
                    'status' => 'error',
                ]);
            }
        }
        $brand->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
