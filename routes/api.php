<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function () {
    // Get the authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user() ?: response()->json(['message' => 'Unauthenticated'], 401);
    });

    // List all tasks for the authenticated user
    Route::get('/tasks', [TaskController::class, 'index']);

    // Add a new task for the authenticated user
    Route::post('/tasks', [TaskController::class, 'store']);
});

// Login route to generate an API token
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token], 200);
});
