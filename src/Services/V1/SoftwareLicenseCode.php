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
 * Software License Code Service for Mayar Headless API V1
 *
 * This service provides functionality for verifying software licenses
 * issued through the Mayar platform. It allows developers to check
 * the validity and status of a given license.
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Services\V1
 */
class SoftwareLicenseCode implements ServiceInterface
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
     * Software License Code Service constructor.
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
     * This endpoint is used to verify the validity of a software license
     * by providing a license code and product ID.
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
            $request = $this->adapter->post("software/v1/license/verify", ['json' => $data]);
            return ResponseFormatter::formatResponse($request->getBody());
        } catch (BaseException $e) {
            return ResponseFormatter::formatErrorResponse($e->getMessage(), $e->getCode());
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

}
