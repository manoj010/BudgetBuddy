<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

trait AppResponse
{
    protected function success($data, $message = '', $status = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message
        ], $status);
    }

    protected function error($e, $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], $status);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'errors' => $validator->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }

    public function notFound($status = 'error', $code = Response::HTTP_NOT_FOUND)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => 'Resource not Found'
        ], $code);
        return null;
    }

    protected function checkOrFindResource($resource, $id = null, $message = 'Resource not found', $status = Response::HTTP_NOT_FOUND)
    {
        $userId = auth()->id();
        if ($id) {
            $resource = $resource->where('user_id', $userId)->find($id);
            if (!$resource) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message
                ], $status);
            }
        } else {
            $resources = $resource->where('user_id', $userId)->get();
            if ($resources->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message
                ], $status);
            }
            return $resources;
        }

        return $resource;
    }
}
