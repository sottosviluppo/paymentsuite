<?php

namespace PaymentSuite\CashOnDeliveryBundle\Controller;

use PaymentSuite\CashOnDeliveryBundle\Services\CashOnDeliveryManager;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * PaymentController.
 */
class PaymentController
{
    /**
     * @var CashOnDeliveryManager
     *
     * Payment manager
     */
    private $cashOnDeliveryManager;

    /**
     * @var RedirectionRoute
     *
     * Redirection route for success
     */
    private $successRedirectionRoute;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    private $paymentBridge;

    /**
     * @var UrlGeneratorInterface
     *
     * Url generator
     */
    private $urlGenerator;

    private $orderPaymentSetter;

    /**
     * Construct.
     *
     * @param CashOnDeliveryManager  $cashOnDeliveryManager   Payment manager
     * @param RedirectionRoute       $successRedirectionRoute Success redirection route
     * @param PaymentBridgeInterface $paymentBridge           Payment bridge
     * @param UrlGeneratorInterface  $urlGenerator            Url generator
     */
    public function __construct(
        CashOnDeliveryManager $cashOnDeliveryManager,
        RedirectionRoute $successRedirectionRoute,
        PaymentBridgeInterface $paymentBridge,
        UrlGeneratorInterface $urlGenerator,
        $orderPaymentSetter
    ) {
        $this->cashOnDeliveryManager = $cashOnDeliveryManager;
        $this->successRedirectionRoute = $successRedirectionRoute;
        $this->paymentBridge = $paymentBridge;
        $this->urlGenerator = $urlGenerator;
        $this->orderPaymentSetter = $orderPaymentSetter;
    }

    /**
     * Payment execution.
     *
     * @return RedirectResponse
     */
    public function executeAction()
    {
        $this
            ->cashOnDeliveryManager
            ->processPayment();

        $this->orderPaymentSetter->setPaymentInOrder('elcodi_plugin.cash_on_delivery.getter');

        $redirectUrl = $this
            ->urlGenerator
            ->generate(
                $this->successRedirectionRoute->getRoute(),
                $this->successRedirectionRoute->getRouteAttributes(
                    $this->paymentBridge->getOrderId()
                )
            );

        return new RedirectResponse($redirectUrl);
    }
}
