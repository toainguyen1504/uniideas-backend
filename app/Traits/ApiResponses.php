<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    /**
     * Success Response.
     *
     * @param  mixed  $data
     * @param  int    $statusCode
     * @param  string $message
     * @return JsonResponse
     */
    public function successResponse(mixed $data, string $message = '', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $data = $data instanceof AnonymousResourceCollection ? $data->resource : $data;
        if ($data instanceof LengthAwarePaginator) {
            return response()->json([
                'message' => $message,
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ],
            ], $statusCode);
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Error Response.
     *
     * @param  mixed   $data
     * @param  string  $message
     * @param  int     $statusCode
     * @return JsonResponse
     */
    public function errorResponse(mixed $data, string $message = '', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        if (! $message) {
            $message = Response::$statusTexts[$statusCode];
        }

        return response()->json([
            'message' => $message,
            'errors' => $data,
        ], $statusCode);
    }

    /**
     * Response with status code 200.
     *
     * @param  mixed  $data
     * @param  string $message
     * @return JsonResponse
     */
    public function okResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->successResponse($data, $message);
    }

    /**
     * Response with status code 201.
     *
     * @param  mixed  $data
     * @param  string $message
     * @return JsonResponse
     */
    public function createdResponse(mixed $data, string $message = ''): JsonResponse
    {
        return $this->successResponse($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Response with status code 204.
     *
     * @return JsonResponse
     */
    public function noContentResponse(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Response with status code 400.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function badRequestResponse(mixed $data, string $message = ''): JsonResponse
    {
        if (! $message) {
            $message = __('Đã xảy ra lỗi hệ thống.');
        }

        return $this->errorResponse($data, $message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Response with status code 401.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function unauthorizedResponse(mixed $data = null, string $message = ''): JsonResponse
    {
        if (! $message) {
            $message = __('Bạn chưa đăng nhập hoặc không có quyền truy cập.');
        }

        return $this->errorResponse($data, $message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response with status code 403.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function forbiddenResponse(mixed $data = null, string $message = ''): JsonResponse
    {
        if (! $message) {
            $message = __('Bạn không có quyền truy cập vào tài nguyên này.');
        }

        return $this->errorResponse($data, $message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Response with status code 404.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function notFoundResponse(mixed $data = null, string $message = ''): JsonResponse
    {
        if (! $message) {
            $message = __('Trang không tồn tại.');
        }

        return $this->errorResponse($data, $message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Response with status code 409.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function conflictResponse(mixed $data, string $message = ''): JsonResponse
    {
        if (! $message) {
            $message = __('Yêu cầu không thể thực hiện do xung đột với trạng thái hiện tại của tài nguyên.');
        }

        return $this->errorResponse($data, $message, Response::HTTP_CONFLICT);
    }

    /**
     * Response with status code 422.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return JsonResponse
     */
    public function unprocessableResponse(mixed $data, string $message = ''): JsonResponse
    {
        if (! $message) {
            $message = __('Dữ liệu không hợp lệ.');
        }

        return $this->errorResponse($data, $message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
