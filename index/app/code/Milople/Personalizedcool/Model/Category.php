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
use Milople\Personalizedcool\Model\Image\Source\Status;
use Milople\Personalizedcool\Model\ResourceModel\Category as CategoryResource;
use Milople\Personalizedcool\Model\ResourceModel\Image\CollectionFactory;
use Milople\Personalizedcool\Model\ResourceModel\Category\Collection;
use Milople\Personalizedcool\Model\ResourceModel\Image\Collection as ImageCollection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Category extends AbstractModel implements CategoryInterface, IdentityInterface
{
    const CACHE_TAG = 'personalized_category_category';
    protected $_cacheTag = self::CACHE_TAG;
    protected $_eventPrefix = 'personalized_category_category';
    protected $_eventObject = 'category';
    protected $imageCollectionFactory;
    public function __construct(
        Context $context,
        Registry $registry,
        CategoryResource $resource,
        CollectionFactory $imageCollectionFactory,
        Collection $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->imageCollectionFactory = $imageCollectionFactory;
    }
    protected function _construct()
    {
        $this->_init(CategoryResource::class);
    }
    public function getImageCollection()
    {
        $collection = $this->imageCollectionFactory->create()
            ->setCategoryFilter($this)
            ->addFilter('status', Status::ENABLED);

        return $collection;
    }
    public function getImagesPosition()
    {
        if (!$this->getId()) {
            return [];
        }

        $array = $this->getData('images_position');
        if ($array === null) {
            $array = $this->getResource()->getImagesPosition($this);
            $this->setData('images_position', $array);
        }
        return $array;
    }
    public function getAvailableStatuses()
    {
        return [
            Status::ENABLED => __('Enabled'),
            Status::DISABLED => __('Disabled')
        ];
    }
    public function getId()
    {
        return $this->getData(static::ID);
    }
    public function getName()
    {
        return $this->getData(static::NAME);
    }
    public function getStatus()
    {
        return $this->getData(static::STATUS);
    }
    public function getCreatedAt()
    {
        return $this->getData(static::CREATED_AT);
    }
    public function getUpdatedAt()
    {
        return $this->getData(static::UPDATED_AT);
    }
    public function setId($id)
    {
        $this->setData(static::ID, $id);
        return $this;
    }
    public function setName($name)
    {
        $this->setData(static::NAME, $name);
        return $this;
    }
    public function setStatus($status)
    {
        $this->setData(static::STATUS, $status);
        return $this;
    }
    public function setCreatedAt($createdAt)
    {
        $this->setData(static::CREATED_AT, $createdAt);
        return $this;
    }
     public function setUpdatedAt($updatedAt)
    {
        $this->setData(static::UPDATED_AT, $updatedAt);
        return $this;
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}