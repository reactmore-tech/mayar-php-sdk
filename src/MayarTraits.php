<?php

namespace ReactMoreTech\MayarHeadlessAPI;

trait MayarTraits
{
    /**
     * Provides access to the Customer service.
     *
     * @return \ReactMoreTech\MayarHeadlessAPI\Services\V1\Customer
     */
    public function customer()
    {
        return $this->__call('customer', []);
    }

    /**
     * Provides access to the DiscountCoupon service.
     *
     * @return \ReactMoreTech\MayarHeadlessAPI\Services\V1\DiscountCoupon
     */
    public function discountCoupon()
    {
        return $this->__call('discountCoupon', []);
    }

    /**
     * Provides access to the Order service.
     *
     * @return \ReactMoreTech\MayarHeadlessAPI\Services\V1\Installment
     */
    public function installment()
    {
        return $this->__call('installment', []);
    }
}
