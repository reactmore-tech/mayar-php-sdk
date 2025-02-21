<?php

namespace ReactMoreTech\MayarHeadlessAPI\Formatter;

class ResponseFormatter
{
    /**
     * Format response dari API
     *
     * @param string $responseBody
     * @param string|null $message
     * @return ApiResponse
     */
    public static function formatResponse($responseBody, $message = null)
    {
        $response = json_decode($responseBody, true);

        if (isset($response['statusCode']) && $response['statusCode'] !== 200) {
            return self::formatErrorResponse(
                $response['messages'] ?? 'Terjadi kesalahan',
                $response['statusCode']
            );
        }

        // Ambil data paginasi jika ada di response
        $paginationKeys = ['hasMore', 'pageCount', 'pageSize', 'page'];
        $paginationData = [];
        foreach ($paginationKeys as $key) {
            if (isset($response[$key])) {
                $paginationData[$key] = $response[$key];
            }
        }

        return new Response(array_merge([
            'success' => true,
            'message' => $response['messages'] ?? ($message ?? 'Request berhasil'),
            'data' => $response['data'] ?? NULL,
            'status_code' => $response['statusCode'] ?? 200,
        ], $paginationData));
    }

    /**
     * Format error response
     *
     * @param string $message
     * @param int $statusCode
     * @return array
     */
    public static function formatErrorResponse($message, $statusCode = 500)
    {
        return new Response([
            'success' => false,
            'message' => $message,
            'data' => null,
            'status_code' => $statusCode,
        ]);
    }
}
