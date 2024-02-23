<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExtensionController extends Controller
{
    public function index(Request $request)
    {
        $product_id = $request->input('product_id');

        // Get all extensions for a specific product_id
        $extensions = Extension::where('product_id', $product_id)->get();

        return response()->json(['extensions' => $extensions], 200);
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'product_id' => 'required|integer',
        'name' => 'required|string',
        'data' => 'required|array',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $extension = Extension::create([
        'product_id' => $request->input('product_id'),
        'name' => $request->input('name'),
        'data' => $request->input('data'),
    ]);

    return response()->json(['extension' => $extension], 201);
}

public function update(Request $request, $product_id, $extension_id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'data' => 'required|array',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    $extension = Extension::where('product_id', $product_id)
        ->where('id', $extension_id)
        ->first();

    if (!$extension) {
        return response()->json(['message' => 'Extension not found'], 404);
    }

    $extension->update([
        'name' => $request->input('name'),
        'data' => $request->input('data'),
    ]);

    return response()->json(['extension' => $extension], 200);
}

    public function destroy($product_id, $extension_id)
    {
        $extension = Extension::where('product_id', $product_id)
            ->where('id', $extension_id)
            ->first();

        if (!$extension) {
            return response()->json(['message' => 'Extension not found'], 404);
        }

        $extension->delete();

        return response()->json(['message' => 'Extension deleted successfully'], 200);
    }
}
