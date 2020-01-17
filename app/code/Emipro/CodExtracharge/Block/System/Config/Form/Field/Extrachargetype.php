<?php

namespace Emipro\CodExtracharge\Block\System\Config\Form\Field;

class Extrachargetype extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * [getExtrachargetype description]
     * @return [type] [description]
     */
    public function getExtrachargetype()
    {
        $methods = [];
        return [
            'fixed' => __('Fixed Charge'),
            'percentage' => __('Percentage')];
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
            foreach ($this->getExtrachargetype() as $key => $Titles) {
                $this->addOption($key, $Titles);
            }
        }
        return parent::_toHtml();
    }
}
