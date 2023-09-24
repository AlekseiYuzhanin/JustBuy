<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
{
    $request->validate([
        'fio' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    $user = new User();
    $user->fio = $request->fio;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    $role = 'admin';

    $token = $user->createToken($role, ['user_id' => $user->id])->plainTextToken;

    return response()->json(['token' => $token]);
}

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['password' => 'Auth failed'], 401);
    }

    $payload = [
        'user_id' => $user->id,
        'role' => $user->role
    ];

    $token = $user->createToken('authToken', ['expires_at' => now()->
    addMinutes(60), 'user_id' => $user->id, 'role' => $user->role])->plainTextToken;
    return response()->json(['user_token' => $token]);
}
}