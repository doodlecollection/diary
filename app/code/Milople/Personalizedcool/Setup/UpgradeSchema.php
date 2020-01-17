<?php
/**
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future. If you wish to customize the module for your
* needs please contact us to https://www.milople.com/contact-us.html
*
* @category    Ecommerce
* @package     Milople_Personlized
* @copyright   Copyright (c) 2016 Milople Technologies Pvt. Ltd. All Rights Reserved.
* @url         https://www.milople.com/magento2-extensions/personalizedcool-products-m2.html
*
**/
namespace Milople\Personalizedcool\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
class UpgradeSchema implements  UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,
                            ModuleContextInterface $context){
         $installer = $setup;
         $installer->startSetup();
         if (version_compare($context->getVersion(), '1.1.0') < 0) {
            /**
             * Create table 'personalized_category_category'
            */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('personalized_category_category')
            )->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Category Id'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Category Name'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Category Status'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Category Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Vategory Updated At'
            )->setComment(
                'Category Table'
            );

            $installer->getConnection()->createTable($table);

            /**
             * Create table 'personalized_category_image'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('personalized_category_image')
            )->addColumn(
                'image_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Image ID'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Image Name'
            )->addColumn(
                'path',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Image Path'
            )->addColumn(
                'status',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Image Status'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Image Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Image Updated At'
            )->setComment(
                'Image Table'
            );

            $installer->getConnection()->createTable($table);

            /**
             * Create table 'personalized_category_category_image'
             */
            $table = $installer->getConnection()->newTable(
            $installer->getTable('personalized_category_category_image')

            )->addColumn(
                'category_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                'Category ID'
            )->addColumn(
                'image_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
                'Image ID'
            )->addColumn(
                'position',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default' => '0'],
                'Position'
            )->addForeignKey(
                $installer->getFkName('personalized_category_category_image', 'category_id', 'personalized_category_category', 'category_id'),
                'category_id',
                $installer->getTable('personalized_category_category'),
                'category_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $installer->getFkName('personalized_category_category_image', 'image_id', 'personalized_category_image', 'image_id'),
                'image_id',
                $installer->getTable('personalized_category_image'),
                'image_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Category To Image Linkage Table'
            );

            $installer->getConnection()->createTable($table);
           
        }// Setup For 1.1.0
        if (version_compare($context->getVersion(), '1.2.0') < 0) {
             /**
             * Create table 'personalized_category_category_image'
             */
            $table = $installer->getConnection()->newTable(
            $installer->getTable('personalized_area')
            )->addColumn(
                'area_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Area ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Product ID'
            )->addColumn(
                'image_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'Image ID'
            )->addColumn(
                'x1',
                 Table::TYPE_SMALLINT,
                null,
                 ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'x1'
            )->addColumn(
                'y1',
                 Table::TYPE_SMALLINT,
                null,
                 ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'y1'
            )->addColumn(
                'x2',
                 Table::TYPE_SMALLINT,
                null,
                 ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'x2'
            )->addColumn(
                'y2',
                 Table::TYPE_SMALLINT,
                null,
                 ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'y2'
            )->addColumn(
                'width',
                 Table::TYPE_SMALLINT,
                null,
                 ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'width'
            )->addColumn(
                'height',
                 Table::TYPE_SMALLINT,
                null,
                 ['unsigned' => true,'nullable' => false, 'default' => '0'],
                'height'
            )->setComment(
                'Store Image Area'
            );

            $installer->getConnection()->createTable($table);
        }// Setup upgrade 1.2.0
        $installer->endSetup();
    }
}