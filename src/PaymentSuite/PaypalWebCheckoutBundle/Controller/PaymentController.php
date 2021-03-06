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

namespace PaymentSuite\PaypalWebCheckoutBundle\Controller;

use PaymentSuite\PaypalWebCheckoutBundle\Services\PaypalWebCheckoutManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class PaymentController.
 */
class PaymentController
{
    /**
     * @var PaypalWebCheckoutManager
     *kernel
     * PaypalWebCheckout manager
     */
    private $paypalWebCheckoutManager;

    /**
     * @var EngineInterface
     *
     * Engine
     */
    private $engine;

    private $orderPaymentSetter;

    /**
     * Construct.
     *
     * @param PaypalWebCheckoutManager $paypalWebCheckoutManager PaypalWebCheckout manager
     * @param EngineInterface          $engine                   Engine
     */
    public function __construct(
        PaypalWebCheckoutManager $paypalWebCheckoutManager,
        EngineInterface $engine,
        $orderPaymentSetter
    ) {
        $this->paypalWebCheckoutManager = $paypalWebCheckoutManager;
        $this->engine = $engine;
        $this->orderPaymentSetter = $orderPaymentSetter;
    }

    /**
     * Payment execution.
     *
     * @return Response
     */
    public function executeAction()
    {
        /**
         * The execute action will generate the Paypal web
         * checkout form before redirecting.
         */
        $formView = $this
            ->paypalWebCheckoutManager
            ->generatePaypalForm();

        $this->orderPaymentSetter->setPaymentInOrder('elcodi_plugin.paypal_web_checkout.getter');

        $data = $this
            ->engine
            ->render('PaypalWebCheckoutBundle:Payment:process.html.twig', [
                'form' => $formView,
            ]);

        return new Response($data);
    }
}
