<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $data['responseMessage'] = 'Validation error!';
            $data['responseName'] = 'error';
            $data['data'] = $validator->errors();

            return $this->errorResponse($data, 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $response = Http::post(config('app.url') . '/oauth/token', [
                "grant_type" => 'password',
                "client_id" => config('services.passport.client_id'),
                "client_secret" => config('services.passport.client_secret'),
                "username" => $request->email,
                "password" => $request->password,
                "scope" => '',
            ]);

            if ($response->failed()) {
                $data['responseMessage'] = 'Failed to retrieve token';
                $data['responseName'] = 'error';
                $data['data'] = $response->json();

                return $this->errorResponse($data, 500);
            }

            $user['token'] = $response->json();

            $data['responseMessage'] = 'User has been logged successfully.';
            $data['responseName'] = 'token';
            $data['data'] = $user['token'];

            return $this->successResponse($data, 200);
        }

        $data['responseMessage'] = 'Unauthorized!';
        $data['responseName'] = 'data';
        $data['data'] = '';

        return $this->errorResponse($data, 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        $data['responseMessage'] = 'Logged out successfully';
        $data['responseName'] = 'data';
        $data['data'] = '';

        return $this->successResponse($data, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $data['responseMessage'] = 'Validation error!';
            $data['responseName'] = 'error';
            $data['data'] = $validator->errors();

            return $this->errorResponse($data, 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now()
        ]);

        $response = Http::post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        $user['token'] = $response->json();

        $data['responseMessage'] = 'User registered successfully';
        $data['responseName'] = 'user';
        $data['data'] = $user;

        return $this->successResponse($data, 201);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            $data['responseMessage'] = 'Validation error!';
            $data['responseName'] = 'error';
            $data['data'] = $validator->errors();

            return $this->errorResponse($data, 422);
        }

        $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),
            'scope' => '',
        ]);

        $data['responseMessage'] = 'Token has been refreshed.';
        $data['responseName'] = 'user';
        $data['data'] = $response->json();

        return $this->successResponse($data, 200);
    }
}
