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

namespace Milople\Personalizedcool\Block\Adminhtml\Image\Edit;

use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Model\Image\Source\Status;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Data\Form as DataForm;

class Form extends Generic
{
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('image_form');
        $this->setTitle(__('Image Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('personalized_category_image');

        /** @var DataForm $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $form->setHtmlIdPrefix('image_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField(
                ImageInterface::ID,
                'hidden',
                ['name' => ImageInterface::ID]
            );
        }

        $fieldset->addField(
            ImageInterface::NAME,
            'text',
            ['name' => ImageInterface::NAME, 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );

        $fieldset->addField(
            ImageInterface::PATH,
            'image',
            ['name' => ImageInterface::PATH, 'label' => __('Image'), 'title' => __('Image'), 'required' => false]
        );


        $fieldset->addField(
            ImageInterface::STATUS,
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => ImageInterface::STATUS,
                'required' => true,
                'options' => $model->getAvailableStatuses()
            ]
        );



        if (!$model->getId()) {
            $model->setData('status', Status::ENABLED);
        }

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}