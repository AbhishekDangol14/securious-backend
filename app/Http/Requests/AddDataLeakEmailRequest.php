<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxAllowedUserEmails;


class AddDataLeakEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Validate email domain
        $user = auth()->user();
        $userEmail = $user->email;

        $emailDomain = '';
        if (!empty($userEmail)):
            $explode = explode('@', $userEmail);
            $emailDomain = 'domain:' . $explode[1];
        endif;

        return [
            'email' => ['required', 'unique:user_emails,email', 'email', $emailDomain, new MaxAllowedUserEmails],
        ];
    }
}
