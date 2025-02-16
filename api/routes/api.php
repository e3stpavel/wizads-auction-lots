<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

Route::post('/auth/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    /* @var User $user */
    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Incorrect email or password.']
        ]);
    }

    return $user->createToken('token-name')->plainTextToken;
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
