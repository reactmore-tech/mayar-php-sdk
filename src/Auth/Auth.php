<?php

namespace ReactMoreTech\MayarHeadlessAPI\Auth;

/**
 * The interface for all MayarHeadlessAPI authentication implementations.
 */
interface Auth
{
    /**
     * Returns an array of headers required for authentication.
     *
     * @return array The headers required for authentication.
     */
    public function getHeaders(): array;
}
