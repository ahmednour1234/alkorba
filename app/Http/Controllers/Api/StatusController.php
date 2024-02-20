<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    public function index()
    {
        return Status::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:statuses|max:255'
        ]);

        $status = Status::create($data);
        return response()->json($status, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:statuses|max:255'
        ]);

        $status = Status::findOrFail($id);
        $status->update($data);
        return response()->json($status, 200);
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();
        return response()->json(null, 204);
    }
}
