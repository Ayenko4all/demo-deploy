<?php

namespace App\Rules;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidateToken implements Rule
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $column;

    /**
     * @var string
     */
    private $value;

    /**
     * @var bool
     */
    private $verified;

    /**
     * Create a new rule instance.
     *
     * @param mixed $type
     * @param mixed $column
     * @param mixed $value
     * @param bool  $verified
     */
    public function __construct($type, $column, $value, bool $verified = true)
    {
        $this->type = $type;
        $this->column = $column;
        $this->value = $value;
        $this->verified = $verified;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $token = Token::query()
            ->where('token', $value)
            ->where('type', $this->type)
            ->where(function ($query) {
                $query->where('email', $this->value)
                    ->orWhere('telephone', $this->value);
            })
            ->first();

        if (! $token) {
            return false;
        }

        if (! $this->verified && $token->verified) {
            return false;
        }

        if (Carbon::parse($token->created_at)->addMinutes(config('auth.verification.email.expire'))->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is invalid or has expired.';
    }
}
