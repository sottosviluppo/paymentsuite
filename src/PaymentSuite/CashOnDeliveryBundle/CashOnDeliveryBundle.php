<?php

namespace PaymentSuite\CashOnDeliveryBundle;

use Mmoreram\SymfonyBundleDependencies\DependentBundleInterface;
use PaymentSuite\CashOnDeliveryBundle\DependencyInjection\CashOnDeliveryExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Cash On Delivery payment bundle.
 */
class CashOnDeliveryBundle extends Bundle implements DependentBundleInterface
{
    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface The container extension
     */
    public function getContainerExtension()
    {
        return new CashOnDeliveryExtension();
    }

    /**
     * Return all bundle dependencies.
     *
     * Values can be a simple bundle namespace or its instance
     *
     * @return array Bundle instances
     */
    public static function getBundleDependencies(KernelInterface $kernel)
    {
        return [
            'PaymentSuite\PaymentCoreBundle\PaymentCoreBundle',
        ];
    }
}
