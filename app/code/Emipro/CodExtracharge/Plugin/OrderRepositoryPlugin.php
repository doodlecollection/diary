<?php

namespace Emipro\CodExtracharge\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class OrderRepositoryPlugin
 */
class OrderRepositoryPlugin
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

    /**
     * Add "codcharge_fee" extension attribute to order data object to make it accessible in API data of order record
     *
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        /*if ($extensionAttributes === null) {
        $extensionAttributes = $this->getOrderExtensionDependency();
        }*/
        $attr = $order->getData('codcharge_fee_name');
        $extensionAttributes->setCashOnDeliveryFeeName($attr);
        $order->setExtensionAttributes($extensionAttributes);
        $attr = $order->getData('codcharge_fee');
        $extensionAttributes->setCashOnDeliveryFee($attr);
        $order->setExtensionAttributes($extensionAttributes);
        return $order;
    }

    /**
     * Add "codcharge_fee" extension attribute to order data object to make it accessible in API data of all order list
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            /*if ($extensionAttributes === null) {
            $extensionAttributes = $this->getOrderExtensionDependency();
            }*/
            $attr = $order->getData('codcharge_fee_name');
            $extensionAttributes->setCashOnDeliveryFeeName($attr);
            $order->setExtensionAttributes($extensionAttributes);
            $attr = $order->getData('codcharge_fee');
            $extensionAttributes->setCashOnDeliveryFee($attr);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}
