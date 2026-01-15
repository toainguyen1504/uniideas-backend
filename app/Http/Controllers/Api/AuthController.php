<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers, ApiResponses;

    /**
     * Login.
     *
     * @unauthenticated
     *
     * This endpoint authenticates the user based on provided credentials and returns an API token upon successful login.
     *
     * @bodyParam email string required The email of the user attempting to log in. Example: user@example.com
     * @bodyParam password string required The password for the user account. Example: password123
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        if ($this->attemptLogin($request)) {
            $user = auth()->user();

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => __('Đăng nhập thành công'),
                'data' => [
                    'token' => $token,
                    'user' => new UserResource($user),
                ],
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'message' => __('Email hoặc mật khẩu không đúng'),
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * Log out.
     *
     * This endpoint revokes the current user's access token, effectively logging them out of the API.
     *
     * @authenticated
     *
     * @param  \Illuminate\Http\Request  $request The HTTP request containing the authorization token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => __('Đăng xuất thành công')
        ], JsonResponse::HTTP_OK);
    }
}
