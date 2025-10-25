<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name).'-'.rand(1000, 5000);
        $category->description = strip_tags(html_entity_decode($request->description));
        $category->status = $request->status;
        // Image upload
        $image = $request->file('image');
        $image_name = $category->slug.time().'.'.$image->getClientOriginalExtension();
        $image->move('admin/category-image/', $image_name);
        $category->image = $image_name;
        $category->save();

        return redirect()->route('category.manage')->with('message', 'category created successfully');
    }

    public function manage()
    {
        return view('admin.category.manage',
            [
                'categories' => Category::orderBy('id', 'desc')->get(),
            ]);
    }

    public function view($id)
    {
        $category = Category::find($id);

        return view('admin.category.view', compact('category'));
    }

    public function status(Request $request)
    {
        $category = Category::find($request->category_id);
        if ($category->status == 1) {
            $category->status = 0;
        } else {
            $category->status = 1;
        }
        $category->save();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function edit($id)
    {
        $category = Category::find($id);

        return view('admin.category.edit', compact('category'));
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::find($id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name).'-'.rand(1000, 5000);
        $category->description = strip_tags(html_entity_decode($request->description));
        if ($request->status == 'on') {
            $category->status = 1;
        } else {
            $category->status = 0;
        }
        // Image upload
        $image = $request->file('image');
        if ($image) {
            if (file_exists($category->image)) {
                unlink($category->image);
            }
            $image_name = time().'.'.$image->getClientOriginalExtension();
            $image->move('admin/category-image/', $image_name);
            $category->image = $image_name;
        }
        $category->update();

        return redirect()->route('category.manage')->with('message', 'category updated successfully');
    }

    public function delete(Request $request)
    {
        $category = Category::find($request->category_id);
        $products = Product::all();
        foreach ($products as $product) {
            if ($category->id == $product->category_id) {
                return response()->json([
                    'status' => 'error',
                ]);
            }
        }
        $category->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
