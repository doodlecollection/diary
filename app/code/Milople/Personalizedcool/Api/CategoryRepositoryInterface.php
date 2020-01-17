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

namespace Milople\Personalizedcool\Api;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Api\Data\CategorySearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CategoryRepositoryInterface
{
    /**
     * Save Category
     *
     * @param CategoryInterface $Category
     * @return CategoryInterface
     * @throws CouldNotSaveException
     */
    public function save(CategoryInterface $category);

    /**
     * Retrieve Category
     *
     * @param int $CategoryId
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getById($categoryId);

    /**
     * Retrieve galleries matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CategorySearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete image
     *
     * @param CategoryInterface $Category
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(CategoryInterface $category);

    /**
     * Delete Category by ID.
     *
     * @param int $CategoryId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($categoryId);
}
