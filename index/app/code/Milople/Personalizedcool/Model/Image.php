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
use Milople\Personalizedcool\Model\Image\Source\Status;
use Milople\Personalizedcool\Model\ResourceModel\Image as ImageResource;
use Milople\Personalizedcool\Model\ResourceModel\Image\Collection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Image extends AbstractModel implements ImageInterface, IdentityInterface
{
    const CACHE_TAG = 'personalized_category_image';
    protected $storeManager;
    protected $_cacheTag = self::CACHE_TAG;
    protected $_eventPrefix = 'personalized_category_image';
    protected $_eventObject = 'image';

    /**
     * Image constructor
    */
    public function __construct(
        Context $context,
        Registry $registry,
        ImageResource $resource,
        StoreManagerInterface $storeManager,
        Collection $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
    }

    protected function _construct()
    {
        $this->_init(ImageResource::class);
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

    public function getPath()
    {
        return $this->getData(static::PATH);
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
    public function setPath($path)
    {
        $this->setData(static::PATH, $path);
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
    public function getUrl()
    {
        return implode('/', [
            rtrim($this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA), '/'),
            $this->getPath()
        ]);
    }
}