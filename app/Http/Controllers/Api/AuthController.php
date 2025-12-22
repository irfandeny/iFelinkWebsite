<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user,
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Credentials invalid',
                    'error' => 'Email or password incorrect',
                ], 401);
            }

            //buat token bila kredensial benar
            if (! $token = Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'Login failed',
                    'error' => 'Could not generate token',
                ], 401);
            }

            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //logout
    public function logout(Request $request)
    {
        try {
            Auth::logout();
            return response()->json([
                'message' => 'Logout successful'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Logout failed'
            ], 500);
        }
    }

    //get authenticated user info
    public function me(Request $request)
    {
        return response()->json([
            'message' => 'Success get user profile',
            'data' => Auth::user()
        ]);
    }
}
