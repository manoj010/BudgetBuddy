<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

trait UserOwnership
{
    //error-403//
    protected function checkOwnership($resource)
    {
        $userId = Auth::user()->id;

        if ($resource->user_id !== $userId) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You do not have permission to update this resource'
            ], Response::HTTP_FORBIDDEN);
        }
        return null;
    }

    protected function checkDelete($resource)
    {
        $user = auth()->user();

        if ($resource->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'You do not have permission to delete this resource'
            ], Response::HTTP_FORBIDDEN);
        }
        return null;
    }
}