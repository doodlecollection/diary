<?php

namespace Emipro\CodExtracharge\Model\ResourceModel\Productrule;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_init(
            'Emipro\CodExtracharge\Model\Productrule',
            'Emipro\CodExtracharge\Model\ResourceModel\Productrule'
        );
    }

    public function availableCODExtrachargesPerProduct($id)
    {
        return $this->getSelect()->join(
            ["cod" => $this->getTable('emipro_codextracharge_rules')],
            "main_table.rules_id=cod.rules_id"
        )->where(
            "main_table.entity_id IN (?)",
            $id)->where("cod.is_active= 1"
        )->group("main_table.rules_id")->order("cod.sort_order");
    }

    public function availableCODExtracharges($id)
    {
        return $this->getSelect()->join(
            ["cod" => $this->getTable('emipro_codextracharge_rules')],
            "main_table.rules_id=cod.rules_id"
        )->where("main_table.entity_id=" . $id)->where("cod.is_active= 1")->order("cod.sort_order")->limit(1);
    }
}
