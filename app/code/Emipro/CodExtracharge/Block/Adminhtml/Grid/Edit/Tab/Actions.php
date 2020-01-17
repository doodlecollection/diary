<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Grid\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Actions extends Generic implements TabInterface
{
    /**
     * [getTabLabel description]
     * @return [type] [description]
     */
    public function getTabLabel()
    {
        return __('Actions');
    }

    /**
     * [getTabTitle description]
     * @return [type] [description]
     */
    public function getTabTitle()
    {
        return __('Actions');
    }

    /**
     * [canShowTab description]
     * @return [type] [description]
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * [isHidden description]
     * @return boolean [description]
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * [_prepareForm description]
     * @return [type] [description]
     */
    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('current_promo_catalog_rule');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset(
            'action_fieldset',
            ['legend' => __('Pricing Structure Rules')]
        );

        $fieldset->addField(
            'cod_charges_type',
            'select',
            [
                'label' => __('Charges Type'),
                'name' => 'cod_charges_type',
                'options' => [
                    'percent' => __('PERCENTAGE'),
                    'fixed' => __('FIXED'),
                ],
            ]
        );

        $fieldset->addField(
            'cod_charges',
            'text',
            [
                'name' => 'cod_charges',
                'required' => true,
                'label' => __('Delivery Charges'),
                'after_element_html' => 'Set 0 For Free Cash On Delivery',
            ]
        );

        $form->setValues($model->getData());

        if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
