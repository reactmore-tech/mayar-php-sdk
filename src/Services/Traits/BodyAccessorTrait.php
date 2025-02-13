<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services\Traits;

/**
 * Trait BodyAccessorTrait
 * Trait that provides a method to access request body in classes that implement this trait.
 * @package ReactMoreTech\MayarHeadlessAPI\Services\Traits
 */
trait BodyAccessorTrait
{
    private $body;

    /**
     * Gets the request body.
     *
     * @return mixed The request body.
     */
    public function getBody()
    {
        return $this->body;
    }
}
