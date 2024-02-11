<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param AuthenticationRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(AuthenticationRequest $request): JsonResponse
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password ?? null)) {
            throw ValidationException::withMessages(['message' => trans('auth.failed')]);
        }

        $user->tokens()->delete();

        $token = $user->createToken("basic-auth", ['*'], now()->addMinutes(15))->plainTextToken;

        return response()->json(['status' => "success", 'access_token' => $token], 201);
    }

    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
