<?php

use App\Http\Controllers\LotsController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

// I am using some builtin Laravel auth solution for API
//  but what does it do on the background is actually:
//  * creating access tokens table
//  * storing them alongside user id
//  * to create token they probably use some crypto-secure bytes,
//      then hash it using sha256 (because token is essentially a password but quite random)
//  * the middleware extract the token from Authorization header and checks it using database
//  the rest is implemented down there (i.e. checking the user and returning the token)

Route::post('/auth/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Incorrect email or password.'],
        ]);
    }

    return $user->createToken('token-name')->plainTextToken;
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/lots/{lot?}', [LotsController::class, 'listLots']);
    Route::post('/lots/{lot?}', [LotsController::class, 'createLot']);
    Route::put('/lots/{lot}', [LotsController::class, 'updateLot']);
    Route::patch('/lots/{lot?}', [LotsController::class, 'moveContainedLots']);
    Route::delete('/lots/{lot}', [LotsController::class, 'deleteLot']);
});
