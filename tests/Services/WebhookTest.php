<?php

namespace ReactMoreTech\MayarHeadlessAPI\Tests\Services;

use ReactMoreTech\MayarHeadlessAPI\Tests\TestCase;
use ReactMoreTech\MayarHeadlessAPI\Services\V1\WebhookServices;
use ReactMoreTech\MayarHeadlessAPI\Formatter\Response;

/**
 * Test cases for Webhook Services.
 */
class WebhookTest extends TestCase
{
    /**
     * Test retrieving webhook history.
     *
     * @return void
     */
    public function testGetWebhookHistory(): void
    {
        $result = $this->requestMock->webhookServices()->getWebhookHistory();

        // Ensure the response is an instance of Response
        $this->assertInstanceOf(Response::class, $result);

        // Convert response to array format
        $responseArray = $result->toArray();

        // Validate response structure
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
        $this->assertArrayHasKey('status_code', $responseArray);

        // Ensure success is true
        $this->assertTrue($responseArray['success']);
    }

    /**
     * Test setting a valid webhook URL.
     *
     * @return void
     */
    public function testSetWebhookURL(): void
    {
        $validURL = "https://example.com/webhook";

        $result = $this->requestMock->webhookServices()->setWebhookURL($validURL);

        // Ensure the response is an instance of Response
        $this->assertInstanceOf(Response::class, $result);

        $responseArray = $result->toArray();

        // Validate response structure
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('data', $responseArray);
        $this->assertArrayHasKey('status_code', $responseArray);

        // Ensure request was successful
        $this->assertTrue($responseArray['success']);
    }

    /**
     * Test setting an invalid webhook URL.
     *
     * @return void
     */
    public function testSetInvalidWebhookURL(): void
    {
        $invalidURL = "invalid_url";

        $result = $this->requestMock->webhookServices()->setWebhookURL($invalidURL);

        // Ensure the response is an instance of Response
        $this->assertInstanceOf(Response::class, $result);

        $responseArray = $result->toArray();

        // Validate response structure
        $this->assertArrayHasKey('success', $responseArray);
        $this->assertArrayHasKey('message', $responseArray);
        $this->assertArrayHasKey('status_code', $responseArray);

        // Ensure success is false for an invalid URL
        $this->assertFalse($responseArray['success']);
        $this->assertEquals(400, $responseArray['status_code']);
    }
}
