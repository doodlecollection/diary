<?php
namespace Emipro\CodExtracharge\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * [install description]
     * @param  SchemaSetupInterface   $setup   [description]
     * @param  ModuleContextInterface $context [description]
     * @return [type]                          [description]
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('emipro_codextracharge_rules'))
            ->addColumn(
                'rules_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Rule Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Name'
            )
            ->addColumn(
                'description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'Description'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'website_id'
            )
            ->addColumn(
                'customer_group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '64k',
                [],
                'customer_group_id'
            )
            ->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '0'],
                'Is Activ'
            )
            ->addColumn(
                'conditions_serialized',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [],
                'Condition Serialized'
            )
            ->addColumn(
                'actions_serialized',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [],
                'Action Serialized'
            )
            ->addColumn(
                'stop_rules_processing',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Stop Rule Processing'
            )
            ->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Sort Orders'
            )
            ->addColumn(
                'cod_charges_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Charges Type'
            )
            ->addColumn(
                'cod_charges',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                [],
                'Cod Charges'
            )
            ->addIndex(
                $installer->getIdxName('emipro_codextracharge_rules', ['is_active', 'sort_order']),
                ['is_active', 'sort_order']
            )
            ->setComment('emipro_codextracharge_rules');

        $installer->getConnection()->createTable($table);

        /**
         * Create table 'posts'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('emipro_codextracharge_products'))
            ->addColumn('excharge_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'ID')
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'EntityId'
            )
            ->addColumn('rules_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'unsigned' => true,
                'nullable' => false,
                'default' => '0',
            ], 'Rule Id')
            ->addIndex(
                $installer->getIdxName('emipro_codextracharge_products', ['rules_id']),
                ['rules_id']
            )
            ->addForeignKey(
                $installer->getFkName(
                    'emipro_codextracharge_products',
                    'rules_id',
                    'emipro_codextracharge_rules',
                    'rules_id'
                ),
                'rules_id',
                $installer->getTable('emipro_codextracharge_rules'),
                'rules_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )

            ->setComment('Emipro Codpayment Products');
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'emipro_codextracharge_products'
         */

        $table = $installer->getConnection()
            ->newTable($installer->getTable('emipro_codextracharge_website'))
            ->addColumn('rules_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'ID')
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'EntityId'
            )
            ->setComment('Emipro Codpayment website');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('emipro_codextracharge_customer_group'))
            ->addColumn('rules_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ], 'ID')
            ->addColumn(
                'customer_group_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'EntityId'
            )
            ->setComment('Emipro Codpayment Customer Group');
        $installer->getConnection()->createTable($table);
        /*
        alter table
         */
        $codcharge_fee = "` ADD  `codcharge_fee` DECIMAL( 10, 2 )  NULL";
        $codcharge_base_fee = "` ADD  `codcharge_base_fee` DECIMAL( 10, 2 )  NULL";
        $codcharge_fee_name = "` ADD  `codcharge_fee_name` varchar( 255)  NULL";

        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('quote_address') . $codcharge_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('quote_address') . $codcharge_base_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('quote_address') . $codcharge_fee_name
        );

        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_order') . $codcharge_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_order') . $codcharge_base_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_order') . $codcharge_fee_name
        );

        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_invoice') . $codcharge_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_invoice') . $codcharge_base_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_invoice') . $codcharge_fee_name
        );

        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_creditmemo') . $codcharge_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_creditmemo') . $codcharge_base_fee
        );
        $installer->run(
            "ALTER TABLE  `" . $setup->getTable('sales_creditmemo') . $codcharge_fee_name
        );

        $installer->endSetup();
    }
}
