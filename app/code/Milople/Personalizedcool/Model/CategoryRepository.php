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

namespace Milople\Personalizedcool\Model;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Api\Data\CategorySearchResultsInterface;
use Milople\Personalizedcool\Api\Data\CategorySearchResultsInterfaceFactory;
use Milople\Personalizedcool\Api\Data\CategoryInterfaceFactory;
use Milople\Personalizedcool\Api\CategoryRepositoryInterface;
use Milople\Personalizedcool\Model\ResourceModel\Category as ResourceCategory;
use Milople\Personalizedcool\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Milople\Personalizedcool\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Reflection\DataObjectProcessor;

class CategoryRepository implements CategoryRepositoryInterface
{
   
    protected $resource;
    protected $categoryFactory;
    protected $categoryCollectionFactory;
    protected $searchResultsFactory;
    protected $dataObjectHelper;
    protected $dataObjectProcessor;
    protected $instances = [];

    public function __construct(
        ResourceCategory $resource,
        CategoryInterfaceFactory $categoryFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        CategorySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    )
    {
        $this->resource = $resource;
        $this->categoryFactory = $categoryFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    public function save(CategoryInterface $category)
    {
        if (false === ($category instanceof AbstractModel)) {
            throw new CouldNotSaveException(__('Invalid Model'));
        }

        /** @var AbstractModel $category */
        try {
            $this->resource->save($category);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $category;
    }
    public function getById($categoryId)
    {
        if (false === array_key_exists($categoryId, $this->instances)) {
            /** @var AbstractModel $category */
            $category = $this->categoryFactory->create();
            $this->resource->load($category, $categoryId);
            if (!$category->getId()) {
                throw new NoSuchEntityException(__('Category with id "%1" does not exist.', $categoryId));
            }
            $this->instances[$categoryId] = $category;
        }
        return $this->instances[$categoryId];
    }
     public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var CategorySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var CategoryCollection $collection */
        $collection = $this->categoryCollectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $teaserGroups = [];
        /** @var Category $categoryModel */
        foreach ($collection as $categoryModel) {
            $categoryData = $this->categoryFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $categoryData,
                $categoryModel->getData(),
                CategoryInterface::class
            );
            $galleries[] = $this->dataObjectProcessor->buildOutputDataArray(
                $categoryData,
                CategoryInterface::class
            );
        }
        $searchResults->setItems($teaserGroups);

        return $searchResults;
    }

    public function delete(CategoryInterface $category)
    {
        if (false === ($category instanceof AbstractModel)) {
            throw new CouldNotDeleteException(__('Invalid Model'));
        }
        /** @var AbstractModel $category */
        try {
            $this->resource->delete($category);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }
    public function deleteById($categoryId)
    {
        return $this->delete($this->getById($categoryId));
    }
}