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
namespace Milople\Personalizedcool\Model\ResourceModel\Image;

use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Model\Category;
use Milople\Personalizedcool\Model\Image;
use Milople\Personalizedcool\Model\ResourceModel\Image as ImageResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\DB\Select;

class Collection extends AbstractCollection
{
    protected $_idFieldName = ImageInterface::ID;
    protected function _construct()
    {
        $this->_init(Image::class, ImageResource::class);
    }

    public function setCategoryFilter($category, $sortByPosition = true)
    {
        if ($category === null || !($category instanceof Category) || $category->getId() === null) {
            return $this;
        }

        $this->getSelect()->joinInner(
            ['gi' => $this->getTable('personalized_category_category_image')],
            'main_table.image_id = gi.image_id',
            ['Category_id' => 'gi.category_id', 'position' => 'position']
        )->where('gi.category_id = ?', $category->getId());

        if ($sortByPosition) {
            $this->getSelect()->order('gi.position ' . Select::SQL_ASC);
        }

        return $this;
    }
}