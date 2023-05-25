<?php

namespace App\Http\Requests\AppCore;

use App\Http\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return config('social.custom_request.app.register');
    }
}
