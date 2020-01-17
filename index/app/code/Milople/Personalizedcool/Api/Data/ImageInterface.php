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

interface ImageInterface
{
    
    const ID = 'image_id';
    const NAME = 'name';
    const PATH = 'path';
    const STATUS = 'status';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const RELATIVE_PATH_FROM_MEDIA_TO_FILE = 'personalized/category/image/';

    /**
     * Retrieve id
     *
     * @return int
     */
    public function getId();

    /**
     * Retrieve name
     *
     * @return string
     */
    public function getName();

    /**
     * Retrieve path
     *
     * @return string
     */
    public function getPath();

    /**
     * Retrieve status
     *
     * @return int
     */
    public function getStatus();

    /**
     * Retrieve created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Retrieve updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Set id
     *
     * @param int $id
     * @return ImageInterface
     */
    public function setId($id);

    /**
     * Set name
     *
     * @param string $name
     * @return ImageInterface
     */
    public function setName($name);

    /**
     * Set path
     *
     * @param string $path
     * @return ImageInterface
     */
    public function setPath($path);

    /**
     * Set status
     *
     * @param int $status
     * @return ImageInterface
     */
    public function setStatus($status);

    /**
     * Set created at
     *
     * @param string $createdAt
     * @return ImageInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Set updated at
     *
     * @param string $updatedAt
     * @return ImageInterface
     */
    public function setUpdatedAt($updatedAt);
}