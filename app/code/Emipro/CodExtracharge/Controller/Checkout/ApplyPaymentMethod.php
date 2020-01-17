<?php

namespace Emipro\CodExtracharge\Controller\Checkout;

class ApplyPaymentMethod extends \Magento\Framework\App\Action\Action
{

    protected $resultForwardFactory;

    protected $layoutFactory;

    protected $cart;

    /**
     * [__construct description]
     * @param \Magento\Framework\App\Action\Context               $context              [description]
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory [description]
     * @param \Magento\Framework\View\LayoutFactory               $layoutFactory        [description]
     * @param \Magento\Checkout\Model\Cart                        $cart                 [description]
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->layoutFactory = $layoutFactory;
        $this->cart = $cart;

        parent::__construct($context);
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        $pMethod = $this->getRequest()->getParam('payment_method');

        $quote = $this->cart->getQuote();

        $quote->getPayment()->setMethod($pMethod['method']);

        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();
        $quote->save();
    }
}
