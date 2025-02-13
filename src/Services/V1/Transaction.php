<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
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
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Paid data transactions.
     *
     * @param array $data Contains optional parameters such as 'page' and 'pageSize'.
     * @return array The formatted API response containing customer data.
     * @throws \Exception If the request fails.
     */
    public function paid(array $data = [])
    {
        try {
            $request = $this->adapter->get("hl/v1/transactions", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * get Unpaid data transactions.
     *
     * @param array $data Contains optional parameters such as 'page' and 'pageSize'.
     * @return array The formatted API response containing customer data.
     * @throws \Exception If the request fails.
     */
    public function unpaid(array $data = [])
    {
        try {
            $request = $this->adapter->get("hl/v1/transactions/unpaid", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Static QRCode.
     *
     * This fiture only available for user who joined before 8 mei 2023
     *
     * @return object The formatted API response containing customer data.
     * @throws \Exception If the request fails.
     */
    public function getStaticQRCode(int $amount)
    {
        try {
            $request = $this->adapter->get("hl/v1/qrcode/static", [
                'json' => ['amount' => $amount]
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Dynamic QRCode.
     *
     * This fiture only available for user who joined before 8 mei 2023
     *
     * @return object The formatted API response containing customer data.
     * @throws \Exception If the request fails.
     */
    public function getDynamicQRCode(int $amount)
    {
        try {
            $request = $this->adapter->post("hl/v1/qrcode/create", [
                'json' => ['amount' => $amount]
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle API request exceptions.
     *
     * @param RequestException $e The caught exception.
     * @return object Formatted error response with status code.
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

