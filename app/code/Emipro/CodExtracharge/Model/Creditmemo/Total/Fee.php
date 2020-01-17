<?php

namespace Emipro\CodExtracharge\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

/**
 * Class Fee
 * @package Emipro\CodExtracharge\Model\Creditmemo\Total
 */
class Fee extends AbstractTotal
{
    /**
     * [__construct description]
     * @param \Emipro\CodExtracharge\Helper\Data         $data            [description]
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager    [description]
     * @param \Magento\Directory\Model\CurrencyFactory   $currencyFactory [description]
     */
    public function __construct(
        \Emipro\CodExtracharge\Helper\Data $data,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory
    ) {
        $this->currencyFactory = $currencyFactory;
        $this->storeManager = $storeManager;
        $this->data = $data;
    }
    /**
     * @param Creditmemo $creditmemo
     * @return $this
     */

    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $applyPro = $order->getCodAppliedRule();

        $creditmemo->setCodchargeFee(0);
        $creditmemo->setCodchargeBaseFee(0);

        if ($applyPro != null && $applyPro != "") {
            $proIds = [];
            $codcharge = 0;
            $proIds = $this->data->getCodAppliedProdIds($creditmemo);
            $applyProArr = $this->data->checkVersion($applyPro);
            foreach ($applyProArr as $applyProId) {
                if (in_array($applyProId['pid'], $proIds)) {
                    $codcharge = $codcharge + $applyProId['charge'];
                }
            }
            $percent = $creditmemo->getSubtotal() / $order->getSubtotal();
            $amount = $creditmemo->getOrder()->getCodchargeFee();
            $amount = $amount * $percent;
            $baseAmount = $creditmemo->getOrder()->getCodchargeBaseFee() * $percent;
            $creditmemo->setCodchargeFee($amount);
            $creditmemo->setCodchargeBaseFee($baseAmount);
        } else {
            $percent = $creditmemo->getSubtotal() / $order->getSubtotal();
            $amount = $creditmemo->getOrder()->getCodchargeFee() * $percent;
            $amount = $amount;
            $baseAmount = $creditmemo->getOrder()->getCodchargeBaseFee() * $percent;
            $baseAmount = $baseAmount;

        }
        $creditmemo->setCodchargeFee($amount);
        $creditmemo->setCodchargeBaseFee($baseAmount);
        $creditmemo->setCodchargeFeeName($order->getCodchargeFeeName());
        if ($order->getCreditmemosCollection()->count() == 0) {
            $grandTotal = $creditmemo->getOrder()->getGrandTotal() - $creditmemo->getOrder()->getShippingAmount();
            $baseGrandtotal = $creditmemo->getOrder()->getBaseGrandTotal() - $creditmemo->getOrder()->getBaseShippingAmount();
            $grandTotal = $grandTotal * $percent;
            $baseGrandtotal = $baseGrandtotal * $percent;
            $creditmemo->setGrandTotal($grandTotal + $creditmemo->getOrder()->getShippingAmount());
            $creditmemo->setBaseGrandTotal($baseGrandtotal + $creditmemo->getOrder()->getBaseShippingAmount());
        } else {
            $grandTotal = $creditmemo->getOrder()->getGrandTotal() - $creditmemo->getOrder()->getShippingAmount();
            $baseGrandtotal = $creditmemo->getOrder()->getBaseGrandTotal() - $creditmemo->getOrder()->getBaseShippingAmount();
            $creditmemo->setGrandTotal($grandTotal * $percent);
            $creditmemo->setBaseGrandTotal($baseGrandtotal * $percent);
        }
        return $this;
    }
}
