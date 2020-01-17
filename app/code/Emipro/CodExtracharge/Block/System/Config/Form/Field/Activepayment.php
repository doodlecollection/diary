<?php

namespace Emipro\CodExtracharge\Block\System\Config\Form\Field;

class Activepayment extends \Magento\Framework\View\Element\Html\Select
{
    private $activepayment;
    protected $addPaymentAllOption = true;

    /**
     * [getConditionalOperators description]
     * @return [type] [description]
     */
    public function getConditionalOperators()
    {
        $methods = [];
        return [
            '>=' => __('equals or greater than'),
            '<=' => __('equals or less than'),
            '>' => __('greater than'),
            '<' => __('less than')];
    }

    /**
     * [setInputName description]
     * @param [type] $value [description]
     */
    public function setInputName($value)
    {

        return $this->setName($value);
    }

    /**
     * [_toHtml description]
     * @return [type] [description]
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->getConditionalOperators() as $key => $Titles) {
                $this->addOption($key, $Titles);
            }
        }
        return parent::_toHtml();
    }
}
