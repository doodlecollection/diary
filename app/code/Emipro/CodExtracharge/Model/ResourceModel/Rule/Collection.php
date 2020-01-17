<?php

namespace Emipro\CodExtracharge\Model\ResourceModel\Rule;

class Collection extends \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
{
    protected $_associatedEntitiesMap = [
        'website' => [
            'associations_table' => 'emipro_codextracharge_products',
            'rule_id_field' => 'rules_id',
            'entity_id_field' => 'website_id',
        ],
    ];

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_init('Emipro\CodExtracharge\Model\Rule', 'Emipro\CodExtracharge\Model\ResourceModel\Rule');
    }

    /**
     * [addAttributeInConditionFilter description]
     * @param [type] $attributeCode [description]
     */
    public function addAttributeInConditionFilter($attributeCode)
    {
        $match = sprintf('%%%s%%', substr(serialize(['attribute' => $attributeCode]), 5, -1));
        $this->addFieldToFilter('conditions_serialized', ['like' => $match]);

        return $this;
    }
}
