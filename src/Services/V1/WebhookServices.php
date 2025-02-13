<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use GuzzleHttp\Exception\RequestException;

/**
 * Webhook Services for v1 Mayar Headless API
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class WebhookServices implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * @var AdapterInterface $adapter HTTP adapter to use for the service
     */
    private $adapter;

    /**
     * WebhookServices constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter to use for the service
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get Webhook History
     *
     * @throws \Exception If request fails for any reason.
     */
    public function getWebhookHistory(array $data = [])
    {
        try {
            $request = $this->adapter->get('v1/webhook/history', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Register Webhook URL
     *
     * @param string|null $url
     * @return array
     */
    public function setWebhookURL(string $url = null)
    {
        try {
            $request = $this->adapter->post("v1/webhook/register", ['urlHook' => $url]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Test Webhook URL
     *
     * @param string|null $url
     * @return array
     */
    public function testWebhookURL(string $url = null)
    {
        try {
            $request = $this->adapter->post("v1/webhook/test", ['urlHook' => $url]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }
    
    /**
     * Retry Webhook URL
     *
     * @param string|null $url
     * @return array
     */
    public function retryWebhookURL(string $webhookHistoryId = null)
    {
        try {
            $request = $this->adapter->post("v1/webhook/retry", ['webhookHistoryId' => $webhookHistoryId]);
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

        // Jika API memberikan response, gunakan itu
        if ($responseBody) {
            $errorData = json_decode($responseBody, true);
            $errorMessage = $errorData['messages'] ?? 'Terjadi kesalahan';
            return ResponseFormatter::formatErrorResponse($errorMessage, $statusCode);
        }

        // Jika tidak ada response, gunakan pesan bawaan exception
        return ResponseFormatter::formatErrorResponse($e->getMessage(), $statusCode);
    }
}
