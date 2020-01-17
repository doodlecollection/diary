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
namespace Milople\Personalizedcool\Model\ResourceModel;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Category extends AbstractDb
{
    const TABLE_NAME = 'personalized_category_category';
    protected $categoryImageTable = null;
    protected $eventManager = null;
    protected $logger;
    
    public function __construct(
        Context $context,
        ManagerInterface $eventManager,
        \Psr\Log\LoggerInterface $logger,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
        $this->logger = $logger;
        $this->eventManager = $eventManager;
    }

   
    protected function _construct()
    {
        $this->_init(static::TABLE_NAME, CategoryInterface::ID);
    }

   
    public function getImagesPosition(AbstractModel $object)
    {
        $connection = $this->getConnection();
        
        $select = $connection->select()->from(
            $this->getCategoryImageTable(),
            ['image_id', 'position']
        )->where(
            'category_id = :category_id'
        );

        $binds = [':category_id' => (int) $object->getId()];

        return $connection->fetchPairs($select, $binds);
    }

    /**
     * Perform actions after object save
     *
     * @param AbstractModel $object
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveImages($object);
        return parent::_afterSave($object);
    }

    /**
     * Category product table name getter
     *
     * @return string
     */
    public function getCategoryImageTable()
    {
        if ($this->categoryImageTable === null) {
            $this->categoryImageTable = $this->getTable('personalized_category_category_image');
        }

        return $this->categoryImageTable;
    }

    protected function saveImages(AbstractModel $category)
    {
        $category->setIsChangedImageList(false);
        $id = $category->getId();
      
        /**
         * new Category-image relationships
         */
        $images = $category->getPostedImages();
     
        /**
         * Example re-save Category
         */
         if ($images === null) {
            return $this;
        }

        /**
         * old Category-image relationships
         */
        $oldImages = $category->getImagesPosition();
         $insert = array_diff_key($images, $oldImages);
        $delete = array_diff_key($oldImages, $images);

        /**
         * Find image ids which are presented in both arrays
         * and saved before (check $oldImages array)
         */
        $update = array_intersect_key($images, $oldImages);
        $update = array_diff_assoc($update, $oldImages);

        $connection = $this->getConnection();

        /**
         * Delete images from Category
         */
        if (!empty($delete)) {
            $cond = ['image_id IN(?)' => array_keys($delete), 'category_id=?' => $id];
            $connection->delete($this->getCategoryImageTable(), $cond);
        }

        /**
         * Add images to Category
         */
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $imageId => $position) {
                $data[] = [
                    'category_id' => (int) $id,
                    'image_id' => (int) $imageId,
                    'position' => (int) $position,
                ];
            }
            $connection->insertMultiple($this->getCategoryImageTable(), $data);
        }

        /**
         * Update image positions in Category
         */
        if (!empty($update)) {
            foreach ($update as $imageId => $position) {
                $where = ['category_id = ?' => (int) $id, 'image_id = ?' => (int) $imageId];
                $bind = ['position' => (int) $position];
                $connection->update($this->getCategoryImageTable(), $bind, $where);
            }
        }

        if (!empty($insert) || !empty($delete)) {
            $imageIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->eventManager->dispatch(
                'personalized_category_category_change_images',
                ['category' => $category, 'image_ids' => $imageIds]
            );
        }

        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $category->setIsChangedImageList(true);

            /**
             * Setting affected images to Category for third party engine index refresh
             */
            $imageIds = array_keys($insert + $delete + $update);
            $category->setAffectedImageIds($imageIds);
        }

        return $this;
    }
}