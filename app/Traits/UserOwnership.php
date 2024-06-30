<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

trait UserOwnership
{
    protected function checkOwnership($resource, $message = 'Permission Denied.', $status = Response::HTTP_FORBIDDEN)
    {
        $user = auth()->user();
        if ($resource->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => $message
            ], $status);
        }
        return null;
    }
}