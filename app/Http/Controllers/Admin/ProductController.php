<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use InertiaToast\Facades\Toast;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return Inertia::render("admin/products/index", compact("products"));
    }

    public function create()
    {
        return Inertia::render("admin/products/create");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('photos', 'public');
        }

        Product::create($data);

        Toast::success('Product added successfuly!');

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        return Inertia::render('admin/products/edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'sometimes|image|max:2048',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            // Store new image
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }


        $product->update($data);

        Toast::success('Product updated successfully!');
        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        Toast::success('Product deleted successfully!');
        return redirect()->route('admin.products.index');
    }
}
