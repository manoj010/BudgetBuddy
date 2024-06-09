<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function loginUser(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $tokenExpiration = Carbon::now()->addHours(1);
            Passport::personalAccessTokensExpireIn($tokenExpiration);
            $token = $user->createToken('userToken')->accessToken;
            $expirationTimestamp = $tokenExpiration->timestamp;

            return response()->json([
                'status' => Response::HTTP_OK,
                'expires_at' => $expirationTimestamp,
                'token' => $token,
            ], Response::HTTP_OK);
        }
        
        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'message' => "Invalid Credentials.",
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function userDetails()
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'data' => $user,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'error' => 'Unauthorized. Please log in.',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logoutUser()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $token = $user->token();
            $token->revoke();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Logged out successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'error' => 'Unauthorized',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
