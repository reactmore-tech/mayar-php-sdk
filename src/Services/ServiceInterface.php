<?php

namespace ReactMoreTech\MayarHeadlessAPI\Services;

use ReactMoreTech\MayarHeadlessAPI\Adapter\AdapterInterface;
use stdClass;

/**
 * Interface ServiceInterface
 * @package ReactMoreTech\MayarHeadlessAPI\Services
 */
interface ServiceInterface
{
    /**
     * ServiceInterface constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter);

}
