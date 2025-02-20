<?php

namespace ReactMoreTech\MayarHeadlessAPI;

use ReactMoreTech\MayarHeadlessAPI\Adapter\Guzzle;
use ReactMoreTech\MayarHeadlessAPI\Auth\APIToken;
use ReactMoreTech\MayarHeadlessAPI\Services\ServiceInterface;

/**
 * Class MayarProvider
 *
 * Provides access to different API services dynamically based on the specified version.
 * 
 * @package ReactMoreTech\MayarHeadlessAPI
 */
class MayarProvider
{
    use MayarTraits;

    /**
     * HTTP adapter for API requests.
     *
     * @var Guzzle|null
     */
    private $adapter;

    /**
     * API authentication token.
     *
     * @var APIToken|null
     */
    private $auth;

    /**
     * Webhook security token.
     *
     * @var string|null
     */
    private $webhookToken;

    /**
     * Determines whether the environment is production.
     *
     * @var bool
     */
    private $isProduction;

    /**
     * API version.
     *
     * @var string
     */
    private $version;

    /**
     * Constructor.
     *
     * @param array $options Configuration options.
     * - `isProduction` (bool, optional): Defines if the API should run in production mode. Defaults to `true`.
     * - `version` (string, optional): API version, either "V1" or "V2". Defaults to "V1".
     * - `apiToken` (string, optional): API authentication token.
     * - `webhookToken` (string, optional): Webhook security token.
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

    /**
     * Sets the API version.
     *
     * @param string $version The API version ("V1" or "V2").
     * @throws \InvalidArgumentException If the version is invalid.
     */
    public function setVersion(string $version): void
    {
        $allowedVersions = ['V1', 'V2'];
        if (!in_array($version, $allowedVersions, true)) {
            throw new \InvalidArgumentException("Invalid version: {$version}. Allowed values are V1 or V2.");
        }
        $this->version = $version;
    }

    /**
     * Sets the API authentication token.
     *
     * @param string $token The API token.
     * @throws \Exception If the token is empty.
     */
    public function apiToken(string $token): void
    {
        if (empty($token)) {
            throw new \Exception("API Token cannot be empty!");
        }
        
        $this->auth = new APIToken($token);
        $this->adapter = new Guzzle($this->auth, $this->isProduction);
    }

    /**
     * Sets the webhook token for security validation.
     *
     * @param string $token The webhook token.
     */
    public function webhookToken(string $token): void
    {
        $this->webhookToken = $token;
    }

    /**
     * Retrieves the webhook token.
     *
     * @return string|null The webhook token, or `null` if not set.
     */
    public function getWebhookToken(): ?string
    {
        return $this->webhookToken;
    }

    /**
     * Sets the environment mode.
     *
     * @param bool $isProduction `true` for production mode, `false` for sandbox mode.
     */
    public function setProduction(bool $isProduction): void
    {
        $this->isProduction = $isProduction;
        // If API Token is already set, update the adapter to reflect the mode change.
        if ($this->auth !== null) {
            $this->adapter = new Guzzle($this->auth, $this->isProduction);
        }
    }

    /**
     * Handles dynamic method calls for accessing API services.
     *
     * @param string $name The name of the service (e.g., 'customer', 'order', 'discountCoupon').
     * @param array $arguments Arguments passed to the service constructor.
     * @return ServiceInterface Returns an instance of the requested service.
     *
     * @throws \BadMethodCallException If the requested service does not exist.
     */
    public function __call($name, $arguments)
    {
        $className = "\\ReactMoreTech\\MayarHeadlessAPI\\Services\\{$this->version}\\" . ucfirst($name);

        if (class_exists($className)) {
            return new $className($this->adapter);
        }

        throw new \BadMethodCallException("Service {$name} not found in version {$this->version}.");
    }
}
