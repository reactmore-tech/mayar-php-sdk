<?php

namespace ReactMoreTech\MayarHeadlessAPI\Auth;

/**
 * Class APIToken
 * 
 * APIToken class is an implementation of the Auth interface for authenticated requests using API token.
 * 
 * @package ReactMoreTech\MayarHeadlessAPI\Auth
 */

class APIToken implements Auth
{
    /**
     * @var string The API token to be used for authentication
     */
    private $apiToken;

    /**
     * APIToken constructor.
     *
     * @param string $apiToken The API token to be used for authentication
     */
    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * Get the headers needed for API authentication
     *
     * @return array The headers needed for API authentication
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken
        ];
    }
}
