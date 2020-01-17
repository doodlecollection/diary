<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Label
 */


namespace Amasty\Label\Model;

class Rule extends \Magento\CatalogRule\Model\Rule
{
    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $amastySerializer;

    /**
     * @var \Magento\Store\Model\App\Emulation
     */
    private $storeEmulation;

    /**
     * @var \Amasty\Label\Plugin\App\Config\ScopeCodeResolver
     */
    private $scopeCodeResolver;

    protected function _construct()
    {
        $this->amastySerializer = $this->getData('amastySerializer');
        $this->storeEmulation = $this->getData('storeEmulation');
        $this->scopeCodeResolver = $this->getData('scopeCodeResolver');
        if (!$this->amastySerializer) {
            $this->amastySerializer = $this->serializer;
        }
        parent::_construct();
        $this->_init('Amasty\Label\Model\ResourceModel\Labels');
        $this->setIdFieldName('entity_id');
    }

    /**
     * @param array $ids
     */
    public function setProductFilter($ids)
    {
        $this->_productsFilter = $ids;
    }

    /**
     * create new function because it should be compatible with parent class
     * @param $label
     *
     * @return array|null
     */
    public function getMatchingProductIdsByLabel($label)
    {
        if ($this->_productIds === null) {
            $this->_productIds = [];
            $this->setCollectedAttributes([]);
            $this->scopeCodeResolver->setNeedClean(true);
            foreach (explode(',', $this->getStores()) as $storeId) {
                $this->storeEmulation->startEnvironmentEmulation($storeId);
                /** @var $productCollection \Magento\Catalog\Model\ResourceModel\Product\Collection */
                $productCollection = $this->_productCollectionFactory->create()
                    ->setStoreId($storeId);
                if ($label->getIsNew()) {
                    $productCollection->addAttributeToSelect('created_at', 'left')
                        ->addAttributeToSelect('news_from_date', 'left')
                        ->addAttributeToSelect('news_to_date', 'left');
                }

                if ($label->getIsSale() || $label->getPriceRangeEnabled()) {
                    $productCollection ->addAttributeToSelect('special_price', 'left')
                        ->addAttributeToSelect('special_from_date', 'left')
                        ->addAttributeToSelect('special_to_date', 'left')
                        ->addAttributeToSelect('price', 'left')
                        ->addAttributeToSelect('price_type', 'left')
                        ->addFinalPrice()
                        ->addMinimalPrice();
                }

                if ($this->_productsFilter) {
                    $productCollection->addIdFilter($this->_productsFilter);
                }

                $this->getConditions()->collectValidatedAttributes($productCollection);

                $this->_resourceIterator->walk(
                    $productCollection->getSelect(),
                    [[$this, 'callbackValidateProduct']],
                    [
                        'attributes' => $this->getCollectedAttributes(),
                        'product' => $this->_productFactory->create(),
                        'store_id' => $storeId,
                        'label' => $label
                    ]
                );
                $this->storeEmulation->stopEnvironmentEmulation();
            }
        }

        return $this->_productIds;
    }

    public function callbackValidateProduct($args)
    {
        $product = $args['product'];

        $storeId = $args['store_id'];
        $product->setData($args['row']);

        $product->setStoreId($storeId);

        $result = $this->getConditions()->validate($product);

        if ($result && isset($args['label'])) {
            $label = $args['label'];
            $product->setTypeInstance(null);
            $product->reloadPriceInfo();
            $label->init($product);

            $result = $result && $label->isApplicableForCustomRules();
        }

        if ($result) {
            $this->_productIds[$product->getId()][$storeId] = true;
        }
    }

    /**
     * fix fatal error after migration from 2.1 to 2.2 magento
     * Retrieve rule combine conditions model
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    public function getConditions()
    {
        if (empty($this->_conditions)) {
            $this->_resetConditions();
        }

        // Load rule conditions if it is applicable
        if ($this->hasConditionsSerialized()) {
            $conditions = $this->getConditionsSerialized();
            if (!empty($conditions)) {
                $conditions = $this->unserializeConditions($conditions);
                if (is_array($conditions) && !empty($conditions)) {
                    $this->_conditions->loadArray($conditions);
                }
            }
            $this->unsConditionsSerialized();
        }

        return $this->_conditions;
    }

    public function unserializeConditions($conditions)
    {
        $resultCondition = $this->amastySerializer->unserialize($conditions);
        if ($resultCondition === false) {
            $resultCondition = $this->serializer->unserialize($conditions);
        }

        return $resultCondition;
    }
}
