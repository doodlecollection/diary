<?php

namespace Emipro\CodExtracharge\Model\ResourceModel\Rule\Product;

class Price extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_init('catalogrule_product_price', 'rule_product_price_id');
    }

    /**
     * [applyPriceRuleToIndexTable description]
     * @param  \Magento\Framework\DB\Select $select          [description]
     * @param  [type]                       $indexTable      [description]
     * @param  [type]                       $entityId        [description]
     * @param  [type]                       $customerGroupId [description]
     * @param  [type]                       $websiteId       [description]
     * @param  [type]                       $updateFields    [description]
     * @param  [type]                       $websiteDate     [description]
     * @return [type]                                        [description]
     */
    public function applyPriceRuleToIndexTable(
        \Magento\Framework\DB\Select $select,
        $indexTable,
        $entityId,
        $customerGroupId,
        $websiteId,
        $updateFields,
        $websiteDate
    ) {
        if (empty($updateFields)) {
            return $this;
        }

        if (is_array($indexTable)) {
            foreach ($indexTable as $k => $v) {
                if (is_string($k)) {
                    $indexAliases = $k;
                } else {
                    $indexAliases = $v;
                }
                break;
            }
        } else {
            $indexAliases = $indexTable;
        }

        $select->join(
            ['rp' => $this->getMainTable()],
            "rp.rule_date = {$websiteDate}",
            []
        )->where(
            "rp.product_id = {$entityId} AND rp.website_id = {$websiteId} AND rp.customer_group_id = {$customerGroupId}"
        );

        foreach ($updateFields as $priceFields) {
            $priceConds = $this->getConnection()->quoteIdentifier([$indexAliases, $priceFields]);
            $priceExprs = $this->getConnection()->getCheckSql(
                "rp.rule_price < {$priceConds}",
                'rp.rule_price',
                $priceConds
            );
            $select->columns([$priceField => $priceExprs]);
        }

        $query = $select->crossUpdateFromSelect($indexTable);
        $this->getConnection()->query($query);

        return $this;
    }
}
