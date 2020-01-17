<?php

namespace Emipro\CodExtracharge\Model\Sales;

use Magento\Catalog\Model\Session;

class Extracharge extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    private $max_payment_condition = "";
    private $max_payment_amount = 0;
    protected $quoteValidator = null;
    protected $storeManager;
    protected $pointssession;

    /**
     * [__construct description]
     * @param \Magento\Quote\Model\QuoteValidator        $quoteValidator  [description]
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager    [description]
     * @param \Magento\Quote\Model\Quote\Address\Total   $total           [description]
     * @param \Emipro\CodExtracharge\Helper\Data         $data            [description]
     * @param \Magento\Customer\Model\Session\Proxy      $customersession [description]
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Emipro\CodExtracharge\Helper\Data $data,
        \Magento\Customer\Model\Session\Proxy $customersession,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        Session $session,
        \Magento\Directory\Helper\Data $directoryHelper
    ) {
        $this->storeManager = $storeManager;
        $this->customersession = $customersession;
        $this->quoteValidator = $quoteValidator;
        $this->currencyFactory = $currencyFactory;
        $this->data = $data;
        $this->pointssession = $session;
        $this->directoryHelper = $directoryHelper;
    }

    /**
     * [collect description]
     * @param  \Magento\Quote\Model\Quote                          $quote              [description]
     * @param  \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment [description]
     * @param  \Magento\Quote\Model\Quote\Address\Total            $total              [description]
     * @return [type]                                                                  [description]
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $codapply = $this->data->getConfig('payment/cashondelivery/applycodextracharge');
        $codActive = $codapply = $this->data->getConfig('payment/cashondelivery/active');
        $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $codapply = $objManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('payment/cashondelivery/applycodextracharge');
        if ($quote->getPayment()->getMethod() == "cashondelivery" && $codapply == 1 && $codActive == 1) {

            $balance = 0;
            $lable = '';
            $lable = $this->data->getConfig('codextracharge/labelsection/displaylabel', true);

            $cod = $this->data->codCharges($quote);
            $balance = $cod["balance"];
            $basebalance = $cod["base_balance"];
            $applyPro = $cod['applyPro'];
            if ($total->getSubtotal()) {
                $total->setTotalAmount('excharge', $balance);
                $total->setBaseTotalAmount('excharge', $basebalance);
                $total->setCodchargeFee($balance);
                $total->setCodchargeBaseFee($basebalance);
                $total->setCodchargeFeeName($lable);
                $quote->setCodAppliedRule($applyPro);
                $total->setCodAppliedRule($applyPro);
                $quote->setCodchargeFee($balance);
                $quote->setCodchargeBaseFee($basebalance);
                $quote->setCodchargeFeeName($lable);
            }
        }

        return $this;
    }

    /**
     * [clearValues description]
     * @param  Address\Total $total [description]
     * @return [type]               [description]
     */
    protected function clearValues(
        Address\Total $total
    ) {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * [fetch description]
     * @param  \Magento\Quote\Model\Quote               $quote [description]
     * @param  \Magento\Quote\Model\Quote\Address\Total $total [description]
     * @return [type]                                          [description]
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $fee = 0;
        $lable = '';
        $method = $quote->getPayment()->getMethod();
        $quote->getPayment()->getMethod();
        $codapply = $this->data->getConfig('payment/cashondelivery/applycodextracharge');
        $codActive = $codapply = $this->data->getConfig('payment/cashondelivery/active');
        $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $codapply = $objManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('payment/cashondelivery/applycodextracharge');
        if ($quote->getPayment()->getMethod() == "cashondelivery" && $codapply == 1 && $codActive == 1) {
            $cod = $this->data->codCharges($quote);
            $fee = $cod["balance"];
            $lable = $this->data->getConfig('codextracharge/labelsection/displaylabel', true);
        }
        return [
            'code' => 'excharge',
            'title' => __("$lable"),
            'value' => $fee,
        ];

    }

    /**
     * [convertPrice description]
     * @param  [type] $amountValue [description]
     * @return [type]              [description]
     */
    public function convertPrice($amountValue, $chargetype)
    {
        $currentCurrency = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
        $baseCurrency = $this->storeManager->getStore()->getBaseCurrency()->getCode();
        if ($currentCurrency != $baseCurrency) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $amountValue = $this->directoryHelper->currencyConvert($amountValue, $baseCurrency, $currentCurrency);
            $amountValue = number_format($amountValue, 1, '.', '');
        }
        return $amountValue;
    }
    /**
     * [getPaymentCondition description]
     * @param  [type] $subtotal          [description]
     * @param  [type] $payment_condition [description]
     * @param  [type] $total_amount      [description]
     * @param  [type] $condition_amount  [description]
     * @return [type]                    [description]
     */
    public function getPaymentCondition(
        $subtotal,
        $payment_condition,
        $total_amount,
        $condition_amount,
        $max_payment_condition
    ) {
        $condition = false;
        $amount = ((in_array($total_amount, $condition_amount)) && (max($condition_amount) == $total_amount)
            && $subtotal > max($condition_amount)) ? max($condition_amount) : $total_amount;
        if (($amount == max($condition_amount) && $subtotal < max($condition_amount)
            && in_array($payment_condition, ['<', '<='])
            && (((($payment_condition != $max_payment_condition[min($condition_amount)])
                || ($payment_condition == $max_payment_condition[min($condition_amount)]))
                && $total_amount <= min($condition_amount)
                && $subtotal <= min($condition_amount) && $subtotal > $this->max_payment_amount)
            ))
            || ($amount == max($condition_amount) && $subtotal > max($condition_amount)
                && in_array($payment_condition, ['>', '>='])
                && (($payment_condition != $max_payment_condition[max($condition_amount)])
                    || ($payment_condition == $max_payment_condition[max($condition_amount)]
                        && $subtotal > max($condition_amount))))
            || (($amount < max($condition_amount)) && ($subtotal < $amount)
                && (!in_array($payment_condition, ['>', '>='])
                    && !in_array($max_payment_condition[max($condition_amount)], ['<', '<='])))
            || (($amount < max($condition_amount)) && ($subtotal > $amount)
                && (in_array($payment_condition, ['>', '>='])
                    && in_array($max_payment_condition[max($condition_amount)], ['<', '<='])
                    && $amount >= $this->max_payment_amount))
            || (($amount < max($condition_amount)) && ($subtotal < $amount)
                && in_array($payment_condition, ['<', '<='])
                && (($payment_condition != $max_payment_condition[min($condition_amount)])
                    || (($amount <= $this->max_payment_amount && $this->max_payment_amount > 0)
                        || ($amount > $this->max_payment_amount && $this->max_payment_amount == 0))))
            || (($amount > $this->max_payment_amount) && ($subtotal > $this->max_payment_amount)
                && ($subtotal > $amount) && in_array($payment_condition, ['<', '<='])
                && in_array($this->max_payment_condition, ['<', '<=']))
            || (($amount < $this->max_payment_amount) && ($subtotal < $this->max_payment_amount)
                && ($subtotal < $amount) && in_array($payment_condition, ['<', '<='])
                && in_array($this->max_payment_condition, ['<', '<=']))
            || (($amount < $this->max_payment_amount) && ($subtotal < $this->max_payment_amount)
                && ($subtotal < $amount) && in_array($payment_condition, ['>', '>='])
                && in_array($this->max_payment_condition, ['>', '>=']))
            || (($amount > $this->max_payment_amount) && ($subtotal > $this->max_payment_amount)
                && ($subtotal > $amount) && in_array($payment_condition, ['>', '>='])
                && in_array($this->max_payment_condition, ['>', '>=']))
            || (($amount < max($condition_amount)) && ($subtotal > $amount)
                && in_array($payment_condition, ['>', '>='])
                && (($payment_condition != $max_payment_condition[max($condition_amount)])
                    || ($payment_condition == $max_payment_condition[max($condition_amount)]
                        && $subtotal < max($condition_amount))) && $amount > $this->max_payment_amount)
            || (in_array($payment_condition, ['>', '>='])
                && in_array($max_payment_condition[max($condition_amount)], ['<', '<='])
                && ($subtotal > max($condition_amount)) && $amount > $this->max_payment_amount)) {
            $this->max_payment_condition = $payment_condition;
            $this->max_payment_amount = $amount;
            if ($payment_condition == ">") {
                $condition = $subtotal > $amount;
            } elseif ($payment_condition == "<") {
                $condition = $subtotal < $amount;
            } elseif ($payment_condition == ">=") {
                $condition = $subtotal >= $amount;
            } elseif ($payment_condition == "<=") {
                $condition = $subtotal <= $amount;
            }
        }
        return $condition;
    }
}
