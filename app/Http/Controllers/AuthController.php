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

        $token = $user->createToken('authToken', ['client'])->plainTextToken;

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

    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json(['user_token' => $token]);
}
}