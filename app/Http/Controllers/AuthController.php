<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            if (!auth()->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = JWTAuth::fromUser($user);
            
            return $this->createNewToken($token);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get authenticated user
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function self()
    {
        $self = User::with('currency')->find(auth()->id());
        return response()->json($self);
    }

    /**
     * Logout user
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['data' => 'logout_successful']);
    }

    /**
     * Refresh token
     *
     * @authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->createNewToken(auth()->refresh());
        } catch (\Exception $e) {
            return response()->json(['data' => 'invalid_or_expired_token'], 401);
        }
    }

    /**
     * Get the token array structure
     *
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'user' => User::find(Auth::id()),
            'access_token' => $token,
            'token_type' => 'bearer',
            'token_created' => time(),
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ], 200);
    }
}
