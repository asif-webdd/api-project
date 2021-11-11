<?php

use Illuminate\Http\JsonResponse;

/**
 * Errors of Validation
 *
 * @param $errors
 * @return JsonResponse
 */
function error_validation($errors): JsonResponse
{
    return response()->json([
        'success' => false,
        'errors' => $errors
    ], 422);
}


/**
 * Send success response with data
 *
 * @param $data
 * @param string $message
 * @param int $code
 * @return JsonResponse
 */
function success_response($data, string $message = "", int $code = 201): JsonResponse
{
    return response()->json([
        'success' => true,
        'data' => $data,
        'message' => $message
    ], $code);
}


/**
 * Send error response with message
 *
 * @param string $message
 * @param int $code
 * @return JsonResponse
 */
function error_response(string $message = "", int $code = 400): JsonResponse
{
    return response()->json([
        'success' => true,
        'message' => $message
    ], $code);
}

