<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Grid;

use Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    protected $coreRegistry = null;

    /**
     * [__construct description]
     * @param \Magento\Backend\Block\Widget\Context $context  [description]
     * @param \Magento\Framework\Registry           $registry [description]
     * @param array                                 $data     [description]
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        $this->_objectId = 'cod_id';
        $this->_blockGroup = 'emipro_codExtracharge';
        $this->_controller = 'adminhtml_grid';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Rule'));

        $this->buttonList->add(
            'save_apply',
            [
                'class' => 'save',
                'label' => __('Save and Apply'),
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'save',
                            'target' => '#edit_form',
                            'eventData' => ['action' => ['args' => ['auto_apply' => 1]]],
                        ],
                    ],
                ],
            ]
        );

        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ],
            ],
            -100
        );

        $this->buttonList->update('delete', 'label', __('Delete Rule'));
    }

    /**
     * [getHeaderText description]
     * @return [type] [description]
     */
    public function getHeaderText()
    {
        $form_title = $this->coreRegistry->registry('emipro_form_data')->getTitle();
        if ($this->coreRegistry->registry('emipro_form_data')->getId()) {
            return __("Edit Post '%1'", $this->escapeHtml($form_title));
        } else {
            return __('New Grid');
        }
    }

    /**
     * [_getSaveAndContinueUrl description]
     * @return [type] [description]
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            'codextracharge/*/save',
            ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']
        );
    }

    /**
     * [_prepareLayout description]
     * @return [type] [description]
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
