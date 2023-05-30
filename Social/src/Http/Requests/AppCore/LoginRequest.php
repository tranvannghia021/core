<?php

namespace Devtvn\Social\Http\Requests\AppCore;

use Devtvn\Social\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>'required|email|string',
            'password'=>'required|min:6|max:20'
        ];
    }
}
