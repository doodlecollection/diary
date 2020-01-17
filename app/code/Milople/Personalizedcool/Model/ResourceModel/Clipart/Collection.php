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
* @url         https://www.milople.com/magento2-extensions/personalizedcool-products.html
*
**/
namespace Milople\Personalizedcool\Model\ResourceModel\Clipart;

use Milople\Personalizedcool\Api\Data\ClipartInterface;
use Milople\Personalizedcool\Model\Category;
use Milople\Personalizedcool\Model\Clipart;
use Milople\Personalizedcool\Model\ResourceModel\Clipart as ClipartResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\DB\Select;

class Collection extends AbstractCollection
{
    /**
     * Identifier field name for collection items
     *
     * Can be used by collections with items without defined
     *
     * @var string
     */
    protected $_idFieldName = ClipartInterface::ID;

    /**
     * Initialization here
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Clipart::class, ClipartResource::class);
    }

    /**
     * @param $category
     * @param bool $sortByPosition
     * @return $this
     */
    public function setCategoryFilter($category, $sortByPosition = true)
    {
        if ($category === null || !($category instanceof Category) || $category->getId() === null) {
            return $this;
        }

        $this->getSelect()->joinInner(
            ['gi' => $this->getTable('personalized_clipart_category_image')],
            'main_table.clipart_id = gi.clipart_id',
            ['category_id' => 'gi.category_id', 'position' => 'position']
        )->where('gi.category_id = ?', $category->getId());

        if ($sortByPosition) {
            $this->getSelect()->order('gi.position ' . Select::SQL_ASC);
        }

        return $this;
    }
}