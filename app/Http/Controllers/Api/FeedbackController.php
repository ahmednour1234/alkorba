<?php

// app/Http/Controllers/Api/FeedbackController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();

        return response()->json(['feedbacks' => $feedbacks]);
    }

    public function show($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }

        return response()->json(['feedback' => $feedback]);
    }

    public function store(Request $request)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string',
            'rate' => 'required|numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Create a new feedback with the authenticated user's ID
        $feedback = Feedback::create([
            'product_id' => $request->input('product_id'),
            'user_id' => $userId,
            'message' => $request->input('message'),
            'rate' => $request->input('rate'),
        ]);

        return response()->json(['feedback' => $feedback, 'message' => 'Feedback created successfully']);
    }

    public function update(Request $request, $id)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'product_id' => 'exists:products,id',
            'message' => 'string',
            'rate' => 'numeric|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }

        // Ensure the authenticated user is the owner of the feedback
        if ($feedback->user_id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Update the feedback with the authenticated user's ID
        $feedback->product_id = $request->input('product_id', $feedback->product_id);
        $feedback->message = $request->input('message', $feedback->message);
        $feedback->rate = $request->input('rate', $feedback->rate);
        $feedback->save();

        return response()->json(['feedback' => $feedback, 'message' => 'Feedback updated successfully']);
    }
    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['message' => 'Feedback not found'], 404);
        }

        $feedback->delete();

        return response()->json(['message' => 'Feedback deleted successfully']);
    }
}
