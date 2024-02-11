<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $request->validated('first_name'),
                'last_name'  => $request->validated('last_name'),
                'email'      => $request->validated('email'),
                'password'   => Hash::make($request->validated('password')),
            ]);

            $token = $user->createToken("basic-auth", ['*'], now()->addMinutes(15))->plainTextToken;

            DB::commit();

            return response()->json(['status' => "success", "message" => "User successfully created!", 'access_token' => $token], 201);
        } catch (\Throwable $exception){
            DB::rollback();
            return response()->json(['status' => 'fail', 'status_code' => 503, 'error' => ['message' => "Registration failed. Please try again!"]], 503);
        }
    }
}
