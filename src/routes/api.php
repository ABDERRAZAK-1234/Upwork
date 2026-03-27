<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;

// profile routes
Route::middleware("auth:sanctum")->group(function () {

    Route::get("/profile/me", [ProfileController::class, "myProfile"]);

    Route::put("/profile/freelance", [ProfileController::class, "updateFreelancerProfile"]);
    Route::put("/profile/client", [ProfileController::class, "updateClientProfile"]);

});
// auth routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/me", [AuthController::class, "me"]); // optional
});
