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

use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Api\Data\ImageSearchResultsInterface;
use Milople\Personalizedcool\Api\Data\ImageSearchResultsInterfaceFactory;
use Milople\Personalizedcool\Api\Data\ImageInterfaceFactory;
use Milople\Personalizedcool\Api\ImageRepositoryInterface;
use Milople\Personalizedcool\Model\ResourceModel\Image as ImageResource;
use Milople\Personalizedcool\Model\ResourceModel\Image\CollectionFactory as ImageCollectionFactory;
use Milople\Personalizedcool\Model\ResourceModel\Image\Collection as ImageCollection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Reflection\DataObjectProcessor;

class ImageRepository implements ImageRepositoryInterface
{
   
    protected $resource;
    protected $imageFactory;
    protected $imageCollectionFactory;
    protected $searchResultsFactory;
    protected $dataObjectHelper;
    protected $dataObjectProcessor;
    protected $instances = [];

    /**
     * ImageRepository constructor
    */
    public function __construct(
        ImageResource $resource,
        ImageInterfaceFactory $imageFactory,
        ImageCollectionFactory $imageCollectionFactory,
        ImageSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor
    )
    {
        $this->resource = $resource;
        $this->imageFactory = $imageFactory;
        $this->imageCollectionFactory = $imageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * Save category
     *
    */
    public function save(ImageInterface $image)
    {
        if (false === ($image instanceof AbstractModel)) {
            throw new CouldNotSaveException(__('Invalid Model'));
        }

        /** @var AbstractModel $image */
        try {
            $this->resource->save($image);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $image;
    }

    /**
     * Retrieve image
     *
    */
    public function getById($imageId)
    {
        if (false === array_key_exists($imageId, $this->instances)) {
            /** @var AbstractModel $image */
            $image = $this->imageFactory->create();
            $this->resource->load($image, $imageId);
            if (!$image->getId()) {
                throw new NoSuchEntityException(__('Image with id "%1" does not exist.', $imageId));
            }
            $this->instances[$imageId] = $image;
        }
        return $this->instances[$imageId];
    }

    /**
     * Retrieve images matching the specified criteria
     *
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var ImageSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var ImageCollection $collection */
        $collection = $this->imageCollectionFactory->create();
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
        /** @var Image $imageModel */
        foreach ($collection as $imageModel) {
            $imageData = $this->imageFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $imageData,
                $imageModel->getData(),
                ImageInterface::class
            );
            $galleries[] = $this->dataObjectProcessor->buildOutputDataArray(
                $imageData,
                ImageInterface::class
            );
        }
        $searchResults->setItems($teaserGroups);

        return $searchResults;
    }

    /**
     * Delete category
    */
    public function delete(ImageInterface $image)
    {
        if (false === ($image instanceof AbstractModel)) {
            throw new CouldNotDeleteException(__('Invalid Model'));
        }
        /** @var AbstractModel $image */
        try {
            $this->resource->delete($image);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
    * Delete category by ID.
    */
    public function deleteById($imageId)
    {
        return $this->delete($this->getById($imageId));
    }
}