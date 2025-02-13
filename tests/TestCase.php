<?php

namespace ReactMoreTech\MayarHeadlessAPI\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use ReactMoreTech\MayarHeadlessAPI\MayarProvider;

/**
 * Class TestCase
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
abstract class TestCase extends BaseTestCase
{
    protected $requestMock;

    // Token API yang digunakan untuk autentikasi
    protected $apiToken = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySWQiOiI4ZjY5OGIzZi0xODM4LTQ5ZjgtOWRmZC0zY2JmOGI1MjZlNzEiLCJhY2NvdW50SWQiOiI2YjQ3MWI4OC0wMmNhLTRiYjQtYmEyNC0wZDFjZWM1MmE2MDMiLCJjcmVhdGVkQXQiOiIxNzM5NDMyNDQ1MzY0Iiwicm9sZSI6ImRldmVsb3BlciIsInN1YiI6InJlYWN0bW9yZWNvbUBnbWFpbC5jb20iLCJuYW1lIjoiUmVhY3Rtb3JlIiwibGluayI6InJlYWN0LW1vcmUtNDk2OTUiLCJpc1NlbGZEb21haW4iOm51bGwsImlhdCI6MTczOTQzMjQ0NX0.X8qDGH8lOHDh8RJFDAHe4N68YcHaCoIwQemMFcqPu0a8D6F9_3o2HCCia9dv2oNDuLnX3crYM0HHUASSGZLkMfwjJodo7axLWfH8-q-ePdY8RX4EG_sIaalTCwzKqTB_9Nut5-QA9Cmv2TklA0eaM_ZmkChbX0miFU4wqZJfJEui8UGVQEOojb-fTRUd17DeVfVDIeOXX7-LKcJen6X5CtbBAG13PZ5dQCDZ1oUZYJDIDzDTou_gssBVGOBwUai_CuYpQZ_ngpxZ6AQ3N4EmfWAjWYQpR2LG2Dt-r4x6fahC-7_X3fgbNQwhC4ZWtHy5mgNXkZzKNiaDaYnirQq8iQ';

    public function setUp(): void
    {
        $this->requestMock = new MayarProvider([
            'apiToken' => $this->apiToken,
            'isProduction' => false,
        ]);

        $this->assertNotNull($this->requestMock->webhookServices());
    }

    public function testClientIsInitialized()
    {
        $this->assertNotNull($this->requestMock);
        $this->assertInstanceOf(MayarProvider::class, $this->requestMock);
    }
}
