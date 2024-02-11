<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Models\Token;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Options\TokenTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::query()
            ->where('email', $request->validated('email'))
            ->firstOrFail();

        $user->update(['password' => Hash::make($request->validated('password'))]);

        $user->tokens()->delete();

        Token::deleteToken("password", $request->validated('email'));

        //$user->notify(new ResetPasswordNotification());

        return response()->json(['message' => 'Password reset successfully']);
    }
}
