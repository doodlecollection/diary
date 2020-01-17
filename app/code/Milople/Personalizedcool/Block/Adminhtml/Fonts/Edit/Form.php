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
namespace Milople\Personalizedcool\Block\Adminhtml\Fonts\Edit;
 
use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
 
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('fonts_form');
        $this->setTitle(__('Fonts Information'));
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        
        $model = $this->_coreRegistry->registry('personalized_fonts');
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'enctype'=>'multipart/form-data','action' => $this->getData('action'), 'method' => 'post']]
        );
        $form->setHtmlIdPrefix('fonts_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Fonts Information'), 'class' => 'fieldset-wide']
        );
 
        if ($model->getId()) {
            $fieldset->addField('font_id', 'hidden', ['name' => 'font_id']);
        }
 
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Font Name'), 'title' => __('Font Name'), 'required' => true]
        );
 
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        if (!$model->getId()) {
            $model->setData('status', '0');
        }
    
        $fieldset->addField(
            'ttf',
            'file',
            array(
                'name' => 'ttf',
                'label' => __('TTF File'),
                'title' => __('TTF File'),
                 'required'  => false
            )
        );
        $fieldset->addField(
            'woff',
            'file',
            [
                'name' => 'woff',
                'label' => __('WOFF File'),
                'title' => __('wOFF File'),
                'required'  => false
            ]
        );
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}
