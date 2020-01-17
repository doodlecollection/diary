<?php

namespace Emipro\CodExtracharge\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * [upgrade description]
     * @param  SchemaSetupInterface   $setup   [description]
     * @param  ModuleContextInterface $context [description]
     * @return [type]                          [description]
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $tableName = $setup->getTable('sales_order');

        // Check if the table already exists
        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $setup->getConnection()->addColumn(
                $setup->getTable('sales_order'),
                'cod_applied_rule',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => '2M',
                    'comment' => 'Cod Applied Products',
                ]
            );
        }

        $tableName = $setup->getTable('quote_address');

        if ($setup->getConnection()->isTableExists($tableName) == true) {
            $setup->getConnection()->addColumn(
                $setup->getTable('quote_address'),
                'cod_applied_rule',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => '2M',
                    'comment' => 'Cod Applied Products',
                ]
            );
        }

        $setup->endSetup();
    }
}
