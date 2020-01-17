<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;

class Grid extends Action
{
    /**
     * [__construct description]
     * @param Context       $context           [description]
     * @param PageFactory   $resultPageFactory [description]
     * @param Rawfactory    $resultRawFactory  [description]
     * @param LayoutFactory $layoutFactory     [description]
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Rawfactory $resultRawFactory,
        LayoutFactory $layoutFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents(
            $this->layoutFactory->create()->createBlock(
                'Emipro\CodExtracharge\Block\Adminhtml\Grid\Grid',
                'grid.view.grid'
            )->toHtml()
        );
    }
}
