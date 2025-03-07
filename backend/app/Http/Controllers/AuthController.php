<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        // Generate refresh token manually
        $refreshToken = JWTAuth::claims(['refresh' => true])->fromUser($user);

        // Store refresh token in HttpOnly cookie
        $cookie = Cookie::make('refresh_token', $refreshToken, config('jwt.refresh_ttl'), '/', null, true, true)->withSameSite('None');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ])->cookie($cookie);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function refresh(Request $request)
    {
        try {
            $refreshToken = Cookie::get('refresh_token');

            if (!$refreshToken) {
                return response()->json(['error' => 'No refresh token provided'], 401);
            }

            $newToken = JWTAuth::setToken($refreshToken)->refresh();

            $newRefreshToken = JWTAuth::claims(['refresh' => true])->fromUser(auth()->user());

            $cookie = Cookie::make(
                'refresh_token',
                $newRefreshToken,
                config('jwt.refresh_ttl'),
                '/',
                null,
                true,
                true
            )->withSameSite('None');

            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ])->cookie($cookie);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Refresh token expired, please log in again'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function user()
    {
        return response()->json([
            'user' => auth()->user(),
            'is_admin' => auth()->user()->is_admin
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out'])
            ->cookie(Cookie::forget('refresh_token'));
    }
}
