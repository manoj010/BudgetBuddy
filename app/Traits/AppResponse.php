<?php

namespace App\Traits;

use App\Http\Resources\Category\{BaseCategoryCollection, BaseCategoryResource};
use Symfony\Component\HttpFoundation\Response;

trait AppResponse
{
    protected function successResponse($data, $status = 'success', $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => new BaseCategoryResource($data)
        ], $code);
    }

    protected function createdResponse($data, $status = 'success', $code = Response::HTTP_CREATED)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => new BaseCategoryResource($data)
        ], $code);
    }

    protected function deleteResponse($message = 'Resource deleted successfully', $status = 'success', $code = Response::HTTP_OK)
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

    protected function checkOrFindResource($resource, $id = null, $message = 'Resource not found', $status = 'error', $code = Response::HTTP_NOT_FOUND)
    {
        $userId = auth()->id();
        if ($id) {
            $resource = $resource->where('user_id', $userId)->find($id);
            if (!$resource) {
                return response()->json([
                    'status' => $status,
                    'code' => $code,
                    'message' => $message
                ], $code);
            }
        } else {
            $resources = $resource->where('user_id', $userId)->get();
            if ($resources->isEmpty()) {
                return response()->json([
                    'status' => $status,
                    'code' => $code,
                    'message' => $message
                ], $code);
            }
            return $resources;
        }

        return $resource;
    }
}
