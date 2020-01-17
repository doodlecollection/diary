<?php

namespace Emipro\CodExtracharge\Model;

use Emipro\CodExtracharge\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Rule extends \Magento\Rule\Model\AbstractModel
{
    protected $_eventPrefix = 'emipro_codextracharge_rules';

    protected $_eventObject = 'rule';

    protected $productIds;

    protected $productsFilter = null;

    protected $now;

    protected static $priceRulesData = [];

    protected $catalogRuleData;

    protected $cacheTypesList;

    protected $relatedCacheTypes;

    protected $resourceIterator;

    protected $customerSession;

    protected $combineFactory;

    protected $actionCollectionFactory;

    protected $productFactory;

    protected $storeManager;

    protected $productCollectionFactory;

    protected $dateTime;

    protected $ruleProductProcessor;

    protected $_associatedEntitiesMap = [
        'website' => [
            'associations_table' => 'emipro_codextracharge_products',
            'rule_id_field' => 'rules_id',
            'entity_id_field' => 'website_id',
        ]];

    /**
     * [__construct description]
     * @param \Magento\Framework\Model\Context                               $context                  [description]
     * @param \Magento\Framework\Registry                                    $registry                 [description]
     * @param \Magento\Framework\Data\FormFactory                            $formFactory              [description]
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface           $localeDate               [description]
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory [description]
     * @param \Magento\Store\Model\StoreManagerInterface                     $storeManager             [description]
     * @param \Magento\CatalogRule\Model\Rule\Condition\CombineFactory       $combineFactory           [description]
     * @param \Magento\CatalogRule\Model\Rule\Action\CollectionFactory       $actionCollectionFactory  [description]
     * @param \Magento\Catalog\Model\ProductFactory                          $productFactory           [description]
     * @param \Magento\Framework\Model\ResourceModel\Iterator                $resourceIterator         [description]
     * @param \Magento\Customer\Model\Session                                $customerSession          [description]
     * @param \Magento\CatalogRule\Helper\Data                               $catalogRuleData          [description]
     * @param \Magento\Framework\App\Cache\TypeListInterface                 $cacheTypesList           [description]
     * @param \Magento\Framework\Stdlib\DateTime                             $dateTime                 [description]
     * @param \Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor   $ruleProductProcessor     [description]
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null   $resource                 [description]
     * @param \Magento\Framework\Data\Collection\AbstractDb|null             $resourceCollection       [description]
     * @param array                                                          $relatedCacheTypes        [description]
     * @param array                                                          $data                     [description]
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogRule\Model\Rule\Condition\CombineFactory $combineFactory,
        \Magento\CatalogRule\Model\Rule\Action\CollectionFactory $actionCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Model\ResourceModel\Iterator $resourceIterator,
        \Magento\Customer\Model\Session\Proxy $customerSession,
        \Magento\CatalogRule\Helper\Data $catalogRuleData,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypesList,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\CatalogRule\Model\Indexer\Rule\RuleProductProcessor $ruleProductProcessor,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $relatedCacheTypes = [],
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->combineFactory = $combineFactory;
        $this->actionCollectionFactory = $actionCollectionFactory;
        $this->productFactory = $productFactory;
        $this->resourceIterator = $resourceIterator;
        $this->customerSession = $customerSession;
        $this->catalogRuleData = $catalogRuleData;
        $this->cacheTypesList = $cacheTypesList;
        $this->relatedCacheTypes = $relatedCacheTypes;
        $this->dateTime = $dateTime;
        $this->ruleProductProcessor = $ruleProductProcessor;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $registry, $formFactory, $localeDate, $resource, $resourceCollection, $data);
    }
    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Emipro\CodExtracharge\Model\ResourceModel\Rule');
        $this->setIdFieldName('rules_id');
    }

    /**
     * [getConditionsInstance description]
     * @return [type] [description]
     */
    public function getConditionsInstance()
    {
        return $this->combineFactory->create();
    }

    /**
     * [getActionsInstance description]
     * @return [type] [description]
     */
    public function getActionsInstance()
    {
        return $this->actionCollectionFactory->create();
    }

    /**
     * [getCustomerGroupIds description]
     * @return [type] [description]
     */
    public function getCustomerGroupIds()
    {
        if (!$this->hasCustomerGroupIds()) {
            $customerGroupIds = $this->_getResource()->getCustomerGroupIds($this->getId());
            $this->setData('customer_group_ids', (array) $customerGroupIds);
        }
        return $this->_getData('customer_group_ids');
    }

    /**
     * [getNow description]
     * @return [type] [description]
     */
    public function getNow()
    {
        if (!$this->now) {
            return $this->dateTime->format('Y-m-d H:i:s');
        }
        return $this->now;
    }

    /**
     * [setNow description]
     * @param [type] $now [description]
     */
    public function setNow($now)
    {
        $this->now = $now;
    }

    /**
     * [getMatchingCodProductIds description]
     * @param  [type] $websiteId [description]
     * @return [type]            [description]
     */
    public function getMatchingCodProductIds($websiteId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if ($this->productIds === null) {
            $this->productIds = [];
            $this->setCollectedAttributes([]);

            if ($websiteId) {

                /** @var $productCollection \Magento\Catalog\Model\ResourceModel\Product\Collection */
                $productCollection = $this->productCollectionFactory->create();
                $productCollection->addWebsiteFilter($websiteId);
                if ($this->productsFilter) {
                    $productCollection->addIdFilter($this->productsFilter);
                }
                $this->getConditions()->collectValidatedAttributes($productCollection);

                $this->resourceIterator->walk(
                    $productCollection->getSelect(),
                    [[$this, 'callbackValidateProduct']],
                    [
                        'attributes' => $this->getCollectedAttributes(),
                        'product' => $this->productFactory->create(),
                    ]
                );
            }
        }
        return $this->productIds;
    }

    /**
     * [callbackValidateProduct description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public function callbackValidateProduct($args)
    {
        $product = clone $args['product'];
        $product->setData($args['row']);

        $websites = $this->_getWebsitesMap();
        $results = [];

        foreach ($websites as $websiteId => $defaultStoreId) {
            $product->setStoreId($defaultStoreId);
            $results[$websiteId] = $this->getConditions()->validate($product);
        }
        $this->productIds[$product->getId()] = $results;
    }

    /**
     * [_getWebsitesMap description]
     * @return [type] [description]
     */
    protected function _getWebsitesMap()
    {
        $map = [];
        $websites = $this->storeManager->getWebsites(true);
        foreach ($websites as $website) {
            if ($website->getDefaultStore() === null) {
                continue;
            }
            $map[$website->getId()] = $website->getDefaultStore()->getId();
        }
        return $map;
    }

    /**
     * [validateDiscount description]
     * @param  [type] $action   [description]
     * @param  [type] $discount [description]
     * @return [type]           [description]
     */
    protected function validateDiscount($action, $discount)
    {
        $result = [];
        switch ($action) {
            case 'by_percent':
            case 'to_percent':
                if ($discount < 0 || $discount > 100) {
                    $result[] = __('Percentage discount should be between 0 and 100.');
                };
                break;
            case 'by_fixed':
            case 'to_fixed':
                if ($discount < 0) {
                    $result[] = __('Discount value should be 0 or greater.');
                };
                break;
            default:
                $result[] = __('Unknown action.');
        }
        return $result;
    }

    /**
     * [calcProductPriceRule description]
     * @param  Product $product [description]
     * @param  [type]  $price   [description]
     * @return [type]           [description]
     */
    public function calcProductPriceRule(Product $product, $price)
    {
        $priceRules = null;
        $productId = $product->getId();
        $storeId = $product->getStoreId();
        $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
        if ($product->hasCustomerGroupId()) {
            $customerGroupId = $product->getCustomerGroupId();
        } else {
            $customerGroupId = $this->customerSession->getCustomerGroupId();
        }
        $dateTs = $this->_localeDate->scopeTimeStamp($storeId);
        $cacheKey = date('Y-m-d', $dateTs) . "|{$websiteId}|{$customerGroupId}|{$productId}|{$price}";

        if (!array_key_exists($cacheKey, self::$priceRulesData)) {
            $rulesData = $this->_getRulesFromProduct($dateTs, $websiteId, $customerGroupId, $productId);
            if ($rulesData) {
                foreach ($rulesData as $ruleData) {
                    if ($product->getParentId()) {
                        if (!empty($ruleData['sub_simple_action'])) {
                            $priceRules = $this->catalogRuleData->calcPriceRule(
                                $ruleData['sub_simple_action'],
                                $ruleData['sub_discount_amount'],
                                $priceRules ? $priceRules : $price
                            );
                        } else {
                            $priceRules = $priceRules ? $priceRules : $price;
                        }
                        if ($ruleData['action_stop']) {
                            break;
                        }
                    } else {
                        $priceRules = $this->catalogRuleData->calcPriceRule(
                            $ruleData['action_operator'],
                            $ruleData['action_amount'],
                            $priceRules ? $priceRules : $price
                        );
                        if ($ruleData['action_stop']) {
                            break;
                        }
                    }
                }
                return self::$priceRulesData[$cacheKey] = $priceRules;
            } else {
                self::$priceRulesData[$cacheKey] = null;
            }
        } else {
            return self::$priceRulesData[$cacheKey];
        }
        return null;
    }

    /**
     * [_getRulesFromProduct description]
     * @param  [type] $dateTs          [description]
     * @param  [type] $websiteId       [description]
     * @param  [type] $customerGroupId [description]
     * @param  [type] $productId       [description]
     * @return [type]                  [description]
     */
    protected function _getRulesFromProduct($dateTs, $websiteId, $customerGroupId, $productId)
    {
        return $this->_getResource()->getRulesFromProduct($dateTs, $websiteId, $customerGroupId, $productId);
    }

    /**
     * [setProductsFilter description]
     * @param [type] $productIds [description]
     */
    public function setProductsFilter($productIds)
    {
        $this->productsFilter = $productIds;
    }

    /**
     * [getProductsFilter description]
     * @return [type] [description]
     */
    public function getProductsFilter()
    {
        return $this->productsFilter;
    }

    /**
     * [_invalidateCache description]
     * @return [type] [description]
     */
    protected function _invalidateCache()
    {
        if (!empty($this->relatedCacheTypes)) {
            $this->cacheTypesList->invalidate($this->relatedCacheTypes);
        }
        return $this;
    }

    /**
     * [afterDelete description]
     * @return [type] [description]
     */
    public function afterDelete()
    {
        $this->ruleProductProcessor->getIndexer()->invalidate();
        return parent::afterDelete();
    }

    /**
     * [isRuleBehaviorChanged description]
     * @return boolean [description]
     */
    public function isRuleBehaviorChanged()
    {
        if (!$this->isObjectNew()) {
            $arrayDiff = $this->dataDiff($this->getOrigData(), $this->getStoredData());
            unset($arrayDiff['name']);
            unset($arrayDiff['description']);
            if (empty($arrayDiff)) {
                return false;
            }
        }
        return true;
    }

    /**
     * [dataDiff description]
     * @param  [type] $array1 [description]
     * @param  [type] $array2 [description]
     * @return [type]         [description]
     */
    protected function dataDiff($array1, $array2)
    {
        $result = [];
        foreach ($array1 as $key => $value) {
            if (array_key_exists($key, $array2)) {
                if (is_array($value)) {
                    if ($value != $array2[$key]) {
                        $result[$key] = true;
                    }
                } else {
                    if ($value != $array2[$key]) {
                        $result[$key] = true;
                    }
                }
            } else {
                $result[$key] = true;
            }
        }
        return $result;
    }
}
