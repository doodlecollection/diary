<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

use Emipro\CodExtracharge\Model\RuleFactory;
use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * [__construct description]
     * @param Context     $context     [description]
     * @param RuleFactory $ruleFactory [description]
     */
    public function __construct(
        Action\Context $context,
        RuleFactory $ruleFactory
    ) {
        parent::__construct($context);
        $this->ruleFactory = $ruleFactory;
    }
    /**
     * [_isAllowed description]
     * @return boolean [description]
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Emipro_CodExtracharge::view');
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('cod_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->ruleFactory->create();
                $model->load($id);

                $model->delete();
                $this->messageManager->addSuccess(__('The rule has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['cod_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
