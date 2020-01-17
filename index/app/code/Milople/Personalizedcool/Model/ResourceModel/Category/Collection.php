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
namespace Milople\Personalizedcool\Model\ResourceModel\Category;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Model\Category;
use Milople\Personalizedcool\Model\ResourceModel\Category as CategoryResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
   
    protected $_idFieldName = CategoryInterface::ID;
    protected function _construct()
    {
        $this->_init(Category::class, CategoryResource::class);
    }
}