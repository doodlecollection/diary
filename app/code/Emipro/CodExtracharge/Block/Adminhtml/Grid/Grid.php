<?php

namespace Emipro\CodExtracharge\Block\Adminhtml\Grid;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $moduleManager;

    protected $gridFactory;

    protected $status;

    /**
     * [__construct description]
     * @param \Magento\Backend\Block\Template\Context  $context       [description]
     * @param \Magento\Backend\Helper\Data             $backendHelper [description]
     * @param \Emipro\CodExtracharge\Model\RuleFactory $gridFactory   [description]
     * @param \Emipro\CodExtracharge\Model\Status      $status        [description]
     * @param \Magento\Framework\Module\Manager        $moduleManager [description]
     * @param array                                    $data          [description]
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Emipro\CodExtracharge\Model\RuleFactory $gridFactory,
        \Emipro\CodExtracharge\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {
        $this->gridFactory = $gridFactory;
        $this->status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * [_construct description]
     * @return [type] [description]
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('gridGrid');
        $this->setDefaultSort('cod_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('grid_record');
    }

    /**
     * [_prepareCollection description]
     * @return [type] [description]
     */
    protected function _prepareCollection()
    {
        $collection = $this->gridFactory->create()->getCollection();

        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * [_prepareColumns description]
     * @return [type] [description]
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'rules_id',
            [
                'header' => __('ID'),
                'width' => '100px',
                'type' => 'number',
                'index' => 'rules_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Rule Name'),
                'index' => 'name',
                'type' => 'text',
            ]
        );
        $this->addColumn(
            'is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'options' => $this->status->toOptionArray(),
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'codextracharge/*/edit',
                        ],
                        'field' => 'cod_id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );

        $block = $this->getLayout()->getBlock('grid.bottom.links');

        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

    /**
     * [getGridUrl description]
     * @return [type] [description]
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * [getRowUrl description]
     * @param  [type] $row [description]
     * @return [type]      [description]
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'codextracharge/*/edit',
            ['cod_id' => $row->getId()]
        );
    }
}
