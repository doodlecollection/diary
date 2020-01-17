<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Grid\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Rule\Model\Condition\AbstractCondition;

class Conditions extends Generic implements TabInterface
{
    protected $rendererFieldset;

    protected $conditions;

    /**
     * [__construct description]
     * @param \Magento\Backend\Block\Template\Context              $context          [description]
     * @param \Magento\Framework\Registry                          $registry         [description]
     * @param \Magento\Framework\Data\FormFactory                  $formFactory      [description]
     * @param \Magento\Rule\Block\Conditions                       $conditions       [description]
     * @param \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset [description]
     * @param array                                                $data             [description]
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Rule\Block\Conditions $conditions,
        \Magento\Backend\Block\Widget\Form\Renderer\Fieldset $rendererFieldset,
        array $data = []
    ) {
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * [getTabLabel description]
     * @return [type] [description]
     */
    public function getTabLabel()
    {
        return __('Conditions');
    }

    /**
     * [getTabTitle description]
     * @return [type] [description]
     */
    public function getTabTitle()
    {
        return __('Conditions');
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

        $renderer = $this->rendererFieldset->setTemplate(
            'Emipro_CodExtracharge::promo/fieldset.phtml'
        )->setNewChildUrl(
            $this->getUrl('catalog_rule/promo_catalog/newConditionHtml/form/rule_conditions_fieldset')
        );

        $fieldset = $form->addFieldset(
            'conditions_fieldset',
            ['legend' => __('Conditions (don\'t add conditions if rule is applied to all products)')]
        )->setRenderer(
            $renderer
        );

        $fieldset->addField(
            'conditions',
            'text',
            ['name' => 'conditions', 'label' => __('Conditions'), 'title' => __('Conditions'), 'required' => true]
        )->setRule(
            $model
        )->setRenderer(
            $this->conditions
        );

        $form->setValues($model->getData());
        $this->setConditionFormName($model->getConditions(), '', 'rule_conditions_fieldset');
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param AbstractCondition $conditions
     * @param string $formName
     * @param string $jsFormName
     * @return void
     */
    private function setConditionFormName(AbstractCondition $conditions, $formName, $jsFormName)
    {
        $conditions->setFormName($formName);
        $conditions->setJsFormObject($jsFormName);

        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName, $jsFormName);
            }
        }
    }
}
