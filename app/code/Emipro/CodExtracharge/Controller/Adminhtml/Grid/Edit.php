<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

use Emipro\CodExtracharge\Model\RuleFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session\Proxy as BackendSession;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Edit extends Action
{

    protected $coreRegistry = null;

    protected $resultPageFactory;

    /**
     * [__construct description]
     * @param Action\Context                             $context           [description]
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory [description]
     * @param \Magento\Framework\Registry                $registry          [description]
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        BackendSession $backendSession,
        RuleFactory $ruleFactory,
        ScopeConfigInterface $ScopeConfigInterface
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $registry;
        $this->backendSession = $backendSession;
        $this->ruleFactory = $ruleFactory;
        $this->ScopeConfigInterface = $ScopeConfigInterface;
        parent::__construct($context);
    }

    /**
     * [_isAllowed description]
     * @return boolean [description]
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * [_initAction description]
     * @return [type] [description]
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Emipro_CodExtracharge::grid')
            ->addBreadcrumb(__('Grid'), __('Grid'))
            ->addBreadcrumb(__('Manage Grid'), __('Manage Grid'));
        return $resultPage;
    }

    /**
     * [getTopDestinations description]
     * @return [type] [description]
     */
    protected function getTopDestinations()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $destinations = (string) $this->ScopeConfigInterface->getValue(
            'general/country/destinations',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return !empty($destinations) ? explode(',', $destinations) : [];
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('cod_id');
        $model = $this->ruleFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getRulesId()) {
                $this->messageManager->addError(__('This rule no longer exists.'));
                $this->_redirect('catalog_rule/*');
                return;
            }
        }

        $data = $this->backendSession->getPageData(true);

        if (!empty($data)) {
            $model->addData($data);
        }
        $this->coreRegistry->register('current_promo_catalog_rule', $model);

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Post') : __('New Cash On Delivery Rule'),
            $id ? __('Edit Post') : __('New Cash On Delivery Rule')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Grids'));
        $title = $model->getRulesId() ? $model->getName() : __('New Cash On Delivery Rule');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
