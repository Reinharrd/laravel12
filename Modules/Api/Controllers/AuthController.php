<?php

namespace Modules\Api\Controllers;

use Modules\Api\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = UserModel::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'token' => $user->createToken('api-token')->plainTextToken,
        ]);
    }

    public function login(Request $request)
    {
        $user = UserModel::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token' => $token
        ]);
    }

    public function me(Request $request)
    {
        // return $request->user();
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $data = [
            'nama' => $user->name,
            'email' => $user->email
        ];
        // $encryptData = Str::of(json_encode($data))->encrypt();
        // $decryptData = Str::of(json_encode($data))->decrypt();
        // $encryptData = Crypt::encryptString(json_encode($data));
        return response()->json(['data' => $data]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
