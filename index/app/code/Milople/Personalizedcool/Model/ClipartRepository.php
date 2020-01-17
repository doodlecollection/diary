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
* @url         https://www.milople.com/magento2-extensions/personalized-products.html
*
**/
namespace Milople\Personalizedcool\Model;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Api\Data\CategorySearchResultsInterface;
use Milople\Personalizedcool\Api\Data\CategorySearchResultsInterfaceFactory;
use Milople\Personalizedcool\Api\Data\CategoryInterfaceFactory;
use Milople\Personalizedcool\Api\CategoryRepositoryInterface;
use Milople\Personalizedcool\Model\ResourceModel\Clipart as ClipartResource;
use Milople\Personalizedcool\Model\ResourceModel\Clipart\CollectionFactory as ClipartCollectionFactory;
use Milople\Personalizedcool\Model\ResourceModel\Clipart\Collection as ClipartCollection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Reflection\DataObjectProcessor;

class ClipartRepository implements CategoryRepositoryInterface
{
    /**
     * @var ClipartResource
     */
    protected $resource;

    /**
     * @var CategoryInterfaceFactory
     */
    protected $clipartFactory;

    /**
     * @var ClipartCollectionFactory
     */
    protected $clipartCollectionFactory;

    /**
     * @var CategorySearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var CategoryInterface[]
     */
    protected $instances = [];

    /**
     * ClipartRepository constructor
     *
     * @param ClipartResource $resource
     * @param CategoryInterfaceFactory $ClipartFactory
     * @param ClipartCollectionFactory $ClipartCollectionFactory
     * @param CategorySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        ClipartResource $resource,
        CategoryInterfaceFactory $clipartFactory,
        ClipartCollectionFactory $clipartCollectionFactory,
        CategorySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    )
    {
        $this->resource = $resource;
        $this->clipartFactory = $clipartFactory;
        $this->clipartCollectionFactory = $clipartCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Save gallery
     *
     * @param CategoryInterface $Clipart
     * @return CategoryInterface
     * @throws CouldNotSaveException
     */
    public function save(CategoryInterface $clipart)
    {
        if (false === ($clipart instanceof AbstractModel)) {
            throw new CouldNotSaveException(__('Invalid Model'));
        }

        /** @var AbstractModel $Clipart */
        try {
            $this->resource->save($clipart);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $clipart;
    }

    /**
     * Retrieve Clipart
     *
     * @param int $ClipartId
     * @return CategoryInterface
     * @throws NoSuchEntityException
     */
    public function getById($clipartId)
    {
        if (false === array_key_exists($clipartId, $this->instances)) {
            /** @var AbstractModel $Clipart */
            $Clipart = $this->clipartFactory->create();
            $this->resource->load($clipart, $clipartId);
            if (!$Clipart->getId()) {
                throw new NoSuchEntityException(__('Clipart with id "%1" does not exist.', $ClipartId));
            }
            $this->instances[$clipartId] = $clipart;
        }
        return $this->instances[$clipartId];
    }

    /**
     * Retrieve Cliparts matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CategorySearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var CategorySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var ClipartCollection $collection */
        $collection = $this->clipartCollectionFactory->create();
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
        /** @var Clipart $ClipartModel */
        foreach ($collection as $clipartModel) {
            $clipartData = $this->clipartFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $clipartData,
                $clipartModel->getData(),
                CategoryInterface::class
            );
            $galleries[] = $this->dataObjectProcessor->buildOutputDataArray(
                $clipartData,
                CategoryInterface::class
            );
        }
        $searchResults->setItems($teaserGroups);

        return $searchResults;
    }

    /**
     * Delete gallery
     *
     * @param CategoryInterface $Clipart
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(CategoryInterface $Clipart)
    {
        if (false === ($clipart instanceof AbstractModel)) {
            throw new CouldNotDeleteException(__('Invalid Model'));
        }
        /** @var AbstractModel $Clipart */
        try {
            $this->resource->delete($clipart);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete gallery by ID.
     *
     * @param int $ClipartId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($clipartId)
    {
        return $this->delete($this->getById($clipartId));
    }
}