<?php

// app/Http/Controllers/Api/DeliveryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery; // Import Delivery model
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function index()
    {
        return Delivery::all(); // Retrieve all deliveries
    }

    public function store(Request $request)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            // Add validation rules for other fields as needed
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // If validation passes, create a new delivery
        $delivery = Delivery::create($request->all());

        // Return the created delivery as a JSON response
        return response()->json($delivery, 201);
    }

    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            // Add validation rules for other fields as needed
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // If validation passes, find the delivery by id
        $delivery = Delivery::findOrFail($id);

        // Update the delivery
        $delivery->update($request->all());

        // Return the updated delivery as a JSON response
        return response()->json($delivery, 200);
    }
    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id); // Find the delivery by id
        $delivery->delete(); // Delete the delivery
        return response()->json(null, 204);
    }

    // Implement other CRUD methods such as show as needed
}
