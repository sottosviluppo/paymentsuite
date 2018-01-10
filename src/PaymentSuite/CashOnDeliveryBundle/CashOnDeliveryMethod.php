<?php

namespace PaymentSuite\CashOnDeliveryBundle;

use PaymentSuite\PaymentCoreBundle\PaymentMethodInterface;

/**
 * CashOnDeliveryMethod class.
 */
final class CashOnDeliveryMethod implements PaymentMethodInterface
{
    /**
     * Get Bankwire method name.
     *
     * @return string Payment name
     */
    public function getPaymentName()
    {
        return 'CashOnDelivery';
    }
}
