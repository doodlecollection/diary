<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Sales\Order;

use \Magento\Framework\DataObject;

class Extracharge extends \Magento\Framework\View\Element\Template
{
    /**
     * Tax configuration model
     *
     * @var \Magento\Tax\Model\Config
     */
    protected $config;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param array $data
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
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
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
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
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
