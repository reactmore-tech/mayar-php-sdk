<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * SaaS Membership License Service for Mayar Headless API V1
 *
 * This service handles the verification of software license codes
 * for SaaS Membership in Mayar's platform. The API ensures that software
 * vendors can manage licensing seamlessly without handling payment processing,
 * unique license code generation, validation, and expiration tracking manually.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class SaaSMembershipLicense implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * SaaS Membership License constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Verify a software license.
     *
     * This method sends a request to verify a software license code against
     * a product ID in the Mayar system. If the license exists and is valid,
     * the response will contain its details, including status, expiration date,
     * and associated customer information.
     *
     * @param array $data An associative array containing:
     *                    - licenseCode (string): The license code to verify.
     *                    - productId (string): The product ID associated with the license.
     *
     * @return array The response containing verification details.
     *
     * @throws RequestException If the request fails due to network issues or API errors.
     */
    public function verifyLicense(array $data = [])
    {
        try {
            $request = $this->adapter->post("saas/v1/license/verify", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Activate License.
     *
     * This endpoint is used to activate a license by providing the license code and product ID.
     * By performing this action, the status of the license code will change to ACTIVE.
     *
     * @param array $data An associative array containing:
     *                    - licenseCode (string): The license code to verify.
     *                    - productId (string): The product ID associated with the license.
     *
     * @return array The response containing verification details.
     *
     * @throws RequestException If the request fails due to network issues or API errors.
     */
    public function activateLicense(array $data = [])
    {
        try {
            $request = $this->adapter->post("saas/v1/license/activate", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Deactivated License.
     *
     * This endpoint is used to deactivate a license by providing the license code and product ID.
     * By performing this action, the status of the license code will change to INACTIVE.
     *
     * @param array $data An associative array containing:
     *                    - licenseCode (string): The license code to verify.
     *                    - productId (string): The product ID associated with the license.
     *
     * @return array The response containing verification details.
     *
     * @throws RequestException If the request fails due to network issues or API errors.
     */
    public function deactivateLicense(array $data = [])
    {
        try {
            $request = $this->adapter->post("saas/v1/license/deactivate", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API exceptions.
     *
     * This method processes API errors, extracting meaningful messages and
     * returning a structured response to the user.
     *
     * @param RequestException $e The exception thrown during the API request.
     *
     * @return array The formatted error response including status code and message.
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
