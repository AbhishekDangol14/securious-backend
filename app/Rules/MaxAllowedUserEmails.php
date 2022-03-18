<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxAllowedUserEmails implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $userEmails = Auth::user()->emails->count();
        return $userEmails <= 50 ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Email Count must not be greater than 50.';
    }
}
