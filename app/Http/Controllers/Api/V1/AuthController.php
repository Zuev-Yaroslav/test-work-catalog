<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\AuthException;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = UserService::login($credentials);

        return response()->json([
            'token' => $token,
        ]);
    }
    public function me()
    {
        $user = Auth::user();

        return response()->json($user);
    }
    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
