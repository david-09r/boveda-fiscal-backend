<?php

use App\Utils\Enum\EnumForStatus;
use Illuminate\Http\JsonResponse;

if (!function_exists('bodyResponse')) {
    function bodyResponse($statusCode, $data = []): JsonResponse
    {
        if (isset($data['message'])) {
            $responseData = [
                'message' => $data['message']
            ];
        }else {
            $responseData = [
                'data' => $data,
            ];
        }

        switch ($statusCode) {
            case EnumForStatus::OK:
                $responseData['meta'] = [
                    'status' => EnumForStatus::OK,
                    'msg' => EnumForStatus::MESSAGE_200
                ];
                return response()->json(
                    $responseData,
                    EnumForStatus::OK
                );
            case EnumForStatus::CREATED:
                $responseData['meta'] = [
                    'status' => EnumForStatus::CREATED,
                    'msg' => EnumForStatus::MESSAGE_201
                ];
                return response()->json(
                    $responseData,
                    EnumForStatus::CREATED
                );
            case EnumForStatus::NO_CONTENT:
                return response()->json(
                    [],
                    EnumForStatus::NO_CONTENT
                );
            case EnumForStatus::UNAUTHORIZED:
                return response()->json(
                    [],
                    EnumForStatus::UNAUTHORIZED
                );
            case EnumForStatus::NOT_FOUND:
                return response()->json(
                    [],
                    EnumForStatus::NOT_FOUND
                );
            default:
                return response()->json(
                    null,
                    EnumForStatus::INTERNAL_SERVER_ERROR
                );
        }
    }
}

if (!function_exists('bodyError')) {
    function bodyError($errorMessage = null, $code = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $errorMessage,
        ], $code);
    }
}

if (!function_exists('serviceResponse')) {
    function serviceResponse($statusCode, $data = []): array
    {
        if (is_string($data)) {
            return [
                'statusCode' => $statusCode,
                'data' => [
                    'message' => $data
                ]
            ];
        }

        return [
            'statusCode' => $statusCode,
            'data' => $data
        ];
    }
}
