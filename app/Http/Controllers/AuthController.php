<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator($request->all(), [
            "username" => "required|unique:users|max:255",
            "email" => "required|unique:users,email",
            "password" => "required|confirmed"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Request didn't pass validation",
                "data" => $validator->errors()
            ], 400);
        }

        $user = User::create($validator->validated());
        $user->token = $user->createToken("auth-api")->accessToken;
        
        return response()->json([
            "ok" => true,
            "message" => "Register Successfully",
            "data" => $user
        ], 201);
    }


    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            "login" => "required",
            "password" => "required"
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "ok" => false,
                "message" => "Invalid Input",
                "data" => $validator->errors()
            ], 400);
        }
    
        // Check if input is an email or username
        $credentials = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->login, 'password' => $request->password]
            : ['username' => $request->login, 'password' => $request->password];
    
        // Debug: Check if user exists
        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();
    
        if (!$user) {
            return response()->json([
                "ok" => false,
                "message" => "User not found"
            ], 404);
        }
    
        // Debug: Verify password manually
        if (!\Hash::check($request->password, $user->password)) {
            return response()->json([
                "ok" => false,
                "message" => "Password mismatch"
            ], 401);
        }
    
        // Debug: Check if auth()->attempt() works
        if (!auth()->attempt($credentials)) {
            return response()->json([
                "ok" => false,
                "message" => "Auth attempt failed"
            ], 401);
        }
    
        // Login successful
        $user = auth()->user();
        $token = $user->createToken("auth-api")->accessToken;
    
        return response()->json([
            "ok" => true,
            "message" => "Login Successful",
            "token" => $token,
            "user" => $user
        ]);
    }
    
}