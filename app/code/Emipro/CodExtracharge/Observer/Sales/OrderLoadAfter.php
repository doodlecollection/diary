<?php

namespace Emipro\CodExtracharge\Observer\Sales;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class OrderLoadAfter implements ObserverInterface
{
    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(OrderExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getOrder();
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->getOrderExtensionDependency();
        }
        $extensionAttributes = $this->extensionFactory->create();

        // echo "<pre>";
        // print_r($extensionAttributes);
        // exit;
        if ($extensionAttributes !== null) {
            $attr = $order->getData('codcharge_fee_name');
            echo $attr;
            exit;
            $extensionAttributes->setCodchargeFeeName($attr);
            $order->setExtensionAttributes($extensionAttributes);
            $attr = $order->getData('codcharge_fee');
            $extensionAttributes->setCodchargeFee($attr);
            $order->setExtensionAttributes($extensionAttributes);
        }
    }
    private function getOrderExtensionDependency()
    {
        $orderapi = \Magento\Framework\App\ObjectManager::getInstance();
        $orderExtension = $orderapi->get('\Magento\Sales\Api\Data\OrderExtension');
        return $orderExtension;
    }
}
