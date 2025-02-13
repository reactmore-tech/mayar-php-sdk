<?php

namespace ReactMoreTech\MayarHeadlessAPI;

use ReactMoreTech\MayarHeadlessAPI\Adapter\Guzzle;
use ReactMoreTech\MayarHeadlessAPI\Auth\APIToken;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;

/**
 * Class MayarProvider
 * @package ReactMoreTech\MayarHeadlessAPI
 */
class MayarProvider
{
    /**
     * @var Guzzle|null
     */
    private $adapter;

    /**
     * @var APIToken|null
     */
    private $auth;

    /**
     * @var string|null
     */
    private $webhookToken;

    /**
     * @var bool
     */
    private $isProduction;

    /**
     * @var string
     */
    private $version;

    /**
     * MayarProvider constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->isProduction = $options['isProduction'] ?? true;
        $this->version = $options['version'] ?? 'V1';

        if (!empty($options['apiToken'])) {
            $this->apiToken($options['apiToken']);
        }

        if (!empty($options['webhookToken'])) {
            $this->webhookToken($options['webhookToken']);
        }
    }

    public function setVersion(string $version): void
    {
        $allowedVersions = ['V1', 'V2'];
        if (!in_array($version, $allowedVersions, true)) {
            throw new \InvalidArgumentException("Versi tidak valid: {$version}. Gunakan V1 atau V2.");
        }
        $this->version = $version;
    }

    /**
     * Set API Token
     * @param string $token
     * @return void
     */
    public function apiToken(string $token): void
    {
        if (empty($token)) {
            throw new \Exception("API Token tidak boleh kosong!");
        }
        
        $this->auth = new APIToken($token);
        $this->adapter = new Guzzle($this->auth, $this->isProduction);
    }

    /**
     * Set Webhook Token
     * @param string $token
     * @return void
     */
    public function webhookToken(string $token): void
    {
        $this->webhookToken = $token;
    }

    /**
     * Get Webhook Token
     * @return string|null
     */
    public function getWebhookToken(): ?string
    {
        return $this->webhookToken;
    }

    /**
     * Set Production Mode
     * @param bool $isProduction
     * @return void
     */
    public function setProduction(bool $isProduction): void
    {
        $this->isProduction = $isProduction;
        // Jika sudah ada API Token, perbarui adapter agar sesuai dengan mode terbaru
        if ($this->auth !== null) {
            $this->adapter = new Guzzle($this->auth, $this->isProduction);
        }
    }


    public function __call($name, $arguments)
    {
        $className = "\\ReactMoreTech\\MayarHeadlessAPI\\Services\\{$this->version}\\" . ucfirst($name);

        if (class_exists($className)) {
            return new $className($this->adapter);
        }

        throw new \BadMethodCallException("Service {$name} Not Found on {$this->version}.");
    }
}
