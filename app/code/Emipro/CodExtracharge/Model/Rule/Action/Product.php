<?php

namespace Emipro\CodExtracharge\Model\Rule\Action;

class Product extends \Magento\Rule\Model\Action\AbstractAction
{
    /**
     * [loadAttributeOptions description]
     * @return [type] [description]
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption(['rule_price' => __('Rule price')]);
        return $this;
    }

    /**
     * [loadOperatorOptions description]
     * @return [type] [description]
     */
    public function loadOperatorOptions()
    {
        $this->setOperatorOption(
            [
                'to_fixed' => __('To Fixed Value'),
                'to_percent' => __('To Percentage'),
                'by_fixed' => __('By Fixed value'),
                'by_percent' => __('By Percentage'),
            ]
        );
        return $this;
    }

    /**
     * @return string
     */
    public function asHtml()
    {
        $htmls = $this->getTypeElement()->getHtml() . __(
            "Update product's %1 %2: %3",
            $this->getAttributeElement()->getHtml(),
            $this->getOperatorElement()->getHtml(),
            $this->getValueElement()->getHtml()
        );
        $htmls .= $this->getRemoveLinkHtml();
        return $htmls;
    }
}
