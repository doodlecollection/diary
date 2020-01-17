<?php

namespace Emipro\CodExtracharge\Model\Rule\Product;

use Magento\Framework\DB\Select;

class Price extends \Magento\Framework\Model\AbstractModel
{

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_init('Emipro\CodExtracharge\Model\ResourceModel\Rule\Product\Price');
    }

    /**
     * [applyPriceRuleToIndexTable description]
     * @param  Select $select          [description]
     * @param  [type] $indexTable      [description]
     * @param  [type] $entityId        [description]
     * @param  [type] $customerGroupId [description]
     * @param  [type] $websiteId       [description]
     * @param  [type] $updateFields    [description]
     * @param  [type] $websiteDate     [description]
     * @return [type]                  [description]
     */
    public function applyPriceRuleToIndexTable(
        Select $select,
        $indexTable,
        $entityId,
        $customerGroupId,
        $websiteId,
        $updateFields,
        $websiteDate
    ) {
        $this->_getResource()->applyPriceRuleToIndexTable(
            $select,
            $indexTable,
            $entityId,
            $customerGroupId,
            $websiteId,
            $updateFields,
            $websiteDate
        );

        return $this;
    }
}
