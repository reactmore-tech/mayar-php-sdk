<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\InvalidContentType;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements;
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
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Software License Code Service constructor.
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
     * This endpoint is used to verify the validity of a software license
     * by providing a license code and product ID.
     *
     * @param array $data Required parameters:
     *  - 'licenseCode' (string) The license code to verify.
     *  - 'productId' (string) The product ID associated with the license.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function verifyLicense(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['licenseCode', 'productId']);
            $request = $this->adapter->post("software/v1/license/verify", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (MissingArguements $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), 400);
        } catch (InvalidContentType $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), 400);
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
