<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Software License Code for v1 Mayar Headless API
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class SoftwareLicenseCode implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * @var AdapterInterface $adapter HTTP adapter to use for the service
     */
    private $adapter;

    /**
     * Software License Code constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter to use for the service
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Verify License
     * This endpoint is used to verify a license by providing the license code and product ID.
     *
     * @param array $data The data containing licenseCode (string) and productId (string).
     * @return array The response from the verification process.
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
