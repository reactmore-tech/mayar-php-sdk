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
 * Request Payment Service for Mayar Headless API V1
 *
 * Endpoint where you can get or create your RequestPayment,
 * creation, updating, and re-open.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class RequestPayment implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Products constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Create Single Payment Request (Penagihan).
     *
     * Create a single payment request.
     *
     * @param array $data Required parameters:
     *  - 'name' (string) Customer's full name.
     *  - 'email' (string) Customer's email address.
     *  - 'amount' (integer) amount request payment.
     *  - 'mobile' (string) Customer's mobile phone number.
     *  - 'redirectUrl' (string) : "https://domain.com/redirect",
     *  - 'description' (string) : "kemana ini menjadi a",
     *  - 'expiredAt' (string) : "2024-02-29T09:41:09.401Z",
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function create(array $payload = [])
    {
        try {
            Validator::validateInquiryRequest($payload, ['name', 'email', 'amount', 'mobile', 'redirectUrl', 'description']);
            $request = $this->adapter->post("hl/v1/payment/create", [
                'json' => $payload
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Edit Single Payment Request (Penagihan).
     *
     * Edit a single payment request.
     *
     * @param string $transactionId Required parameters (string).
     * @param array $payload Required parameters:
     *  - 'name' (string) Customer's full name.
     *  - 'amount' (integer) amount request payment.
     *  - 'redirectUrl' (string) : "https://domain.com/redirect",
     *  - 'description' (string) : "kemana ini menjadi a",
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function edit($transactionId, array $payload = [])
    {
        try {
            Validator::validateSingleArgument($transactionId, 'id');
            Validator::validateInquiryRequest($payload, ['name', 'amount', 'redirectUrl', 'description']);
            $payload['transactionId'] = $transactionId;
            $request = $this->adapter->post("hl/v1/payment/edit", [
                'json' => $payload
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a list of Request Payment.
     *
     * Index Single Payment Request Page
     *
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getList(array $data = [])
    {
        try {
            $request = $this->adapter->get('hl/v1/payment', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Sort/Filter Single Payment Request Page.
     *
     * This method fetches a list of Payment Request from the API.
     *
     * @param string $status Required parameters (open/close).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getListFilter($data)
    {
        try {
           
            Validator::validateInquiryRequest($data, ['status']);
            $request = $this->adapter->get("hl/v1/payment?status={$data['status']}", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Request Payment Detail by ID.
     *
     * Detail Single Payment Request.
     *
     * @param string $transactionId Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function getDetail($transactionId = null)
    {
        try {
            Validator::validateSingleArgument($transactionId, 'transactionId');
            $request = $this->adapter->get("hl/v1/payment/{$transactionId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Change Status Payment.
     *
     * @param string $status Required parameters (open/close).
     * @param string $transactionId Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function changeStatus($status, $transactionId)
    {
        try {
            Validator::validateSingleArgument($status, 'status');
            Validator::validateSingleArgument($transactionId, 'transactionId');
            $request = $this->adapter->get("hl/v1/payment/{$status}/{$transactionId}");
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
