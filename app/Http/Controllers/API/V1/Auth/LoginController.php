<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use SendResponse;

    /**
     * @param  LoginRequest  $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (!auth()->attempt($request->only('email', 'password'))) {
            return $this->failed([], trans('auth.failed'));
        }

        $token = auth()->user()->createToken('news_auth')->plainTextToken;

        return $this->success($this->respondWithToken($token), 'You have been logged in successfully');
    }

    /**
     * @param  string  $token
     * @return array
     */
    protected function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('sanctum.expiration')
                ? config('sanctum.expiration') * 60 :
                null,
        ];
    }
}
