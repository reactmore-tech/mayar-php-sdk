<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\MainValidator;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use GuzzleHttp\Exception\RequestException;

/**
 * Product Service for Mayar Headless API V1
 *
 * Endpoint where you can get or create your products,
 * creation, updating, and re-open.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Products implements ServiceInterface
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
     * Retrieve a list of product.
     *
     * @param array $data Contains optional parameters such as 'page', 'pageSize', search.
     * @return array The formatted API response containing prodcut data.
     * @throws \Exception If the request fails.
     */
    public function getList(array $data = [])
    {
        try {
            MainValidator::validateContentType($data);
            $request = $this->adapter->get('hl/v1/product', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a list of product with type filter.
     *
     * @param array $data Contains optional parameters such as 'page', dan 'pageSize'.
     * @return array The formatted API response containing prodcut data.
     * @throws \Exception If the request fails.
     */
    public function getListFilter(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['event']);
            $request = $this->adapter->get("hl/v1/product/type/{$data['event']}", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a list of product with type filter.
     *
     * @param array $data Contains optional parameters such as 'page', dan 'pageSize'.
     * @return array The formatted API response containing prodcut data.
     * @throws \Exception If the request fails.
     */
    public function getDetail(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['id']);
            $request = $this->adapter->get("hl/v1/product/{$data['id']}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Change Status Product.
     *
     * @param array $data need array with field 'id', 'status' status (open/close).
     * @return array The formatted API response containing prodcut data.
     * @throws \Exception If the request fails.
     */
    public function changeStatus(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['id', 'status']);
            $request = $this->adapter->get("hl/v1/product/{$data['status']}/{$data['id']}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Handle API request exceptions.
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
