<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

trait AppErrorResponse
{
    //error-422
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    //error-404
    public function notFoundResponse($status = 'error', $code = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => 'Resource not Found'
        ], $code);
        return null;
    }
}
