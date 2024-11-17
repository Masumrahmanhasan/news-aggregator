<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function dd;

class ProfileController extends Controller
{
    use SendResponse;

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        return $this->success($user, 'User Profile fetched successfully.');
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return $this->success([], 'You have successfully logged out.');
    }
}
