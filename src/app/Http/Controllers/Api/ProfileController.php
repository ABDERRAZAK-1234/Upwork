<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClientProfile;
use App\Models\FreelancerProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function myProfile(Request $request)
    {
        $user = $request->user();

        if ($user->role === "freelance") {
            $profile = FreelancerProfile::where("user_id", $user->id)->first();
        } else if ($user->role === "client") {
            $profile = ClientProfile::where("user_id", $user->id)->first();
        } else {
            $profile = null;
        }

        return response()->json([
            "success" => true,
            "data" => [
                "user" => $user,
                "profile" => $profile
            ]
        ]);
    }

    public function updateFreelancerProfile(Request $request)
    {
        $user = $request->user();

        if ($user->role !== "freelance") {
            return response()->json([
                "success" => false,
                "message" => "Forbidden"
            ], 403);
        }

        $data = $request->validate([
            "title" => "nullable|string|max:255",
            "bio" => "nullable|string",
            "daily_rate" => "nullable|numeric|min:0",
            "experience_years" => "nullable|integer|min:0",
            "portfolio_url" => "nullable|url",
            "is_available" => "nullable|boolean"
        ]);

        $profile = FreelancerProfile::updateOrCreate(
            ["user_id" => $user->id],
            $data
        );

        return response()->json([
            "success" => true,
            "message" => "Freelancer profile updated successfully",
            "data" => $profile
        ]);
    }

    public function updateClientProfile(Request $request)
    {
        $user = $request->user();

        if ($user->role !== "client") {
            return response()->json([
                "success" => false,
                "message" => "Forbidden"
            ], 403);
        }

        $data = $request->validate([
            "company_name" => "nullable|string|max:255",
            "description" => "nullable|string"
        ]);

        $profile = ClientProfile::updateOrCreate(
            ["user_id" => $user->id],
            $data
        );

        return response()->json([
            "success" => true,
            "message" => "Client profile updated successfully",
            "data" => $profile
        ]);
    }
}
