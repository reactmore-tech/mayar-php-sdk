<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Webhook for Mayar Headless API V1
 *
 * This service handles webhook operations, including retrieving webhook history,
 * registering a webhook URL, testing the webhook, and retrying webhook events.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class WebhookServices implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Webhook Services constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Retrieve webhook history.
     *
     * @param array $data Query parameters such as page, pageSize, status, type, etc.
     * @return array The response containing webhook history data.
     * @throws \Exception If request fails.
     */
    public function getWebhookHistory(array $data = [])
    {
        try {
            $request = $this->adapter->get('hl/v1/webhook/history', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Register a webhook URL.
     *
     * @param string|null $url The URL to register for webhook events.
     * @return array The response indicating success or failure.
     */
    public function setWebhookURL(string $url = null)
    {
        try {
            $request = $this->adapter->post("hl/v1/webhook/register", ['urlHook' => $url]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Test a registered webhook URL.
     *
     * @param string|null $url The webhook URL to test.
     * @return array The response indicating success or failure.
     */
    public function testWebhookURL(string $url = null)
    {
        try {
            $request = $this->adapter->post("hl/v1/webhook/test", ['urlHook' => $url]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }
    
    /**
     * Retry a failed webhook event.
     *
     * @param string|null $webhookHistoryId The ID of the webhook event to retry.
     * @return array The response indicating success or failure.
     */
    public function retryWebhookURL(string $webhookHistoryId = null)
    {
        try {
            $request = $this->adapter->post("hl/v1/webhook/retry", ['webhookHistoryId' => $webhookHistoryId]);
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
