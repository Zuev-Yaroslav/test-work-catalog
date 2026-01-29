<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        return response([
            'message' => $this->message
        ], $this->code);
    }

    public static function attemptOrFail(array $credentials): void
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthException('Incorrect email or password.', Response::HTTP_UNAUTHORIZED);
        }
    }
}
