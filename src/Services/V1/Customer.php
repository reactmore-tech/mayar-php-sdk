<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Formatter\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use GuzzleHttp\Exception\RequestException;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\BaseException;

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
     * Initializes the Customer service with the provided HTTP adapter.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Retrieve a paginated list of customers.
     *
     * This method fetches a list of customers from the API. It supports optional
     * pagination parameters such as 'page' and 'pageSize'.
     *
     * @param array $data Optional parameters:
     *  - 'page' (int) Page number.
     *  - 'pageSize' (int) Number of items per page.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getList($data = [])
    {
        try {
            Validator::validateArrayRequest($data);
            $request = $this->adapter->get('hl/v1/customer', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Generate a magic link for customer login.
     *
     * Sends a magic link to the customer's email, allowing them to log in to
     * the customer portal without a password.
     *
     * @param array $data Required parameter:
     *  - 'email' (string) Customer's email address.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function createMagicLink(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['email']);
            $request = $this->adapter->post("hl/v1/customer/login/portal", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create a new customer record.
     *
     * Registers a new customer with the provided details.
     *
     * @param array $data Required parameters:
     *  - 'name' (string) Customer's full name.
     *  - 'email' (string) Customer's email address.
     *  - 'mobile' (string) Customer's mobile phone number.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function create(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['name', 'email', 'mobile']);
            $request = $this->adapter->post("hl/v1/customer/create", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update an existing customer record.
     *
     * Updates the email address of an existing customer.
     *
     * @param array $data Required parameters:
     *  - 'fromEmail' (string) Current email address.
     *  - 'toEmail' (string) New email address.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function update(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['fromEmail', 'toEmail']);
            $request = $this->adapter->post("hl/v1/customer/update", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API request exceptions.
     *
     * Processes and formats exceptions that occur during API requests,
     * returning a structured error response.
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
