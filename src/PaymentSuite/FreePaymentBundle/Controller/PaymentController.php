<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\FreePaymentBundle\Controller;

use PaymentSuite\FreePaymentBundle\Services\FreePaymentManager;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * PaymentController.
 */
class PaymentController
{
    private $freePaymentManager;
    private $successRedirectionRoute;
    private $paymentBridge;
    private $urlGenerator;
    private $orderPaymentSetter;
    private $kernel;

    /**
     * @var bool if set TRUE indicate that FreePayment is used to create an order.
     */
    private $createOrderWithFreePayment;

    public function __construct(
        FreePaymentManager $freePaymentManager,
        RedirectionRoute $successRedirectionRoute,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator,
        $orderPaymentSetter,
        $kernel,
        $createOrderWithFreePayment
    ) {
        $this->freePaymentManager = $freePaymentManager;
        $this->successRedirectionRoute = $successRedirectionRoute;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
        $this->orderPaymentSetter = $orderPaymentSetter;
        $this->kernel = $kernel;
        $this->createOrderWithFreePayment = $createOrderWithFreePayment;
    }

    /**
     * Free Payment execution.
     *
     * @return RedirectResponse
     */
    public function executeAction()
    {
        // check: freepayment solo in debug, o con amount == 0
        if ($this->paymentBridge->getAmount() != 0 && !$this->kernel->isDebug() && !$this->createOrderWithFreePayment) {
            throw new \Exception("Impossibile usare free payment con importo diverso da zero", 1);
        }

        $this
            ->freePaymentManager
            ->processPayment();

        $this->orderPaymentSetter->setPaymentInOrder('elcodi_plugin.free_payment.getter');

        $successUrl = $this
            ->urlGenerator
            ->generate(
                $this->successRedirectionRoute->getRoute(),
                $this->successRedirectionRoute->getRouteAttributes(
                    $this->paymentBridge->getOrderId()
                )
            );

        return new RedirectResponse($successUrl);
    }
}
