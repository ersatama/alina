<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function checked(): array
    {
        return $this->validator->validated();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'        =>  'failure',
            'status_code'   =>  ResponseAlias::HTTP_BAD_REQUEST,
            'message'       =>  'Bad Request',
            'errors'        =>  $validator->errors(),
        ], ResponseAlias::HTTP_BAD_REQUEST));
    }
}