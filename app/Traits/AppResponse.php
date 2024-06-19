<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait AppResponse
{
    protected function successResponse($data, $status = 'success', $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    protected function createdResponse($data, $status = 'success', $code = Response::HTTP_CREATED)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $data
        ], $code);
    }

    protected function deleteResponse($message, $status = 'success', $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message
        ], $code);
    }

    protected function serverErrorResponse($e, $status = 'error', $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], $code);
    }

    protected function findResource($resource, $id, $message = 'Category Not Found', $status = 'error', $code = Response::HTTP_NOT_FOUND)
    {
        $userId = auth()->id();
        $resource = $resource->where('user_id', $userId)->find($id);
        if (!$resource) {
            return response()->json([
                'status' => $status,
                'code' => $code,
                'message' => $message
            ], $code);
        }
        return null;
    }

    protected function checkResource($resource, $userId, $status = 'error', $code = Response::HTTP_NOT_FOUND)
    {
        $resources = $resource->where('user_id', $userId)->get();
        if ($resources->isEmpty()) {
            return response()->json([
                'status' => $status,
                'code' => $code,
                'message' => 'No resources found for the authenticated user'
            ], $code);
        }
        return null;
    }
}
