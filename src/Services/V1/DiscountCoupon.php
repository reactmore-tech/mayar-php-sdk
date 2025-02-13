<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Discount and Coupon for Mayar Headless API V1
 *
 * This service handles webhook operations, including retrieving webhook history,
 * registering a webhook URL, testing the webhook, and retrying webhook events.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class DiscountCoupon implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Discount and Coupon Services constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Create Coupon.
     *
     * @param array $payoload payload required for this endpoint.
     * @return object The response containing webhook history data.
     * @throws \Exception If request fails.
     */
    public function create(array $payload = [])
    {
        try {
            $request = $this->adapter->post('hl/v1/coupon/create', [
                'json' => $payload
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve Coupon by ID.
     *
     * @param array $id parameter to added in URL.
     * @return object The response containing webhook history data.
     * @throws \Exception If request fails.
     */
    public function getCoupon(string $couponId)
    {
        try {
            $request = $this->adapter->get("hl/v1/coupon/{$couponId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API exceptions and format error responses.
     *
     * @param RequestException $e The exception thrown during the request.
     * @return array The formatted error response.
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
