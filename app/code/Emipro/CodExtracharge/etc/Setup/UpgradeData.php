<?php
/**
 * Copyright © Emipro Technologies Pvt Ltd. All rights reserved.
 * @license http://shop.emiprotechnologies.com/license-agreement/
 */
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Emipro\CodExtracharge\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * [\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig]
     * @var [type]
     */
    protected $scopeConfig;
    /**
     * [\Magento\Config\Model\ResourceModel\Config $resourceConfig description]
     * @var [type]
     */
    protected $resourceConfig;
    /**
     * [\Magento\Framework\Json\Helper\Data  $jsonHelper description]
     * @var [type]
     */
    protected $jsonHelper;
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {

        $this->scopeConfig = $scopeConfig;
        $this->resourceConfig = $resourceConfig;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * UpgradeData constructor.
     *
     * @param AggregatedFieldDataConverter $aggregatedFieldConverter
     * @param MetadataPool $metadataPool
     */
    /**
     * @inheritdoc
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $objManager = \Magento\Framework\App\ObjectManager::getInstance();
        $productMetadata = $objManager->get('Magento\Framework\App\ProductMetadataInterface');
        if ($productMetadata->getVersion() > '2.2.0') {
            $setup->startSetup();
            $serializedValue = $this->scopeConfig->getValue('payment/cashondelivery/active2', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            if ($this->isSerialized($serializedValue)) {
                $unserializer = $objManager->get(\Magento\Framework\Unserialize\Unserialize::class);
                $condition = $unserializer->unserialize($serializedValue);
                $this->resourceConfig->saveConfig('payment/cashondelivery/active2', json_encode($condition), 'default', 0);
            }
            $setup->endSetup();
        }
    }
    private function isSerialized($value)
    {
        return (boolean) preg_match('/^((s|i|d|b|a|O|C):|N;)/', $value);
    }
    /**
     * Convert metadata from serialized to JSON format:
     *
     * @param ModuleDataSetupInterface $setup
     *
     * @return void
     */
}
