<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = \App\Models\User::where('email',$request->email)->first();
        if(!$user){
            throw ValidationException::withMessage([
                'email' => ['The provided Credentials in uncorrect']
            ]);
        }
        if(!Hash::check($request->password,$user->password)){
            throw ValidationException::withMessage([
                'email' => ['The provided Credentials in uncorrect']
            ]);
        }
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'token' => $token
        ]);

    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
           'message' => 'logged out successfully'
        ]);
    }
}
