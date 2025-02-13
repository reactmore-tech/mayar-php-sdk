<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Software License Code Service for Mayar Headless API V1
 *
 * This service provides functionality for verifying software licenses
 * issued through the Mayar platform. It allows developers to check
 * the validity and status of a given license.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class SoftwareLicenseCode implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter instance.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Constructor for SoftwareLicenseCode Service.
     *
     * @param AdapterInterface $adapter The HTTP adapter for making requests.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Verify a software license.
     *
     * This endpoint is used to verify the validity of a software license
     * by providing a license code and product ID.
     *
     * @param array $data The request payload containing:
     *                    - licenseCode (string): The license code to verify.
     *                    - productId (string): The product ID associated with the license.
     * @return array The API response containing the license status and details.
     *
     * @throws RequestException If the request fails due to network issues or API errors.
     */
    public function verifyLicense(array $data = [])
    {
        try {
            $request = $this->adapter->post("software/v1/license/verify", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API exceptions.
     *
     * This method catches API request exceptions and formats them
     * into a structured error response.
     *
     * @param RequestException $e The caught exception.
     * @return array The formatted error response.
     */
    private function handleException(RequestException $e)
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $responseBody = $response ? $response->getBody()->getContents() : null;

        if ($responseBody) {
            $errorData = json_decode($responseBody, true);
            $errorMessage = $errorData['message'] ?? 'An unexpected error occurred.';
            return ResponseFormatter::formatErrorResponse($errorMessage, $statusCode);
        }

        return ResponseFormatter::formatErrorResponse($e->getMessage(), $statusCode);
    }
}
