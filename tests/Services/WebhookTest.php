<?php

namespace ReactMoreTech\MayarHeadlessAPI\Tests\Services;

use ReactMoreTech\MayarHeadlessAPI\Tests\TestCase;
use ReactMoreTech\MayarHeadlessAPI\Services\V1\WebhookServices;

class WebhookTest extends TestCase
{
    public function testGetWebhookHistory()
    {
        $result = $this->requestMock->webhookServices()->getWebhookHistory();
    
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('status_code', $result);
    
        $this->assertTrue($result['success']); // Inilah yang gagal
    }
    

    public function testSetWebhookURL()
    {
        $validURL = "https://example.com/webhook";

        $result = $this->requestMock->webhookServices()->setWebhookURL($validURL);

        // Pastikan response mengandung data yang benar
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('status_code', $result);

        // Cek apakah request berhasil
        $this->assertTrue($result['success']);
    }

    public function testSetInvalidWebhookURL()
    {
        $invalidURL = "invalid_url";

        $result = $this->requestMock->webhookServices()->setWebhookURL($invalidURL);

        // Pastikan response error memiliki format yang benar
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayHasKey('status_code', $result);

        // Pastikan success false untuk invalid URL
        $this->assertFalse($result['success']);
        $this->assertEquals(400, $result['status_code']);
    }
}
