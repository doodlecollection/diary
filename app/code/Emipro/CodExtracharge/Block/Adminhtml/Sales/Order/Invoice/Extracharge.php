<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Sales\Order\Invoice;

use \Magento\Framework\DataObject;

class Extracharge extends \Magento\Framework\View\Element\Template
{

    protected $config;
    protected $order;
    protected $source;

    /**
     * [__construct description]
     * @param \Magento\Framework\View\Element\Template\Context $context   [description]
     * @param \Magento\Tax\Model\Config                        $taxConfig [description]
     * @param array                                            $data      [description]
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Tax\Model\Config $taxConfig,
        DataObject $dataObject,
        array $data = []
    ) {
        $this->config = $taxConfig;
        $this->dataObject = $dataObject;
        parent::__construct($context, $data);
    }

    /**
     * [displayFullSummary description]
     * @return [type] [description]
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * [getSource description]
     * @return [type] [description]
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * [getStore description]
     * @return [type] [description]
     */
    public function getStore()
    {
        return $this->order->getStore();
    }

    /**
     * [getOrder description]
     * @return [type] [description]
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * [getLabelProperties description]
     * @return [type] [description]
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * [getValueProperties description]
     * @return [type] [description]
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * [initTotals description]
     * @return [type] [description]
     */
    public function initTotals()
    {

        $parent = $this->getParentBlock();
        $this->order = $parent->getOrder();
        $this->source = $parent->getSource();

        $store = $this->getStore();
        if ($this->source->getCodchargeFee() > 0) {
            $fee = $this->dataObject->addData(
                [
                    'code' => 'excharge',
                    'strong' => false,
                    'value' => $this->source->getCodchargeFee(),
                    'base_value' => $this->source->getCodchargeBaseFee(),
                    'label' => __($this->source->getCodchargeFeeName()),
                ]
            );
            $parent->addTotal($fee, 'excharge');
            $parent->addTotal($fee, 'excharge');
            return $this;
        }
    }
}
