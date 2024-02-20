<?php

// app/Http/Controllers/Api/DeliveryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery; // Import Delivery model

class DeliveryController extends Controller
{
    public function index()
    {
        return Delivery::all(); // Retrieve all deliveries
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
            // Add validation for other fields as needed
        ]);

        $delivery = Delivery::create($data); // Create a new delivery
        return response()->json($delivery, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
            // Add validation for other fields as needed
        ]);

        $delivery = Delivery::findOrFail($id); // Find the delivery by id
        $delivery->update($data); // Update the delivery
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
