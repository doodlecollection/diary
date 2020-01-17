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
namespace Milople\Personalizedcool\Block\Adminhtml\Fonts;
 
use Magento\Backend\Block\Widget\Form\Container;
 
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
 
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
 
    /**
     * Department edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'font_id';
        $this->_blockGroup = 'Milople_Personalizedcool';
        $this->_controller = 'adminhtml_fonts';
 
        parent::_construct();
 
        if ($this->_isAllowedAction('Milople_Personalizedcool::fonts_save')) {
            $this->buttonList->update('save', 'label', __('Save Fonts'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }
        if ($this->_isAllowedAction('Milople_Personalizedcool::fonts_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Fonts'));
        } else {
            $this->buttonList->remove('delete');
        }
 
    }
 
    /**
     * Get header with Fonts name
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        
        if ($this->_coreRegistry->registry('personalized_fonts')->getId()) {
            return __("Edit Font '%1'", $this->escapeHtml($this->_coreRegistry->registry('personalized_fonts')->getName()));
        } else {
            return __('New Font');
        }
    }
 
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
 
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('fonts/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
