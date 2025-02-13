<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Customer Mayar Headless API V1
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Customer implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * @var AdapterInterface $adapter HTTP adapter to use for the service
     */
    private $adapter;

    /**
     * Customer constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter to use for the service
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get List Customer
     * 
     * Get your customer data
     *
     * @param array $data The data containing page (string) and pageSize (string).
     * @return ResponseFormatter
     * @throws \Exception If request fails for any reason.
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
     * Create Magic Link
     *
     * create magic link and send to customer email for their login in our customer portal
     *
     * @param string|null $email
     * @return ResponseFormatter
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
     * Create Customer Data
     *
     * @param array $data The data containing name (string), email (string), and mobile (string).
     * @return ResponseFormatter
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
     * Update Customer Data
     *
     * @param array $data The data containing fromEmail (string) and toEmail (string).
     * @return ResponseFormatter
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
     * Handle API exceptions
     *
     * @param RequestException $e
     * @return array
     */
    private function handleException(RequestException $e)
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $responseBody = $response ? $response->getBody()->getContents() : null;

        if ($responseBody) {
            $errorData = json_decode($responseBody, true);
            $errorMessage = $errorData['messages'] ?? 'Terjadi kesalahan';
            return ResponseFormatter::formatErrorResponse($errorMessage, $statusCode);
        }

        return ResponseFormatter::formatErrorResponse($e->getMessage(), $statusCode);
    }
}
