<?php

namespace Emipro\CodExtracharge\Model\Rule\Condition;

/**
 * Class Product
 */
class Product extends \Magento\Rule\Model\Condition\Product\AbstractProduct
{
    /**
     * Validate product attribute value for condition
     *
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return bool
     */
    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $attrCode = $this->getAttribute();
        if ('category_ids' == $attrCode) {
            return $this->validateAttribute($model->getAvailableInCategories());
        }

        $oldAttrValue = $model->hasData($attrCode) ? $model->getData($attrCode) : null;
        $this->_setAttributeValue($model);

        $result = $this->validateAttribute($model->getData($this->getAttribute()));
        $this->_restoreOldAttrValue($model, $oldAttrValue);

        return (bool) $result;
    }

    /**
     * Restore old attribute value
     *
     * @param \Magento\Framework\Model\AbstractModel $model
     * @param mixed $oldAttrValue
     * @return void
     */
    protected function _restoreOldAttrValue(\Magento\Framework\Model\AbstractModel $model, $oldAttrValues)
    {
        $attrCodes = $this->getAttribute();
        if ($oldAttrValues === null) {
            $model->unsetData($attrCodes);
        } else {
            $model->setData($attrCodes, $oldAttrValues);
        }
    }

    /**
     * Set attribute value
     *
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return $this
     */
    protected function _setAttributeValue(\Magento\Framework\Model\AbstractModel $model)
    {
        $storeIds = $model->getStoreId();
        $defaultStoreIds = \Magento\Store\Model\Store::DEFAULT_STORE_ID;

        if (!isset($this->_entityAttributeValues[$model->getId()])) {
            return $this;
        }

        $productValue = $this->_entityAttributeValues[$model->getId()];

        if (!isset($productValue[$storeIds]) && !isset($productValue[$defaultStoreIds])) {
            return $this;
        }

        $values = isset($productValue[$storeIds]) ? $productValue[$storeIds] : $productValue[$defaultStoreIds];

        $values = $this->_prepareDatetimeValue($values, $model);
        $values = $this->_prepareMultiselectValue($values, $model);

        $model->setData($this->getAttribute(), $values);

        return $this;
    }

    /**
     * Prepare datetime attribute value
     *
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return mixed
     */
    protected function _prepareDatetimeValue($values, \Magento\Framework\Model\AbstractModel $model)
    {
        $attributes = $model->getResource()->getAttribute($this->getAttribute());
        if ($attributes && $attributes->getBackendType() == 'datetime') {
            $values = strtotime($values);
        }

        return $values;
    }

    /**
     * Prepare multiselect attribute value
     *
     * @param mixed $values
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return mixed
     */
    protected function _prepareMultiselectValue($values, \Magento\Framework\Model\AbstractModel $model)
    {
        $attributes = $model->getResource()->getAttribute($this->getAttribute());
        if ($attributes && $attributes->getFrontendInput() == 'multiselect') {
            $values = strlen($values) ? explode(',', $values) : [];
        }

        return $values;
    }
}
