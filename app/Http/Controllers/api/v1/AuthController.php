<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegisterRequest;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
    }
    public function refresh(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::where('refresh_token', $request->refresh_token)->first();

            if (!$user) {
                return response()->json(['error' => 'Invalid refresh token'], 401);
            }

            // Generate a new access token using the existing refresh token
            $token = auth()->login($user);

            if (!$token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function register(StoreRegisterRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            $user->save();

            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }


    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if (!$token = auth()->attempt($validator->validated())) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $user = User::where('email', $request->email)->first();
            $refresh_token =  Str::random(330);
            $user->refresh_token = $refresh_token;
            $user->save();

            return $this->createNewToken($token, $refresh_token);
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }
    public function logout()
    {
        try {
            auth()->logout();

            return response()->json(['message' => 'User successfully signed out']);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    protected function createNewToken($token, $refresh_token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user(),
            'refresh_token' => $refresh_token,
        ]);
    }
}
