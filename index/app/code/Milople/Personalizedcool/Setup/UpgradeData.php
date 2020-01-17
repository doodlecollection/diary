<?php
namespace Milople\Personalizedcool\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Catalog\Model\Product\Type;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
		$productTypes = [
			'downloadable',
			Type::TYPE_BUNDLE,
			Type::TYPE_VIRTUAL,
			Configurable::TYPE_CODE,
			Type::TYPE_SIMPLE
        ];
        $productTypes = join(',', $productTypes);
        $dbVersion = $context->getVersion();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($dbVersion, '1.6.1', '<')) { // update an existing product attribute to add as soring options
						$eavSetup -> updateAttribute(\Magento\Catalog\Model\Product::ENTITY, 'allow_personalization', [
						'type' => 'int', 'backend' => '', 'frontend' => '', 'label' => 'Allow Personalization', 'input' => 'select',
						'class' => '', 'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
						'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
						'group' => 'Personalized Products', 'visible' => true, 'required' => false, 'user_defined' => true,
						'default' => 0, 'searchable' => false, 'filterable' => false, 'comparable' => false, 'visible_on_front' => true,
						'used_in_product_listing' => true, 'unique' => false, 'apply_to' =>  $productTypes
						]);
        }
    }
}