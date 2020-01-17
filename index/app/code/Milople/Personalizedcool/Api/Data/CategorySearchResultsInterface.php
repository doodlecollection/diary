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

namespace Milople\Personalizedcool\Api\Data;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface CategorySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Category list.
     *
     * @return CategoryInterface[]
     */
    public function getItems();

    /**
     * Set galleries list.
     *
     * @param CategoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
