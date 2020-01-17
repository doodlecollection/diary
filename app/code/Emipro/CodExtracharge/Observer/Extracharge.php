<?php
namespace Emipro\CodExtracharge\Observer;

use Magento\Framework\Event\ObserverInterface;

class Extracharge implements ObserverInterface
{
    protected $scopeConfig;

    /**
     * [__construct description]
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig [description]
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * [execute description]
     * @param  \Magento\Framework\Event\Observer $observer [description]
     * @return [type]                                      [description]
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();
        if ($quote->getPayment()->getMethod() == "cashondelivery") {
            $service_charge = $quote->getCodchargeFee();
            $base_service_charge = $quote->getCodchargeBaseFee();
            $name = $quote->getCodchargeFeeName();
            $applyPro = $quote->getCodAppliedRule();

            $order->setCodchargeFee($service_charge);
            $order->setCodchargeBaseFee($base_service_charge);
            $order->setCodchargeFeeName($name);
            $order->setCodAppliedRule($applyPro);
            $order->setGrandTotal($order->getGrandTotal());
            $order->setBaseGrandTotal($order->getBaseGrandTotal());
        }
    }
}
