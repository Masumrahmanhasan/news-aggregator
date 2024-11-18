<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    use SendResponse;

    /**
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $user = User::query()->create($request->validated());
        return $this->success($user, 'You have successfully registered.');
    }
}
