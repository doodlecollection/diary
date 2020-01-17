<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Grid\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface
{

    protected $systemStore;

    protected $groupRepository;

    protected $searchCriteriaBuilder;

    protected $objectConverter;

    /**
     * [__construct description]
     * @param \Magento\Backend\Block\Template\Context        $context               [description]
     * @param \Magento\Framework\Registry                    $registry              [description]
     * @param \Magento\Framework\Data\FormFactory            $formFactory           [description]
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository       [description]
     * @param \Magento\Framework\Api\SearchCriteriaBuilder   $searchCriteriaBuilder [description]
     * @param \Magento\Framework\Convert\DataObject          $objectConverter       [description]
     * @param \Magento\Store\Model\System\Store              $systemStore           [description]
     * @param array                                          $data                  [description]
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        $this->groupRepository = $groupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->objectConverter = $objectConverter;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * [getTabLabel description]
     * @return [type] [description]
     */
    public function getTabLabel()
    {
        return __('Rule Information');
    }

    /**
     * [getTabTitle description]
     * @return [type] [description]
     */
    public function getTabTitle()
    {
        return __('Rule Information');
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General Information')]);

        if ($model->getId()) {
            $fieldset->addField('rules_id', 'hidden', ['name' => 'rules_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Rule Name'), 'title' => __('Rule Name'), 'required' => true]
        );

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'style' => 'height: 100px;',
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['0' => __('Disabled'), '1' => __('Enabled')],
            ]
        );

        if ($this->_storeManager->isSingleStoreMode()) {
            $websiteIds = $this->_storeManager->getStore(true)->getWebsiteId();
            $fieldset->addField('website_ids', 'hidden', ['name' => 'website_ids[]', 'value' => $websiteIds]);
            $model->setWebsiteIds($websiteIds);
        } else {
            $fields = $fieldset->addField(
                'website_ids',
                'multiselect',
                [
                    'name' => 'website_ids[]',
                    'label' => __('Websites'),
                    'title' => __('Websites'),
                    'required' => true,
                    'values' => $this->systemStore->getWebsiteValuesForForm(),
                ]
            );
            $renderers = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $fields->setRenderer($renderers);
        }

        $customerGroups = $this->groupRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        $fieldset->addField(
            'customer_group_id',
            'multiselect',
            [
                'name' => 'customer_group_id[]',
                'label' => __('Customer Groups'),
                'title' => __('Customer Groups'),
                'required' => true,
                'values' => $this->objectConverter->toOptionArray($customerGroups, 'id', 'code'),
            ]
        );

        $fieldset->addField('sort_order', 'text', ['name' => 'sort_order', 'label' => __('Priority')]);

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
