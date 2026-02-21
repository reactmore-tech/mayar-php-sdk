<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\V1;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use ReactMoreTech\MayarHeadlessAPI\Formatter\ResponseFormatter;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\BodyAccessorTrait;
use ReactMoreTech\MayarHeadlessAPI\Services\Traits\HandleExceptionTrait;
use ReactMoreTech\MayarHeadlessAPI\Helper\Validations\Validator;
use ReactMoreTech\MayarHeadlessAPI\Exceptions\BaseException;
use GuzzleHttp\Exception\RequestException;

/**
 * SaaS Membership License Service for Mayar Headless API V1
 *
 * This service handles the verification of software license codes
 * for SaaS Membership in Mayar's platform. The API ensures that software
 * vendors can manage licensing seamlessly without handling payment processing,
 * unique license code generation, validation, and expiration tracking manually.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class SaaSMembershipLicense implements ServiceInterface
{
    use BodyAccessorTrait;
    use HandleExceptionTrait;

    /**
     * HTTP adapter for API communication.
     *
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * SaaS Membership License Service constructor.
     *
     * @param AdapterInterface $adapter HTTP adapter instance.
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Verify a software license.
     *
     * This method sends a request to verify a software license code against
     * a product ID in the Mayar system. If the license exists and is valid,
     * the response will contain its details, including status, expiration date,
     * and associated customer information.
     *
     * @param array $data Required parameters:
     *  - 'licenseCode' (string) The license code to verify.
     *  - 'productId' (string) The product ID associated with the license.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function verifyLicense(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['licenseCode', 'productId']);
            $request = $this->adapter->post("saas/v1/license/verify", ['json' => $data]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Activate License.
     *
     * This endpoint is used to activate a license by providing the license code and product ID.
     * By performing this action, the status of the license code will change to ACTIVE.
     * 
     * @param array $data Required parameters:
     *  - 'licenseCode' (string) The license code to verify.
     *  - 'productId' (string) The product ID associated with the license.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function activateLicense(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['licenseCode', 'productId']);
            $request = $this->adapter->post("saas/v1/license/activate", ['json' => $data]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Deactivated License.
     *
     * This endpoint is used to deactivate a license by providing the license code and product ID.
     * By performing this action, the status of the license code will change to INACTIVE.
     *
     * @param array $data Required parameters:
     *  - 'licenseCode' (string) The license code to verify.
     *  - 'productId' (string) The product ID associated with the license.
     * @return ResponseFormatter Formatted API response.
     * @throws \Exception If the request fails.
     */
    public function deactivateLicense(array $data = [])
    {
        try {
            Validator::validateInquiryRequest($data, ['licenseCode', 'productId']);
            $request = $this->adapter->post("saas/v1/license/deactivate", ['json' => $data]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

}
