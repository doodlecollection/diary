<?php

namespace Emipro\CodExtracharge\Block\Adminhtml;

use Magento\Backend\Block\Widget\Container;

class Gridlist extends Container
{
    /**
     * [_prepareLayout description]
     * @return [type] [description]
     */
    protected function _prepareLayout()
    {
        $this->setTemplate("grid/view.phtml");
        $this->buttonList->add(
            'add_new',
            [
                'label' => __('Add New Rule'),
                'class' => 'primary',
                'button_class' => '',
                'onclick' => "setLocation('" . $this->_getCreateUrl() . "')",
            ]
        );

        $this->buttonList->add(
            'apply_rules',
            [
                'label' => __('Apply Rules'),
                'onclick' => "location.href='" . $this->getUrl('codextracharge/*/Applyall') . "'",
                'class' => 'apply',
            ]
        );

        $this->setChild(
            'grid',
            $this->getLayout()->createBlock('Emipro\CodExtracharge\Block\Adminhtml\Grid\Grid', 'grid.view.grid')
        );
        return parent::_prepareLayout();
    }

    /**
     * [_getAddButtonOptions description]
     * @return [type] [description]
     */
    protected function _getAddButtonOptions()
    {

        $splitButtonOptions[] = [
            'label' => __('Add New'),
            'onclick' => "setLocation('" . $this->_getCreateUrl() . "')",
        ];

        return $splitButtonOptions;
    }

    /**
     * [_getCreateUrl description]
     * @return [type] [description]
     */
    protected function _getCreateUrl()
    {
        return $this->getUrl('codextracharge/*/new');
    }

    /**
     * [getGridHtml description]
     * @return [type] [description]
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }
}
