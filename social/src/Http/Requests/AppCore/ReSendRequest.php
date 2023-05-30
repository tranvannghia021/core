<?php

namespace Core\Social\Http\Requests\AppCore;

use Core\Social\Http\Requests\BaseRequest;

class ReSendRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>'required'
        ];
    }
}
