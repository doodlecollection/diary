<?php

namespace Emipro\CodExtracharge\Model\ResourceModel;

use Magento\Catalog\Model\Product;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Rule extends \Magento\Rule\Model\ResourceModel\AbstractResource
{
    const SECONDS_IN_DAY = 86400;

    protected $logger;

    protected $_associatedEntitiesMap = [
        'website' => [
            'associations_table' => 'emipro_codextracharge_website',
            'rule_id_field' => 'rules_id',
            'entity_id_field' => 'website_id',
        ],
        'customer_group' => [
            'associations_table' => 'emipro_codextracharge_customer_group',
            'rule_id_field' => 'rules_id',
            'entity_id_field' => 'customer_group_id',
        ],
    ];

    /**
     * Catalog rule data
     *
     * @var \Magento\CatalogRule\Helper\Data
     */
    protected $catalogRuleData = null;

    /**
     * Core event manager proxy
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager = null;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $coreDate;

    /**
     * @var \Magento\Catalog\Model\Product\ConditionFactory
     */
    protected $conditionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    protected $dateTime;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Prefix for resources that will be used in this resource model
     *
     * @var string
     */
    protected $connectionName = \Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION;

    /**
     * [__construct description]
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context          [description]
     * @param \Magento\Store\Model\StoreManagerInterface        $storeManager     [description]
     * @param \Magento\Catalog\Model\Product\ConditionFactory   $conditionFactory [description]
     * @param \Magento\Framework\Stdlib\DateTime\DateTime       $coreDate         [description]
     * @param \Magento\Eav\Model\Config                         $eavConfig        [description]
     * @param \Magento\Framework\Event\ManagerInterface         $eventManager     [description]
     * @param \Magento\CatalogRule\Helper\Data                  $catalogRuleData  [description]
     * @param \Psr\Log\LoggerInterface                          $logger           [description]
     * @param \Magento\Framework\Stdlib\DateTime                $dateTime         [description]
     * @param PriceCurrencyInterface                            $priceCurrency    [description]
     * @param string                                            $connectionName   [description]
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\ConditionFactory $conditionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\CatalogRule\Helper\Data $catalogRuleData,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        PriceCurrencyInterface $priceCurrency,
        $connectionName = null
    ) {
        $this->storeManager = $storeManager;
        $this->conditionFactory = $conditionFactory;
        $this->coreDate = $coreDate;
        $this->eavConfig = $eavConfig;
        $this->eventManager = $eventManager;
        $this->catalogRuleData = $catalogRuleData;
        $this->logger = $logger;
        $this->dateTime = $dateTime;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $connectionName);
    }

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_init('emipro_codextracharge_rules', 'rules_id');
    }

    /**
     * [_afterLoad description]
     * @param  AbstractModel $object [description]
     * @return [type]                [description]
     */
    protected function _afterLoad(AbstractModel $object)
    {
        $object->setData('customer_group_ids', (array) $this->getCustomerGroupIds($object->getId()));
        $object->setData('website_ids', (array) $this->getWebsiteIds($object->getId()));

        return parent::_afterLoad($object);
    }

    /**
     * [_afterSave description]
     * @param  AbstractModel $object [description]
     * @return [type]                [description]
     */
    protected function _afterSave(AbstractModel $object)
    {
        if ($object->hasWebsiteIds()) {
            $websiteIds = $object->getWebsiteIds();
            if (!is_array($websiteIds)) {
                $websiteIds = explode(',', (string) $websiteIds);
            }
            $this->bindRuleToEntity($object->getId(), $websiteIds, 'website');
        }

        if ($object->hasCustomerGroupIds()) {
            $customerGroupIds = $object->getCustomerGroupIds();
            if (!is_array($customerGroupIds)) {
                $customerGroupIds = explode(',', (string) $customerGroupIds);
            }
            $this->bindRuleToEntity($object->getId(), $customerGroupIds, 'customer_group');
        }

        parent::_afterSave($object);
        return $this;
    }

    /**
     * [_afterDelete description]
     * @param  \Magento\Framework\Model\AbstractModel $rule [description]
     * @return [type]                                       [description]
     */
    protected function _afterDelete(\Magento\Framework\Model\AbstractModel $rule)
    {
        $connection = $this->getConnection();
        $connection->delete(
            $this->getTable('catalogrule_product'),
            ['rule_id=?' => $rule->getId()]
        );
        $connection->delete(
            $this->getTable('catalogrule_customer_group'),
            ['rule_id=?' => $rule->getId()]
        );
        $connection->delete(
            $this->getTable('catalogrule_group_website'),
            ['rule_id=?' => $rule->getId()]
        );
        return parent::_afterDelete($rule);
    }

    /**
     * [getRulePrice description]
     * @param  [type] $date [description]
     * @param  [type] $wId  [description]
     * @param  [type] $gId  [description]
     * @param  [type] $pId  [description]
     * @return [type]       [description]
     */
    public function getRulePrice($date, $wId, $gId, $pId)
    {
        $data = $this->getRulePrices($date, $wId, $gId, [$pId]);
        if (isset($data[$pId])) {
            return $data[$pId];
        }

        return false;
    }

    /**
     * [getRulePrices description]
     * @param  \DateTime $date            [description]
     * @param  [type]    $websiteId       [description]
     * @param  [type]    $customerGroupId [description]
     * @param  [type]    $productId      [description]
     * @return [type]                     [description]
     */
    public function getRulePrices(\DateTime $dates, $websiteIds, $customerGroupsId, $productId)
    {
        $connections = $this->getConnection();
        $selectRes = $connections->select()->from(
            $this->getTable('catalogrule_product_price'),
            ['product_id', 'rule_price']
        )->where(
            'rule_date = ?',
            $dates->format('Y-m-d')
        )->where(
            'website_id = ?',
            $websiteIds
        )->where(
            'customer_group_id = ?',
            $customerGroupsId
        )->where(
            'product_id IN(?)',
            $productId
        );
        return $connections->fetchPairs($selectRes);
    }

    /**
     * [getRulesFromProduct description]
     * @param  [type] $dates            [description]
     * @param  [type] $websiteIds       [description]
     * @param  [type] $customerGroupsId [description]
     * @param  [type] $productId       [description]
     * @return [type]                  [description]
     */
    public function getRulesFromProduct($dates, $websiteIds, $customerGroupsId, $productId)
    {
        $connections = $this->getConnection();
        if (is_string($dates)) {
            $dates = strtotime($dates);
        }
        $selectRes = $connections->select()->from(
            $this->getTable('catalogrule_product')
        )->where(
            'website_id = ?',
            $websiteIds
        )->where(
            'customer_group_id = ?',
            $customerGroupsId
        )->where(
            'product_id = ?',
            $productId
        )->where(
            'from_time = 0 or from_time < ?',
            $dates
        )->where(
            'to_time = 0 or to_time > ?',
            $dates
        );

        return $connections->fetchOne($selectRes);
    }
}
