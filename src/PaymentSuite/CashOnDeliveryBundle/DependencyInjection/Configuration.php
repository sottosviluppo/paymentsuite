<?php

namespace PaymentSuite\CashOnDeliveryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

use PaymentSuite\PaymentCoreBundle\DependencyInjection\Abstracts\AbstractPaymentSuiteConfiguration;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration extends AbstractPaymentSuiteConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cash_on_delivery');

        $rootNode
            ->children()
            ->append($this->addRouteConfiguration('payment_success'))
            ->end();

        return $treeBuilder;
    }
}
