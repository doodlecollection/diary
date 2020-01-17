<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

use Emipro\CodExtracharge\Model\RuleFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session\Proxy as BackendSession;
use Magento\CatalogRule\Controller\Adminhtml\Promo\Catalog;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Psr\Log\LoggerInterface;

class Save extends Catalog
{

    /**
     * [__construct description]
     * @param Action\Context $context      [description]
     * @param Registry       $coreRegistry [description]
     * @param Date           $dateFilter   [description]
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        Date $dateFilter,
        BackendSession $backendSession,
        RuleFactory $ruleFactory,
        LoggerInterface $loggerInterface,
        DataObject $dataObject
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->backendSession = $backendSession;
        $this->loggerInterface = $loggerInterface;
        $this->dataObject = $dataObject;
        parent::__construct($context, $coreRegistry, $dateFilter);
    }

    /**
     * [_isAllowed description]
     * @return boolean [description]
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Emipro_CodExtracharge::grid');
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->ruleFactory->create();
                $data = $this->getRequest()->getPostValue();

                $id = $this->getRequest()->getParam('cod_id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new LocalizedException(__('Wrong rule specified.'));
                    }
                }

                $validateResult = $model->validateData($this->dataObject->addData($data));
                if ($validateResult !== true) {
                    foreach ($validateResult as $errorMessage) {
                        $this->messageManager->addError($errorMessage);
                    }
                    $this->_getSession()->setPageData($data);
                    $this->_redirect('codextracharge/*/edit', ['cod_id' => $model->getId()]);
                    return;
                }

                $data['conditions'] = $data['rule']['conditions'];
                unset($data['rule']);

                $data['website_id'] = implode(',', $data['website_ids']);
                $data['customer_group_id'] = implode(',', $data['customer_group_id']);
                $model->loadPost($data);

                $this->backendSession->setPageData($model->getData());
                $model->save();

                $this->messageManager->addSuccess(__('You saved the rule.'));
                $this->backendSession->setPageData(false);

                if ($this->getRequest()->getParam('auto_apply')) {
                    $this->getRequest()->setParam('cod_id', $model->getId());
                    $this->_forward('applyrules');
                } else {
                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('codextracharge/*/edit', ['cod_id' => $model->getId()]);
                        return;
                    }
                    $this->_redirect('codextracharge/*/');
                }
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the rule data. Please review the error log.')
                );
                $this->loggerInterface->critical($e);
                $this->backendSession->setPageData($data);
                $this->_redirect('codextracharge/*/edit', ['cod_id' => $this->getRequest()->getParam('cod_id')]);
                return;
            }
        }
        $this->_redirect('codextracharge/*/');
    }
}
