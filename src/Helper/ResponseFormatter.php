<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper;

use stdClass;

/**
 * The ResponseFormatter class provides static methods to format API response.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Helper
 */
class ResponseFormatter
{
    /**
     * Format response dari API
     *
     * @param string $responseBody
     * @param string|null $message
     * @return array
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

        return [
            'success' => true,
            'message' => $message ?? 'Request berhasil',
            'data' => $response,
            'status_code' => $response['statusCode'] ?? 200,
        ];
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
        return [
            'success' => false,
            'message' => $message,
            'data' => null,
            'status_code' => $statusCode,
        ];
    }
}
