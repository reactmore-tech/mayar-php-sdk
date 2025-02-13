<?php

namespace ReactMoreTech\MayarHeadlessAPI\Adapter;

use Psr\Http\Message\ResponseInterface;
use ReactMoreTech\MayarHeadlessAPI\Auth\Auth;

/**
 * The Adapter interface provides a common interface for all HTTP request adapters used in the Flip SDK.
 */
interface AdapterInterface
{
    /**
     * Adapter constructor.
     *
     * @param Auth $auth The authentication credentials used for the request.
     * @param bool $isProduction Indicates whether the adapter should use the production API endpoint or not.
     * @param string $baseURI The base URI for the API endpoint.
     */
    public function __construct(Auth $auth, bool $isProduction, string $baseURI);

    /**
     * Sends a GET request.
     *
     * Per the Robustness Principle, this method does not include the ability to send a body with a GET request (though
     * it is technically possible in the RFCs, it is never useful).
     *
     * @param string $uri The URI for the request.
     * @param array $data An array of data to send with the request.
     * @param array $headers An array of headers to send with the request.
     * @return ResponseInterface The response object from the API.
     */
    public function get(string $uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * Sends a POST request.
     *
     * @param string $uri The URI for the request.
     * @param array $data An array of data to send with the request.
     * @param array $headers An array of headers to send with the request.
     * @return ResponseInterface The response object from the API.
     */
    public function post(string $uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * Sends a PUT request.
     *
     * @param string $uri The URI for the request.
     * @param array $data An array of data to send with the request.
     * @param array $headers An array of headers to send with the request.
     * @return ResponseInterface The response object from the API.
     */
    public function put(string $uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * Sends a PATCH request.
     *
     * @param string $uri The URI for the request.
     * @param array $data An array of data to send with the request.
     * @param array $headers An array of headers to send with the request.
     * @return ResponseInterface The response object from the API.
     */
    public function patch(string $uri, array $data = [], array $headers = []): ResponseInterface;

    /**
     * Sends a DELETE request.
     *
     * @param string $uri The URI for the request.
     * @param array $data An array of data to send with the request.
     * @param array $headers An array of headers to send with the request.
     * @return ResponseInterface The response object from the API.
     */
    public function delete(string $uri, array $data = [], array $headers = []): ResponseInterface;
}

