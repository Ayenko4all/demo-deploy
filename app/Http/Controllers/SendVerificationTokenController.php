<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Notifications\TokenNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SendVerificationTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        Token::deleteToken("verification", $request->input('email'));

        $token = Token::create([
            'email' => $request->input('email'),
            'token' => generateToken(),
            'type'  => "verification",
        ]);

        /* uncomment to send a notification to mail */
       // \Notification::route('mail', $token->email)->notify(new TokenNotification($token->token));

        return response()->json(['message' => 'Verification codes sent successfully']);
    }
}
