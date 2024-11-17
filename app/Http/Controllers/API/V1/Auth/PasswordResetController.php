<?php
declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use App\Http\Requests\Auth\PasswordResetUpdateRequest;
use App\Models\User;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    use SendResponse;

    public function store(PasswordResetRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return $this->success([], trans($status));
        }

        return $this->failed([], trans($status), statusCode: 400);
    }

    public function update(PasswordResetUpdateRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request): void {
                $user->forceFill([
                    'password' => Hash::make($request->string('password')->value()),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success([], trans($status));
        }
        return $this->failed([], trans($status), statusCode: 400);
    }
}
