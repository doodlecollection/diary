<?php

namespace Vikas\ShipwayTracking\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable(
            $setup->getTable('shipway_couriermapping')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'default_courier',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'default Courier Name'
        )->addColumn(
            'shipway_courierid',
            Table::TYPE_INTEGER,
            11,
            ['nullable' => false],
            'Shipway Courierid'
        )->addColumn(
            'courier_type',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'courier Type'
        )->addIndex(
            $setup->getIdxName('shipway_couriermapping', ['shipway_courierid']),
            ['shipway_courierid']
        )->setComment(
            'shipway Courierid'
        );
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
