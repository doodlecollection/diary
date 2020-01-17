<?php

namespace Emipro\CodExtracharge\Model\ResourceModel\Rule\Product\Price;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init(
            'Emipro\CodExtracharge\Model\Rule\Product\Price',
            'Emipro\CodExtracharge\Model\ResourceModel\Rule\Product\Price'
        );
    }
}
