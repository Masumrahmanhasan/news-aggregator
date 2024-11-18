<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPreferenceStoreRequest;
use App\Traits\SendResponse;
use Illuminate\Http\JsonResponse;
use function auth;

class UserPreferenceController extends Controller
{
    use SendResponse;

    public function index(): JsonResponse
    {
        $preferences = auth()->user()->preferences;
        return $this->success($preferences, 'User Preferences List');
    }

    public function store(UserPreferenceStoreRequest $request): JsonResponse
    {
        $preferences = auth()->user()->preferences()->updateOrCreate(
            ['user_id' => auth()->id()],
            $request->validated()
        );
        return $this->success($preferences, 'User Preferences Updated');
    }
}
