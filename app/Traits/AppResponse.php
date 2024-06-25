<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait AppResponse
{
    protected function successResponse($data, $message = '', $status = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message
        ], $status);
    }

    protected function createdResponse($data, $message = '', $status = Response::HTTP_CREATED)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message
        ], $status);
    }

    protected function serverErrorResponse($e, $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], $status);
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
