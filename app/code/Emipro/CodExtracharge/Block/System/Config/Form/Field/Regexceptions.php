<?php

namespace Emipro\CodExtracharge\Block\System\Config\Form\Field;

class Regexceptions extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * Grid columns
     *
     * @var array
     */
    protected $customerGroupRenderer;
    protected $paymentRenderer;
    protected $chargeTypeRenderer;
    /**
     * Enable the "Add after" button or not
     *
     * @var bool
     */
    protected $addAfter = true;
    /**
     *  Label of add button
     *
     * @var string
     */
    protected $addButtonLabel;
    /**
     * Check if columns are defined, set template
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addButtonLabel = __('Add');
    }
    /**
     * Returns renderer for country element
     *
     * @return \Magento\Braintree\Block\Adminhtml\Form\Field\Countries
     */
    protected function getCustomerGroupRenderer()
    {
        if (!$this->customerGroupRenderer) {
            $this->customerGroupRenderer = $this->getLayout()->createBlock(
                \Magento\CatalogInventory\Block\Adminhtml\Form\Field\Customergroup::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->customerGroupRenderer;
    }

    /**
     * [_getPaymentRenderer description]
     * @return [type] [description]
     */
    protected function _getPaymentRenderer()
    {

        if (!$this->paymentRenderer) {
            $this->paymentRenderer = $this->getLayout()->createBlock(
                'Emipro\CodExtracharge\Block\System\Config\Form\Field\Activepayment',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->paymentRenderer;
    }

    /**
     * [_getChargeTypeRenderer description]
     * @return [type] [description]
     */
    protected function _getChargeTypeRenderer()
    {
        if (!$this->chargeTypeRenderer) {
            $this->chargeTypeRenderer = $this->getLayout()->createBlock(
                'Emipro\CodExtracharge\Block\System\Config\Form\Field\Extrachargetype',
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->chargeTypeRenderer;
    }

    /**
     * Prepare to render
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'amount',
            [
                'label' => __('Amount'),
                'required' => true,
                'style' => 'width:50px',
            ]
        );
        $this->addColumn(
            'payment_condition',
            [
                'label' => __('Condition'),
                'renderer' => $this->_getPaymentRenderer(),
            ]
        );
        $this->addColumn(
            'customer_group',
            [
                'label' => __('Customer Group'),
                'renderer' => $this->getCustomerGroupRenderer(),
            ]
        );
        $this->addColumn(
            'extra_charge_value',
            [
                'label' => __('Charge'),
                'required' => true,
                'style' => 'width:50px',
            ]
        );
        $this->addColumn(
            'extra_charge_type',
            [
                'label' => __('Type'),
                'renderer' => $this->_getChargeTypeRenderer(),
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * [_prepareArrayRow description]
     * @param  \Magento\Framework\DataObject $row [description]
     * @return [type]                             [description]
     */
    protected function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $options = [];

        $custGroup = $row->getData('customer_group');
        $options['option_' . $this->getCustomerGroupRenderer()->calcOptionHash($custGroup)] = 'selected="selected"';

        $paymentmethod = $row->getData('payment_condition');
        if ($paymentmethod) {
            $options['option_' . $this->_getPaymentRenderer()->calcOptionHash($paymentmethod)] = 'selected="selected"';
        }
        $chargetype = $row->getData('extra_charge_type');
        if ($chargetype) {
            $options['option_' . $this->_getChargeTypeRenderer()->calcOptionHash($chargetype)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }
}
