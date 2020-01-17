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
namespace Milople\Personalizedcool\Model;
use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Model\Clipart\Source\Status;
use Milople\Personalizedcool\Model\ResourceModel\Clipart as ClipartResource;
use Milople\Personalizedcool\Model\ResourceModel\Clipart\Collection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Clipart extends AbstractModel implements CategoryInterface, IdentityInterface
{
    const CACHE_TAG = 'personalized_category_clipart';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * When you use true - all cache will be clean
     *
     * @var string|array|bool
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'personalized_category_clipart';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'clipart';

    /**
     * Gallery constructor
     *
     * @param Context $context
     * @param Registry $registry
     * @param ClipartResource $resource
     * @param StoreManagerInterface $storeManager
     * @param Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ClipartResource $resource,
        StoreManagerInterface $storeManager,
        Collection $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->storeManager = $storeManager;
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ClipartResource::class);
    }

    /**
     * getAvailableStatuses
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            Status::ENABLED => __('Enabled'),
            Status::DISABLED => __('Disabled')
        ];
    }

    /**
     * Retrieve id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(static::ID);
    }

    /**
     * Retrieve name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(static::NAME);
    }

    /**
     * Retrieve path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getData(static::PATH);
    }

    /**
     * Retrieve status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->getData(static::STATUS);
    }

    /**
     * Retrieve created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(static::CREATED_AT);
    }

    /**
     * Retrieve updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(static::UPDATED_AT);
    }

    /**
     * Set id
     *
     * @param int $id
     * @return ClipartInterface
     */
    public function setId($id)
    {
        $this->setData(static::ID, $id);
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ClipartInterface
     */
    public function setName($name)
    {
        $this->setData(static::NAME, $name);
        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return ClipartInterface
     */
    public function setPath($path)
    {
        $this->setData(static::PATH, $path);
        return $this;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return ClipartInterface
     */
    public function setStatus($status)
    {
        $this->setData(static::STATUS, $status);
        return $this;
    }

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return ClipartInterface
     */
    public function setCreatedAt($createdAt)
    {
        $this->setData(static::CREATED_AT, $createdAt);
        return $this;
    }


    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return ClipartInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->setData(static::UPDATED_AT, $updatedAt);
        return $this;
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return implode('/', [
            rtrim($this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA), '/'),
            $this->getPath()
        ]);
    }
}