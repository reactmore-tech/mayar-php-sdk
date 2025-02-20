<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Helper\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\InvalidContentType;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\MissingArguements;
use GuzzleHttp\Exception\RequestException;

/**
 * Installment Service for Mayar Headless API V1
 *
 * Provides functionalities for managing Installment,
 * creation, and Show detail installment data.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class Installment implements ServiceInterface
{
    use BodyAccessorTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Installment Service constructor.
     *
     * Initializes the Customer service with the provided HTTP adapter.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get Installment Detail by ID.
     *
     * Retrieves the details of a specific Installmentby its unique identifier. 
     *
     * @param string $installmentId Required parameters (string).
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function detail(string $installmentId)
    {
        try {
            Validator::validateSingleArgument($installmentId, 'installmentId');
            $request = $this->adapter->get("hl/v1/installment/{$installmentId}");
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (MissingArguements $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), 400);
        } catch (InvalidContentType $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), 400);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create a new installment.
     *
     * Generates a installment for new customers with customizable properties,
     *
     * @param array $payload Required parameters:
     *  - 'email' (string) Email Customer.
     *  - 'mobile' (string) Mobile Phone Customer:
     *  - 'name' (string) Name Customer:
     *  - 'amount' (int) Amount Installment:
     *  - 'installment' (array) installment details:
     *    - 'description' (string) Description Installment".
     *    - 'interest' (int) interest percetage".
     *    - 'tenure' (int) installment tenor".
     *    - 'dueDate' (int) Installment Due Date".
     *
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function create(array $payload)
    {
        try {
            Validator::validateCreateInstallment($payload);
            $request = $this->adapter->post("hl/v1/installment/create", [
                'json' => $payload
            ]);
            return ResponseFormatter::formatResponse($request->getBody());
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
