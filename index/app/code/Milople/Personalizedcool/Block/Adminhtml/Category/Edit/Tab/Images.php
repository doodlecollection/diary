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

namespace Milople\Personalizedcool\Block\Adminhtml\Category\Edit\Tab;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Model\ResourceModel\Image\Collection;
use Milople\Personalizedcool\Model\ResourceModel\Image\CollectionFactory;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;

class Images extends Extended
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var  CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param CollectionFactory $collectionFactory
     * @param Registry $coreRegistry
     * @param array $data
     */
   public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        Registry $coreRegistry,
        array $data = []
    )
    {
        $this->coreRegistry = $coreRegistry;
        $this->collectionFactory = $collectionFactory;

        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('category_image_grid');
        $this->setDefaultSort('image_id', 'desc');
        $this->setUseAjax(true);
    }

    /**
     * 
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_category', [
            'type' => 'checkbox',
            'name' => 'in_category',
            'values' => $this->getSelectedImages(),
            'index' => 'image_id',
            'filter_index' => 'main_table.image_id',
            'header_css_class' => 'col-select col-massaction',
            'column_css_class' => 'col-select col-massaction'
        ]);

        $this->addColumn(ImageInterface::ID, [
            'header' => __('Image Id'),
            'index' => 'image_id',
            'filter_index' => 'main_table.image_id',
        ]);

        $this->addColumn('image_name', [
            'header' => __('Name'),
            'index' => ImageInterface::NAME
        ]);

        $this->addColumn('image_created_at', [
            'header' => __('Created At'),
            'index' => ImageInterface::CREATED_AT
        ]);

        $this->addColumn('image_updated_at', [
            'header' => __('Updated Time'),
            'index' => ImageInterface::UPDATED_AT
        ]);

        $this->addColumn('position', [
                'header' => __('Position'),
                'name' => 'position',
                'type' => 'number',
                'validate_class' => 'validate-number',
                'index' => 'position',
                'editable' => true,
                'edit_only' => !$this->getCategory()->getId(),
                'header_css_class' => 'col-position',
                'column_css_class' => 'col-position',
                'filter_condition_callback' => [$this, 'filterImagesPosition']
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Add filter
     *
     * @param Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_category') {
            $imageIds = $this->getSelectedImages();
            if (empty($imageIds)) {
                $imageIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('main_table.image_id', ['in' => $imageIds]);
            } elseif (!empty($imageIds)) {
                $this->getCollection()->addFieldToFilter('main_table.image_id', ['nin' => $imageIds]);
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Retrieve selected images
     *
     * @return array
     */
    protected function getSelectedImages()
    {
        return array_keys($this->getSelectedImagePositions());
    }

    /**
     * @return array
     */
    public function getSelectedImagePositions()
    {
      
        if (false === $this->hasData('selected_image_positions')) {
            $images = [];
            foreach ($this->getCategory()->getImagesPosition() as $imageId => $imagePosition) {
                $images[$imageId] = ['position' => $imagePosition];
            }

            $this->setData('selected_image_positions', $images);
        }


        return $this->getData('selected_image_positions');
    }

    /**
     * @return CategoryInterface
     */
    public function getCategory()
    {
        return $this->coreRegistry->registry('personalized_category_category');
    }

    /**
     * Apply `position` filter to images grid.
     *
     * @param Collection $collection
     * @param Extended $column
     * @return $this
     */
    public function filterImagesPosition($collection, $column)
    {
        $collection->addFieldToFilter($column->getIndex(), $column->getFilter()->getCondition());
        return $this;
    }
}