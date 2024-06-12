<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait ResourceNotFound
{
    protected function checkResource($resource, $userId)
    {
        $resources = $resource->where('user_id', $userId)->get();

        if ($resources->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_NOT_FOUND,
                'message' => 'No resources found for the authenticated user'
            ], Response::HTTP_NOT_FOUND);
        }

        return null;
    }
}