<?php

namespace Emipro\CodExtracharge\Model\Rule\Action;

class Collection extends \Magento\Rule\Model\Action\Collection
{
    /**
     * [getNewChildSelectOptions description]
     * @return [type] [description]
     */
    public function getNewChildSelectOptions()
    {
        $this->setType('Emipro\CodExtracharge\Model\Rule\Action\Collection');
        $actions = parent::getNewChildSelectOptions();
        $actions = array_merge_recursive(
            $actions,
            [
                ['value' => 'Emipro\CodExtracharge\Model\Rule\Action\Product', 'label' => __('Update the Product')],
            ]
        );
        return $actions;
    }
}
