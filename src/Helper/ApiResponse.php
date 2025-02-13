<?php

namespace ReactMoreTech\MayarHeadlessAPI\Helper;

use JsonSerializable;

/**
 * API Response Wrapper Class
 *
 * @package ReactMoreTech\MayarHeadlessAPI\Helper
 */
class ApiResponse implements JsonSerializable
{
    /**
     * @var array $response The response data.
     */
    private array $response;

    /**
     * Constructor.
     *
     * @param array $response Formatted response array.
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Convert response to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * Implement jsonSerialize to automatically return JSON.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->response;
    }

    /**
     * Convert response to JSON.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }

    /**
     * Magic method for accessing array keys like an object.
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get(string $key): mixed
    {
        return $this->response[$key] ?? null;
    }

    /**
     * Magic method for converting object to string (default JSON).
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
