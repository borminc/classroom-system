<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function okWithMessage($msg = 'Request successful'): JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'success' => true,
        ], Response::HTTP_OK);
    }

    public function okWithData($data = null, $msg = 'Successful request'): JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'success' => true,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    protected function created($data, $msg = 'Created successfully'): JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'data' => $data,
            'success' => true,
        ], Response::HTTP_CREATED);
    }

    protected function updated($data = null, $msg = 'Updated successfully'): JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'data' => $data,
            'success' => true,
        ]);
    }

    protected function deleted($data = null, $msg = 'Deleted successfully'): JsonResponse
    {
        return response()->json([
            'message' => $msg,
            'data' => $data,
            'success' => true,
        ], Response::HTTP_OK);
    }

    protected function unauthenticated($message = 'Unauthenticated'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'success' => false,
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function errorMessage($message = 'Error'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'success' => false,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    protected function errors($errors): JsonResponse
    {
        return response()->json([
            'message' => __('The given data was invalid.'),
            'errors' => $errors,
            'success' => false,
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
