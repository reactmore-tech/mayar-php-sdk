<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Customer Service for Mayar Headless API V1
 *
 * Provides functionalities for managing customer data, including retrieval,
 * creation, updating, and generating magic links.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Customer implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Customer constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Retrieve a list of customers.
     *
     * @param array $data Contains optional parameters such as 'page' and 'pageSize'.
     * @return array The formatted API response containing customer data.
     * @throws \Exception If the request fails.
     */
    public function getList(array $data = [])
    {
        try {
            $request = $this->adapter->get('hl/v1/customer', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Generate a magic link for customer login.
     *
     * Sends a magic link to the customer's email to allow login to the customer portal.
     *
     * @param string|null $email Customer email to receive the magic link.
     * @return array The formatted API response.
     */
    public function createMagicLink(string $email = null)
    {
        try {
            $request = $this->adapter->post("hl/v1/customer/login/portal", ['email' => $email]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create a new customer record.
     *
     * @param array $data Contains required fields: 'name' (string), 'email' (string), and 'mobile' (string).
     * @return array The formatted API response.
     */
    public function create(array $data = [])
    {
        try {
            $request = $this->adapter->post("hl/v1/customer/create", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update an existing customer record.
     *
     * @param array $data Contains required fields: 'fromEmail' (string) and 'toEmail' (string).
     * @return array The formatted API response.
     */
    public function update(array $data = [])
    {
        try {
            $request = $this->adapter->post("hl/v1/customer/update", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API request exceptions.
     *
     * @param RequestException $e The caught exception.
     * @return array Formatted error response with status code.
     */
    private function handleException(RequestException $e)
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
