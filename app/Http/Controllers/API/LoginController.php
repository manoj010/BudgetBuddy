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
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Login"},
     *     summary="Login user",
     *     description="Login user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="manoj@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="expires_at", type="integer", example=1717932517),
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="code", type="string", example="422"),
     *             @OA\Property(property="message", type="string", example="Validation error occurred")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Invalid Credentials.")
     *         )
     *     )
     * )
     */
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
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'expires_at' => $expirationTimestamp,
                'token' => $token,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'message' => "Invalid Credentials.",
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Get(
     *     path="/api/user-detail",
     *     summary="User Detail",
     *     description="Authenticated User's Detail",
     *     tags={"Login"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="success",
     *                 description="success"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 example=200,
     *                 description="HTTP status code"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1,
     *                     description="User's ID"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Test",
     *                     description="User's name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="test@gmail.com",
     *                     description="User's email address"
     *                 ),
     *                 @OA\Property(
     *                     property="email_verified_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2024-06-09T09:00:24.000000Z",
     *                     description="Timestamp of when the user's email was verified"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2024-06-09T09:00:24.000000Z",
     *                     description="Timestamp of when the user was created"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2024-06-09T09:00:24.000000Z",
     *                     description="Timestamp of when the user was last updated"
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="integer",
     *                 example=401,
     *                 description="HTTP status code"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid Credentials.",
     *                 description="Error message"
     *             ),
     *         ),
     *     ),
     * )
     */
    public function userDetails()
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'data' => $user,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'error' => 'Unauthorized. Please log in.',
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Login"},
     *     summary="Logout user",
     *     description="Logout user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="success"),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Logged out successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="error"),
     *             @OA\Property(property="code", type="integer", example=401),
     *             @OA\Property(property="message", type="string", example="Invalid Credentials.")
     *         )
     *     )
     * )
     */
    public function logoutUser()
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $token = $user->token();
            $token->revoke();
            return response()->json([
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Logged out successfully.'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED,
            'error' => 'Unauthorized.',
        ], Response::HTTP_UNAUTHORIZED);
    }
}
