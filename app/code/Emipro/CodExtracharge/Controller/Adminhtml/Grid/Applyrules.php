<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

use Emipro\CodExtracharge\Helper\Data as HelperData;
use Magento\Backend\App\Action\Context;
use Magento\CatalogRule\Controller\Adminhtml\Promo\Catalog;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;

class Applyrules extends Catalog
{
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Date $dateFilter,
        HelperData $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context, $coreRegistry, $dateFilter);
    }
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
        try {
            $cod_id = $this->getRequest()->getParam('cod_id');
            $this->helper->applyrule($cod_id);
            $this->messageManager->addSuccess(__('The rules have been applied.'));
        } catch (\Exception $e) {
            $this->messageManager->addError('Unable to apply rules.');
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('codextracharge/*/');
    }

}
