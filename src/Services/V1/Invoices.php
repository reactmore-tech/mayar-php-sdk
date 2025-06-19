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
 * Invoices Service for Mayar Headless API V1
 *
 * Endpoint where you can get or create your RequestPayment,
 * creation, updating, Close, and re-open.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Invoices implements ServiceInterface
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
     * Create Invoice.
     *
     * Create a single payment request.
     *
     * @param array $data Required parameters:
     *  - 'name' (string) Customer's full name.
     *  - 'email' (string) Customer's email address.
     *  - 'mobile' (string) Customer's mobile phone number.
     *  - 'redirectUrl' (string) : "https://domain.com/redirect",
     *  - 'description' (string) : "kemana ini menjadi a",
     *  - 'expiredAt' (string) : "2024-02-29T09:41:09.401Z",
     *  - 'items' (array) Daftar item, masing-masing berisi:
     *      - 'quantity' (int) Jumlah barang.
     *      - 'rate' (int) Harga per unit.
     *      - 'description' (string) Deskripsi item.    
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function create(array $payload = [])
    {
        try {
            Validator::validateInquiryRequest($payload, ['name', 'email', 'mobile', 'redirectUrl', 'description', 'items']);
            $request = $this->adapter->post("hl/v1/invoice/create", [
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
     * Edit Invoice.
     *
     * Edit a invoice request.
     *
     * @param string $id Required parameters (string).
     * @param array $payload Required parameters:
     *  - 'redirectUrl' (string) : "https://domain.com/redirect",
     *  - 'description' (string) : "kemana ini menjadi a",
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function edit($id, array $payload = [])
    {
        try {
            Validator::validateSingleArgument($id, 'id');
            Validator::validateInquiryRequest($payload, ['redirectUrl', 'description']);
            $payload['id'] = $id;
            $request = $this->adapter->post("hl/v1/invoice/edit", [
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
            $request = $this->adapter->get('hl/v1/invoice', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Sort/Filter Invoice Data Page.
     *
     * This method fetches a list of Invoice from the API.
     *
     * @param string $status Required parameters (open/close).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getListFilter($data)
    {
        try {

            Validator::validateInquiryRequest($data, ['status']);
            $request = $this->adapter->get("hl/v1/invoice?status={$data['status']}", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Detail Invoice.
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
            Validator::validateSingleArgument($transactionId, 'id');
            $request = $this->adapter->get("hl/v1/invoice/{$transactionId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Change Status of Invoice.
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
            Validator::validateSingleArgument($transactionId, 'id');
            $request = $this->adapter->get("hl/v1/invoice/{$status}/{$transactionId}");
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
