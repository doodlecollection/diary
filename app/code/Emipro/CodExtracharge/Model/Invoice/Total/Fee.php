<?php

namespace Emipro\CodExtracharge\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    protected $directoryHelper;
    /**
     * [__construct description]
     * @param \Emipro\CodExtracharge\Helper\Data         $data            [description]
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager    [description]
     * @param \Magento\Directory\Model\CurrencyFactory   $currencyFactory [description]
     */
    public function __construct(
        \Emipro\CodExtracharge\Helper\Data $data,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Magento\Directory\Helper\Data $directoryHelper
    ) {
        $this->currencyFactory = $currencyFactory;
        $this->storeManager = $storeManager;
        $this->data = $data;
        $this->directoryHelper = $directoryHelper;
    }

    /**
     * @param Invoice $invoice
     * @return $this
     */

    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $order = $invoice->getOrder();

        $applyPro = $order->getCodAppliedRule();

        $invoice->setCodchargeFee(0);
        $invoice->setCodchargeBaseFee(0);

        if ($applyPro != null && $applyPro != "") {
            $proIds = [];
            $codcharge = 0;
            $proIds = $this->data->getCodAppliedProdIds($invoice);
            $applyProArr = $this->data->checkVersion($applyPro);
            foreach ($applyProArr as $applyProId) {
                if (in_array($applyProId['pid'], $proIds)) {
                    $codcharge = $codcharge + $applyProId['charge'];
                }
            }
            $percent = $invoice->getSubtotal() / $order->getSubtotal();
            $amount = $invoice->getOrder()->getCodchargeFee();
            $amount = $amount * $percent;
            $baseAmount = $invoice->getOrder()->getCodchargeBaseFee() * $percent;
        } else {
            $percent = $invoice->getSubtotal() / $order->getSubtotal();
            $amount = $invoice->getOrder()->getCodchargeFee() * $percent;
            $baseAmount = $invoice->getOrder()->getCodchargeBaseFee() * $percent;
        }
        $invoice->setCodchargeFee($amount);
        $invoice->setCodchargeBaseFee($baseAmount);

        $invoice->setCodchargeFeeName($order->getCodchargeFeeName());
        if ($order->getInvoiceCollection()->count() == 0) {
            $grandTotal = $invoice->getOrder()->getGrandTotal() - $invoice->getOrder()->getShippingAmount();
            $baseGrandtotal = $invoice->getOrder()->getBaseGrandTotal() - $invoice->getOrder()->getBaseShippingAmount();
            $grandTotal = $grandTotal * $percent;
            $baseGrandtotal = $baseGrandtotal * $percent;
            $invoice->setGrandTotal($grandTotal + $invoice->getOrder()->getShippingAmount());
            $invoice->setBaseGrandTotal($baseGrandtotal + $invoice->getOrder()->getBaseShippingAmount());
        } else {
            $grandTotal = $invoice->getOrder()->getGrandTotal() - $invoice->getOrder()->getShippingAmount();
            $baseGrandtotal = $invoice->getOrder()->getBaseGrandTotal() - $invoice->getOrder()->getBaseShippingAmount();
            $invoice->setGrandTotal($grandTotal * $percent);
            $invoice->setBaseGrandTotal($baseGrandtotal * $percent);
        }
        return $this;
    }
}
