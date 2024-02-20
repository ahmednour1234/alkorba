<?php

// app/Http/Controllers/Api/CoffeeController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coffee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class CoffeeController extends Controller
{
    public function index()
    {
        $coffees = Coffee::all();

        return response()->json(['coffees' => $coffees]);
    }

    public function show($id)
    {
        $coffee = Coffee::find($id);

        if (!$coffee) {
            return response()->json(['message' => 'Coffee not found'], 404);
        }

        return response()->json(['coffee' => $coffee]);
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
        $imgPath = $request->file('img')->store('coffee_images', 'public');

        $coffee = Coffee::create([
            'name' => $request->input('name'),
            'cat_id' => $request->input('cat_id'),
            'price' => $request->input('price'),
            'img' => $imgPath,
        ]);

        return response()->json(['coffee' => $coffee, 'message' => 'Coffee created successfully']);
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

        $coffee = Coffee::find($id);

        if (!$coffee) {
            return response()->json(['message' => 'Coffee not found'], 404);
        }

        // Handle image update if provided
        if ($request->hasFile('img')) {
            // Delete the existing image file
            if ($coffee->img) {
                Storage::disk('public')->delete($coffee->img);
            }

            // Upload the new image
            $imgPath = $request->file('img')->store('coffee_images', 'public');
            $coffee->img = $imgPath;
        }

        // Update other fields
        $coffee->name = $request->input('name');
        $coffee->cat_id = $request->input('cat_id');
        $coffee->price = $request->input('price');
        $coffee->save();

        return response()->json(['coffee' => $coffee, 'message' => 'Coffee updated successfully']);
    }

    public function destroy($id)
    {
        $coffee = Coffee::find($id);

        if (!$coffee) {
            return response()->json(['message' => 'Coffee not found'], 404);
        }

        // Delete the associated image file
        if ($coffee->img) {
            Storage::disk('public')->delete($coffee->img);
        }

        $coffee->delete();

        return response()->json(['message' => 'Coffee deleted successfully']);
    }
}

