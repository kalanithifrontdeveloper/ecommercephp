<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        // Use 'auth:api' instead of 'auth::api'
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return Response::json($validator->errors(), 400);
        }

        // Create a new user
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)] // Hash the password
        ));

        // Return a success message with the user data
        return Response::json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201); // Return a 201 Created status code
    }

    public function login(Request $request)
    {
        // Validate the login request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            // Return validation errors
            return response()->json($validator->errors(), 442);
        }

        // Attempt to authenticate the user and generate a JWT token
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the generated token
        return $this->createNewToken($token);
    }

    protected function createNewToken($token)
    {
        // Return the token details
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user'=>auth()->user()
        ]);
    }
    public function profile(){
        return response()->json(auth()->user());
    }
    public function logout(){
        auth()->logout();
        return response()->json([
            'message' => 'User logged out successfully'
        ]);
    }
}
