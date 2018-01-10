<?php

namespace PaymentSuite\CashOnDeliveryBundle\Services;

use PaymentSuite\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;

/**
 * Cash On Delivery manager.
 */
class CashOnDeliveryManager
{
    /**
     * @var CashOnDeliveryMethodFactory
     *
     * Cash On Delivery method factory
     */
    private $methodFactory;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    private $paymentEventDispatcher;

    /**
     * Construct method for bankwire manager.
     *
     * @param CashOnDeliveryMethodFactory  $methodFactory    Cash On Delivery method factory
     * @param PaymentBridgeInterface $paymentBridge          Payment Bridge
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     */
    public function __construct(
        CashOnDeliveryMethodFactory $methodFactory,
        PaymentBridgeInterface $paymentBridge,
        PaymentEventDispatcher $paymentEventDispatcher
    ) {
        $this->methodFactory = $methodFactory;
        $this->paymentBridge = $paymentBridge;
        $this->paymentEventDispatcher = $paymentEventDispatcher;
    }

    /**
     * Tries to process a payment through Bankwire.
     *
     * @return CashOnDeliveryManager Self object
     *
     * @throws PaymentOrderNotFoundException
     */
    public function processPayment()
    {
        $cashOnDeliveryMethod = $this
            ->methodFactory
            ->create();

        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge.
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderLoad(
                $this->paymentBridge,
                $cashOnDeliveryMethod
            );

        /**
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Order exists right here.
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderCreated(
                $this->paymentBridge,
                $cashOnDeliveryMethod
            );

        /**
         * Payment paid done.
         *
         * Paid process has ended ( No matters result )
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderDone(
                $this->paymentBridge,
                $cashOnDeliveryMethod
            );

        return $this;
    }

    /**
     * Validates payment, given an Id of an existing order.
     *
     * @param int $orderId Id from order to validate
     *
     * @return CashOnDeliveryManager self Object
     *
     * @throws PaymentOrderNotFoundException
     */
    public function validatePayment($orderId)
    {
        /**
         * Loads order to validate.
         */
        $this
            ->paymentBridge
            ->findOrder($orderId);

        /**
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Payment paid successfully.
         *
         * Paid process has ended successfully
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderSuccess(
                $this->paymentBridge,
                $this
                    ->methodFactory
                    ->create()
            );

        return $this;
    }

    /**
     * Decline payment, given an Id of an existing order.
     *
     * @param int $orderId Id from order to decline
     *
     * @return CashOnDeliveryManager self Object
     *
     * @throws PaymentOrderNotFoundException
     */
    public function declinePayment($orderId)
    {
        /**
         * Loads order to validate.
         */
        $this
            ->paymentBridge
            ->findOrder($orderId);

        /**
         * Order Not found Exception must be thrown just here.
         */
        if (!$this->paymentBridge->getOrder()) {
            throw new PaymentOrderNotFoundException();
        }

        /**
         * Payment failed.
         *
         * Paid process has ended with failure
         */
        $this
            ->paymentEventDispatcher
            ->notifyPaymentOrderFail(
                $this->paymentBridge,
                $this
                    ->methodFactory
                    ->create()
            );

        return $this;
    }
}
