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
     * Retrieve a paginated list of product.
     *
     * This method fetches a list of product from the API. It supports optional
     * pagination parameters such as 'page' and 'pageSize'.
     *
     * @param array $data Optional parameters:
     *  - 'search' (string) Query to search Product.
     *  - 'page' (int) Page number.
     *  - 'pageSize' (int) Number of items per page.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getList(array $data = [])
    {
        try {
            Validator::validateArrayRequest($data);
            $request = $this->adapter->get('hl/v1/product', $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a list of product with type filter.
     *
     * This method fetches a list of product from the API. It supports optional
     * pagination parameters such as 'page' and 'pageSize'.
     *
     * @param array $data Required parameters:
     *  - 'event' (string) Event Type.
     *  Optional parameters:
     *  - 'page' (int) Page number.
     *  - 'pageSize' (int) Number of items per page.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function getListFilter(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['event']);
            $request = $this->adapter->get("hl/v1/product/type/{$data['event']}", $data);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get Product Detail by ID.
     *
     * Retrieves the details of a specific product by its unique identifier.
     *
     * @param string $couponId Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If validation fails or the request encounters an error.
     */
    public function getDetail(string $productId = null)
    {
        try {
            Validator::validateSingleArgument($productId, 'productId');
            $request = $this->adapter->get("hl/v1/product/{$productId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Change Status Product.
     *
     * @param array $data need array with field 'id', 'status' status (open/close).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function changeStatus(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['id', 'status']);
            $request = $this->adapter->get("hl/v1/product/{$data['status']}/{$data['id']}");
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
