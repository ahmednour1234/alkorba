<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AddressController extends Controller
{
    public function index()
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Retrieve addresses belonging to the authenticated user
        $addresses = Address::where('user_id', $userId)->get();

        // Return the addresses as a JSON response
        return response()->json(['addresses' => $addresses]);
    }
    public function store(Request $request)
    {
        // Assign the authenticated user's ID to the user_id field
        $request->merge(['user_id' => Auth::id()]);

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create address
        $address = Address::create($request->all());

        // Return success response
        return response()->json(['address' => $address, 'message' => 'Address created successfully']);
    }

    public function update(Request $request, $id)
    {
        // Assign the authenticated user's ID to the user_id field
        $request->merge(['user_id' => Auth::id()]);

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Find address by ID
        $address = Address::findOrFail($id);

        // Update address
        $address->update($request->all());

        // Return success response
        return response()->json(['address' => $address, 'message' => 'Address updated successfully']);
    }
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'Address deleted successfully']);
    }
}
