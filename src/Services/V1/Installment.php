<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Installment Service for Mayar Headless API V1
 *
 * Create and Check Installment api
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Installment implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Installment Service constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * installment Detail
     *
     * @return object The formatted API response Installment Detail.
     * @throws \Exception If the request fails.
     */
    public function detail(string $installmentId)
    {
        try {
            $request = $this->adapter->get("hl/v1/installment/{$installmentId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Installment Create.
     *
     * endpoint to cretae installment. All field in payload ar mandatory if we dont describe, 
     * its optional
     * 
     * @param array $payload Contains the installment data.
     *
     * @return object The formatted API response containing customer data.
     * @throws \Exception If the request fails.
     */
    public function create(array $payload)
    {
        try {
            $request = $this->adapter->post("hl/v1/installment/create", [
                'json' => $payload
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API request exceptions.
     *
     * @param RequestException $e The caught exception.
     * @return object Formatted error response with status code.
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
