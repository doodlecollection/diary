<?php

namespace Emipro\CodExtracharge\Controller\Adminhtml\Grid;

use Emipro\CodExtracharge\Helper\Data as HelperData;
use Emipro\CodExtracharge\Model\RuleFactory;
use Magento\Backend\App\Action\Context;
use Magento\CatalogRule\Controller\Adminhtml\Promo\Catalog;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;

class Applyall extends Catalog
{
    /**
     * [__construct description]
     * @param Context     $context      [description]
     * @param Registry    $coreRegistry [description]
     * @param Date        $dateFilter   [description]
     * @param RuleFactory $ruleFactory  [description]
     * @param HelperData  $helper       [description]
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Date $dateFilter,
        RuleFactory $ruleFactory,
        HelperData $helper
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->helper = $helper;
        parent::__construct($context, $coreRegistry, $dateFilter);
    }
    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        try {
            $data = $this->ruleFactory->create()->getCollection();

            foreach ($data as $product) {
                $cod_id = $product->getRules_id();

                $this->helper->applyrule($cod_id);
            }
        } catch (\Exception $e) {
            $this->messageManager->addError('Unable to apply rules.');
        }
        $this->messageManager->addSuccess(__('The rules have been applied.'));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('codextracharge/*/');
    }
}
