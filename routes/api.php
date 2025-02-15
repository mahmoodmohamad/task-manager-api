<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TaskController;


// Authenticated routes (requires token)
Route::middleware('auth:sanctum')->group(function () {
    // Get the authenticated user
    Route::get('/user', function (Request $request) {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return response()->json($request->user(), 200);
    });

    // List all tasks for the authenticated user (with pagination)
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    // Add a new task for the authenticated user

});

// Login route to generate an API token
Route::post('/login', function (Request $request) {


    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);



    $user = User::where('email', $request->email)->first();


    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Password mismatch'], 401);
    }



    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token], 200);
});
  // Limit to 5 requests per minute for login
