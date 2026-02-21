<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\Traits;

use GuzzleHttp\Exception\RequestException;
use ReactMoreTech\MayarHeadlessAPI\Formatter\ResponseFormatter;

/**
 * Trait HandleExceptionTrait
 *
 * Provides a shared method for handling API request exceptions across all service classes.
 * This eliminates the duplicate handleException() method that was previously copy-pasted
 * in every service class.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\Traits
 */
trait HandleExceptionTrait
{
    /**
     * Handle API request exceptions.
     *
     * Processes and formats exceptions that occur during API requests,
     * returning a structured error response.
     *
     * @param RequestException $e The caught exception.
     * @return array Formatted error response with status code.
     */
    protected function handleException(RequestException $e)
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $responseBody = $response ? $response->getBody()->getContents() : null;

        if ($responseBody) {
            $errorData = json_decode($responseBody, true);
            $errorMessage = $errorData['messages'] ?? 'An error occurred';
            return ResponseFormatter::formatErrorResponse($errorMessage, $statusCode);
        }

        return ResponseFormatter::formatErrorResponse($e->getMessage(), $statusCode);
    }
}
