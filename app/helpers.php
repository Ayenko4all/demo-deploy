<?php

use App\Models\Token;

if (! function_exists('generateToken')) {
    /**
     * Generate a unique token for a user.
     *
     * @return string
     */
    function generateToken(): string
    {
        do {
            $token = mt_rand(100000, 999999);
        } while (Token::whereToken($token)->exists());

        return (string) $token;
    }
}
