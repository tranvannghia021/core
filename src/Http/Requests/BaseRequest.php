<?php

namespace Devtvn\Social\Http\Requests;

use Devtvn\Social\Traits\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    use Response;

    public function authorize()
    {
        return true;
    }

    /**
     * override method failedValidation
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {

        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException($this->ResponseRequest($errors));
    }
}
