<?php

namespace Emipro\CodExtracharge\Model\Sales\Order\Creditmemo;

class Extracharge extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
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
     * [collect description]
     * @param  \Magento\Sales\Model\Order\Creditmemo $creditmemo [description]
     * @return [type]                                            [description]
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
            $amount = number_format((float) $codcharge, 1, '.', '');
            $orderCurrency = $order->getData('order_currency_code');
            $amount = $this->convertPrice($amount, $orderCurrency, 0);
            $amount = number_format((float) $amount, 1, '.', '');
            $baseAmount = number_format((float) $codcharge, 1, '.', '');
        } else {
            $percent = $creditmemo->getSubtotal() / $order->getSubtotal();
            $amount = $creditmemo->getOrder()->getCodchargeFee() * $percent;
            $amount = number_format((float) $amount, 1, '.', '');
            $baseAmount = $creditmemo->getOrder()->getCodchargeBaseFee() * $percent;
            $baseAmount = number_format((float) $baseAmount, 1, '.', '');
        }

        $creditmemo->setCodchargeFee($amount);
        $creditmemo->setCodchargeBaseFee($baseAmount);
        $creditmemo->setCodchargeFeeName($order->getCodchargeFeeName());
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $amount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseAmount);
        return $this;
    }

    /**
     * [convertPrice description]
     * @param  [type] $amountValue [description]
     * @return [type]              [description]
     */
    public function convertPrice($amountValue, $orderCurrency, $chargetype)
    {
        $baseCurrency = $this->storeManager->getStore()->getBaseCurrency()->getCode();
        if ($orderCurrency != $baseCurrency) {
            $rate = $chargetype == 0 ? $rate = $this->currencyFactory->create()->load($baseCurrency)
                ->getAnyRate($orderCurrency) : $rate = $this->currencyFactory
                ->create()->load($orderCurrency)->getAnyRate($baseCurrency);
            $amountValue = $amountValue * $rate;
        }
        return $amountValue;
    }
}
