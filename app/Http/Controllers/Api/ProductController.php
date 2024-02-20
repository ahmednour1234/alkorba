<?php

// app/Http/Controllers/Api/ProductController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json(['products' => $products]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json(['product' => $product]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cat_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Handle image upload
        $imgPath = $request->file('img')->store('product_images', 'public');

        $product = Product::create([
            'name' => $request->input('name'),
            'cat_id' => $request->input('cat_id'),
            'price' => $request->input('price'),
            'img' => $imgPath,
        ]);

        return response()->json(['product' => $product, 'message' => 'Product created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'cat_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Handle image update if provided
        if ($request->hasFile('img')) {
            // Delete the existing image file
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }

            // Upload the new image
            $imgPath = $request->file('img')->store('product_images', 'public');
            $product->img = $imgPath;
        }

        // Update other fields
        $product->name = $request->input('name');
        $product->cat_id = $request->input('cat_id');
        $product->price = $request->input('price');
        $product->save();

        return response()->json(['product' => $product, 'message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the associated image file
        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
    public function search(Request $request)
{
    $search = $request->input('search');

    $products = Product::query()
        ->where('name', 'like', "%$search%")
        ->with('category')
        ->get();

    return response()->json(['products' => $products]);
}
}

