<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

class NewAction extends \Magento\Backend\App\Action
{
    protected $resultForwardFactory;

    /**
     * [__construct description]
     * @param \Magento\Backend\App\Action\Context               $context              [description]
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory [description]
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }

    /**
     * [_isAllowed description]
     * @return boolean [description]
     */
    protected function _isAllowed()
    {
        return true;
    }
}
