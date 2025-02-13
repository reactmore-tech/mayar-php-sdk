<?php

namespace ReactMoreTech\MayarHeadlessAPI\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;

/**
 * Exception that is thrown when there is an error response from Guzzle.
 *
 * This exception is typically thrown when there is an error response from Guzzle client that indicates
 * a client error or server error.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Exceptions
 */
class ResponseException extends Exception
{
    /**
     * Generates a ResponseException from a Guzzle RequestException.
     *
     * This method is used to generate a ResponseException from a Guzzle RequestException. This method
     * attempts to derive a detailed error from the standard JSON response.
     *
     * @param RequestException $err The client request exception (typically 4xx or 5xx response).
     * @return self
     */

    public static function fromRequestException(RequestException $err): self
    {
        if (!$err->hasResponse()) {
            return new ResponseException($err->getMessage(), 0, $err);
        }

        $response = $err->getResponse();
        $contentType = $response->getHeaderLine('Content-Type');

        // Attempt to derive detailed error from standard JSON response.
        if (strpos($contentType, 'application/json') !== false) {
            $json = json_decode($response->getBody());
            if (json_last_error() !== JSON_ERROR_NONE) {
                return new ResponseException(
                    $err->getMessage(),
                    0,
                    new JSONException(json_last_error_msg(), 0, $err)
                );
            }

            if (isset($json->errors) && count($json->errors) >= 1) {
                return new ResponseException(
                    $json->errors[0]->message,
                    $json->errors[0]->code,
                    $err
                );
            }
        }

        return new ResponseException($err->getMessage(), 0, $err);
    }
}
