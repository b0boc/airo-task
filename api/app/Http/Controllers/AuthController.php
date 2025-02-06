<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            // In case there is a user registered, check if the login check up
            if($user) {
                // In case the login fails, abort
                if (!Auth::attempt($request->only('email', 'password'))) {
                    return response()->json([
                        'message' => 'Invalid credentials',
                    ], Response::HTTP_FORBIDDEN);
                }
    
                // Everything ok, return token
                return response()->json([
                    'token' => JWTAuth::fromUser($user),
                    'message' => 'Logged in',
                ], Response::HTTP_OK);
            }
            // Otherwise, register new user
            else {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
        
                Auth::login($user);
        
                return response()->json([
                    'token' => JWTAuth::fromUser($user),
                    'message' => 'Registered and logged in!',
                ], Response::HTTP_CREATED);
            }
        } catch(ValidationException $e) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_FORBIDDEN);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Invalid credentials '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request) {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to invalidate token '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
