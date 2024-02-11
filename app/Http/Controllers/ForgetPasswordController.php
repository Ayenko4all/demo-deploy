<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use App\Options\TokenTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ForgetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email', 'exists:users']]);

        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if (! $user) {
            throw ValidationException::withMessages(['email' => trans('auth.failed')]);
        }

        Token::deleteToken("password", $data['email']);

        $token = Token::create([
            'email' => $data['email'],
            'token' => generateToken(),
            'type'  => 'password',
        ]);

        return response()->json(['message' => 'Password reset token sent successfully']);
    }
}
