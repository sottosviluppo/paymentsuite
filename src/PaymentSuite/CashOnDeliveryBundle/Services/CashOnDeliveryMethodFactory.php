<?php

namespace PaymentSuite\CashOnDeliveryBundle\Services;

use PaymentSuite\CashOnDeliveryBundle\CashOnDeliveryMethod;
use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * Class CashOnDeliveryMethodFactory.
 */
class CashOnDeliveryMethodFactory
{
    /**
     * Create new PaymentMethodInterface instance.
     *
     * @return PaymentMethodInterface New instance
     */
    public function create()
    {
        return new CashOnDeliveryMethod();
    }
}
