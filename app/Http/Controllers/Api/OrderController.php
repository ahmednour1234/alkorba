<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Validate the incoming request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'address_id' => 'required|string',
            'phone' => 'required|string',
            'deliver_id' => 'required|string',
            'status_id' => 'required|string',
            'data' => 'required|json',
            'note' => 'nullable|string',
        ]);

        // Create a new order
        $order = Order::create([
            'user_id' => $userId,
            'address_id' => $request->input('address_id'),
            'phone' => $request->input('phone'),
            'note' => $request->input('note'),
            'deliver_id' => $request->input('deliver_id'),
            'status_id' => $request->input('status_id'),
        ]);

        // Create order details
        $orderDetail = $order->details()->create([
            'product_id' => $request->input('product_id'),
            'data' => $request->input('data'),
        ]);

        // Calculate total price for the product_id
        $totalPrice = OrderDetail::where('product_id', $request->input('product_id'))
            ->sum('price'); // Assuming there is a 'price' column in OrderDetail table

        // Update the total_price field in the Order table
        $order->update(['total_price' => $totalPrice]);

        // Return a response
        return response()->json(['message' => 'Order created successfully'], 201);
    }
    public function update(Request $request, $orderId)
{
    // Validate the incoming request data
    $request->validate([
        'address_id' => 'string',
        'delivery_id' => 'string',
        'status_id' => 'string',
        'phone' => 'string',
        'note' => 'nullable|string',
    ]);

    // Find the order by ID
    $order = Order::findOrFail($orderId);

    // Update the order details
    $order->update([
        'address_id' => $request->input('address_id', $order->address),
        'delivery_id' => $request->input('delivery_id', $order->delivery_id),
        'status_id' => $request->input('status_id', $order->status_id),
        'phone' => $request->input('phone', $order->phone),
        'note' => $request->input('note', $order->note),
    ]);

    // Return a response
    return response()->json(['message' => 'Order updated successfully'], 200);
}
public function destroy($orderId)
{
    // Find the order by ID
    $order = Order::findOrFail($orderId);

    // Delete the order and associated details
    $order->delete();

    // Return a response
    return response()->json(['message' => 'Order deleted successfully'], 200);
}
public function index()
{
    // Fetch all orders with their details
    $orders = Order::with('details')->get();

    // Return a response
    return response()->json(['orders' => $orders], 200);
}
public function show($orderId)
{
    // Find the order by ID with its details
    $order = Order::with('details')->findOrFail($orderId);

    // Return a response
    return response()->json(['order' => $order], 200);
}
public function getOrdersByUserId($userId)
{
    // Fetch orders with their details for the specified user_id
    $orders = Order::where('user_id', $userId)->with('details')->get();

    // Return a response
    return response()->json(['orders' => $orders], 200);
}
public function search(Request $request)
{
    $query = Order::query();

    // Search by date range
    if ($request->has('start_date') && $request->has('end_date')) {
        $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
    }

    // Search by price range (assuming 'total_price' field)
    if ($request->has('min_price') && $request->has('max_price')) {
        $query->whereBetween('total_price', [$request->input('min_price'), $request->input('max_price')]);
    }

    // Search by status name
    if ($request->has('status_name')) {
        $query->whereHas('status', function ($subquery) use ($request) {
            $subquery->where('name', $request->input('status_name'));
        });
    }

    // Fetch orders with their details and apply search filters
    $orders = $query->with(['details.product' => function ($query) {
        $query->select('id', 'name');
    }])->get();

    return response()->json(['orders' => $orders], 200);
}

}
