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
 * Transaction Service for Mayar Headless API V1
 *
 * All you transaction history, transaction detail, unapid transaction here
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Transaction implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Transaction Service constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Balance Account.
     *
     * @return array The formatted API response containing balance account.
     * @throws \Exception If the request fails.
     */
    public function getBalance()
    {
        try {
            $request = $this->adapter->get('hl/v1/balance');
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a paginated list of Transaction.
     *
     * This method fetches a list of transaction Paid from the API. It supports optional
     * pagination parameters such as 'page' and 'pageSize'.
     *
     * @param array $payload Exyta parameters:
     *  - 'page' (int) Page number.
     *  - 'pageSize' (int) Number of items per page.
     *  - 'startAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'endAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'status' (string) List of status: [paid, settled].
     *  - 'type' (string) List of types: [generic_link, payment_request, payme, invoice, bundling, physical_product, event, webinar, digital_product, coaching, course, cohort_based, fundraising, ebook, podcast, audiobook, membership].
     *  - 'customerId' (string) The Customer ID associated with the Transaction.
     *  - 'fields' (string) For field projection.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function paid(array $payload = [])
    {
        try {
            Validator::validateArrayRequest($payload);
            $request = $this->adapter->get("hl/v1/transactions", $payload);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a paginated list of Transaction Unpaid.
     *
     * This method fetches a list of transactions Unpaid from the API. It supports optional
     * pagination parameters such as 'page' and 'pageSize'.
     *
     * @param array $payload Exyta parameters:
     *  - 'page' (int) Page number.
     *  - 'pageSize' (int) Number of items per page.
     *  - 'startAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'endAt' (string|null) Expiration date in ISO 8601 format (e.g., "2023-05-05T09:06:14.933Z").
     *  - 'status' (string) List of status: [active, expired].
     *  - 'customerId' (string) The Customer ID associated with the Transaction.
     *  - 'fields' (string) For field projection.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function unpaid(array $payload = [])
    {
        try {
            Validator::validateArrayRequest($payload);
            $request = $this->adapter->get("hl/v1/transactions/unpaid", $payload);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Static QRCode.
     *
     * This fiture only available for user who joined before 8 mei 2023
     *
     * @param string $amount Required parameters (int).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getStaticQRCode(int $amount)
    {
        try {
            Validator::validateSingleArgument($amount, 'amount');
            $request = $this->adapter->get("hl/v1/qrcode/static", [
                'json' => ['amount' => $amount]
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Dynamic QRCode.
     *
     * This fiture only available for user who joined before 8 mei 2023
     *
     * @param string $amount Required parameters (int).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getDynamicQRCode(int $amount)
    {
        try {
            Validator::validateSingleArgument($amount, 'amount');
            $request = $this->adapter->post("hl/v1/qrcode/create", [
                'json' => ['amount' => $amount]
            ]);
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
