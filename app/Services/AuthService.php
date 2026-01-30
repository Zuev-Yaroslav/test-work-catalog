<?php

namespace App\Services;

use App\Exceptions\AuthException;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public static function login(array $credentials)
    {
        AuthException::attemptOrFail($credentials);
        $user = Auth::user();

        return $user->createToken('token')->plainTextToken;
    }
}
