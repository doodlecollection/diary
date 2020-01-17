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

use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Api\Data\ImageSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ImageRepositoryInterface
{
    /**
     * Save image
     *
     * @param ImageInterface $image
     * @return ImageInterface
     * @throws CouldNotSaveException
     */
    public function save(ImageInterface $image);

    /**
     * Retrieve image
     *
     * @param int $imageId
     * @return ImageInterface
     * @throws LocalizedException
     */
    public function getById($imageId);

    /**
     * Retrieve images matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ImageSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete image
     *
     * @param ImageInterface $image
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(ImageInterface $image);

    /**
     * Delete image by ID.
     *
     * @param int $imageId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($imageId);
}
