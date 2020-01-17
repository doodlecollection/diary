<?php

namespace Emipro\CodExtracharge\Controller\Index;

use Magento\Customer\Model\Session\Proxy as CustomerSession;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $customerSession;

    /**
     * [__construct description]
     * @param \Magento\Framework\App\Action\Context $context         [description]
     * @param \Magento\Customer\Model\Session\Proxy $customerSession [description]
     * @param BackendSession                        $backendSession  [description]
     * @param \Magento\Framework\Json\Helper\Data   $jsonData        [description]
     * @param \Magento\Framework\DataObject         $dataObject      [description]
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        CustomerSession $customerSession,
        \Magento\Framework\Json\Helper\Data $jsonData,
        \Magento\Framework\DataObject $dataObject,
        \Emipro\CodExtracharge\Helper\Data $helper,
        \Magento\Customer\Model\Address $customerAddress
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->jsonData = $jsonData;
        $this->dataObject = $dataObject;
        $this->helper = $helper;
        $this->customerAddress = $customerAddress;
    }

    /**
     * [execute description]
     * @return [type] [description]
     */
    public function execute()
    {
        $custId = $this->customerSession->getCustomer()->getId();
        $data['specificCountCond'] = 1;
        if ($this->customerSession->isLoggedIn()) {
            $address = $this->customerAddress->load($custId);
            $cntry = $address->getCountryId();
            $specificCount = $this->helper->getConfig('payment/cashondelivery/specificcountry');
            if ($specificCount != "") {
                $countArr = explode(",", $specificCount);
                $data['specificCountCond'] = (in_array($cntry, $countArr)) ? 1 : 0;
            }
        }
        $this->dataObject->setData($data);
        $this->getResponse()->representJson($this->dataObject->toJson());
    }
}
