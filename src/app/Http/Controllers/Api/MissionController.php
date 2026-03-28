<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    // GET missions
    public function index(Request $request)
    {
        $query = Mission::with(["category", "client"]);

        // filter
        if ($request->has("status")) {
            $query->where("status", $request->status);
        }

        if ($request->has("type")) {
            $query->where("type", $request->type);
        }

        if ($request->has("category_id")) {
            $query->where("category_id", $request->category_id);
        }

        if ($request->has("search")) {
            $query->where("title", "like", "%" . $request->search . "%");
        }

        $missions = $query->latest()->paginate(10);

        return response()->json([
            "success" => true,
            "data" => $missions
        ]);
    }

    // POST missions
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== "client") {
            return response()->json([
                "success" => false,
                "message" => "Forbidden"
            ], 403);
        }

        $data = $request->validate([
            "category_id" => "required|exists:categories,id",
            "title" => "required|string|max:255",
            "description" => "required|string",
            "budget" => "nullable|numeric|min:0",
            "duration" => "nullable|string|max:255",
            "type" => "required|in:web,mobile,desktop"
        ]);

        $mission = Mission::create([
            "client_id" => $user->id,
            ...$data
        ]);

        return response()->json([
            "success" => true,
            "message" => "Mission created successfully",
            "data" => $mission
        ], 201);
    }

    // GET /missions
    public function show($id)
    {
        $mission = Mission::with(["category", "client"])->find($id);

        if (!$mission) {
            return response()->json([
                "success" => false,
                "message" => "Mission not found"
            ], 404);
        }

        return response()->json([
            "success" => true,
            "data" => $mission
        ]);
    }

    // PUT /missions/
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $mission = Mission::find($id);

        if (!$mission) {
            return response()->json([
                "success" => false,
                "message" => "Mission not found"
            ], 404);
        }

        if ($user->role !== "client" || $mission->client_id !== $user->id) {
            return response()->json([
                "success" => false,
                "message" => "Forbidden"
            ], 403);
        }

        $data = $request->validate([
            "category_id" => "nullable|exists:categories,id",
            "title" => "nullable|string|max:255",
            "description" => "nullable|string",
            "budget" => "nullable|numeric|min:0",
            "duration" => "nullable|string|max:255",
            "type" => "nullable|in:web,mobile,desktop",
            "status" => "nullable|in:ouverte,en_cours,terminee,annulee"
        ]);

        $mission->update($data);

        return response()->json([
            "success" => true,
            "message" => "Mission updated successfully",
            "data" => $mission
        ]);
    }

    // delete missions
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $mission = Mission::find($id);

        if (!$mission) {
            return response()->json([
                "success" => false,
                "message" => "Mission not found"
            ], 404);
        }

        if ($user->role !== "client" || $mission->client_id !== $user->id) {
            return response()->json([
                "success" => false,
                "message" => "Forbidden"
            ], 403);
        }

        $mission->delete();

        return response()->json([
            "success" => true,
            "message" => "Mission deleted successfully"
        ]);
    }
}
