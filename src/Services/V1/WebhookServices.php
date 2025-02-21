<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Formatter\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\BaseException;
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
     * Retrieve a paginated list of Webhook History.
     *
     * This method fetches a list of Webhook History from the API. It supports optional
     * pagination parameters such as 'page' and 'pageSize'.
     *
     * @param array $payload Exyta parameters:
     *  - 'page' (int) Page number.
     *  - 'pageSize' (int) Number of items per page.
     *  - 'startAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'endAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'status' (string) List of status: [SUCCESS, FAILED].
     *  - 'type' (string) List of types: [payment.received].
     *  - 'urlDestination' (string) The Customer ID associated with the Transaction.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getWebhookHistory(array $payload = [])
    {
        try {
            Validator::validateArrayRequest($payload);
            $request = $this->adapter->get('hl/v1/webhook/history', $payload);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Register a webhook URL.
     *
     * @param string $url Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function setWebhookURL(string $url = null)
    {
        try {
            Validator::validateSingleArgument($url, 'urlHook');
            $request = $this->adapter->post("hl/v1/webhook/register", ['urlHook' => $url]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Test a registered webhook URL.
     *
     * @param string $url Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function testWebhookURL(string $url = null)
    {
        try {
            Validator::validateSingleArgument($url, 'urlHook');
            $request = $this->adapter->post("hl/v1/webhook/test", ['urlHook' => $url]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retry a failed webhook event.
     *
     * @param string $webhookHistoryId Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function retryWebhookURL(string $webhookHistoryId = null)
    {
        try {
            Validator::validateSingleArgument($webhookHistoryId, 'webhookHistoryId');
            $request = $this->adapter->post("hl/v1/webhook/retry", ['webhookHistoryId' => $webhookHistoryId]);
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
