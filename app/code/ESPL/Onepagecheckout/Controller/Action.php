<?php
/**
 * ESPL_Onepagecheckout extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ESPL  License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.elitechsystems.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@elitechsystems.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.elitechsystems.com for more information.
 *
 * @category   ESPL
 * @package    ESPL_Onepagecheckout
 * @author-email  info@elitechsystems.com
 * @copyright  Copyright 2017 � elitechsystems.com. All Rights Reserved
 */
namespace ESPL\Onepagecheckout\Controller;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Action\Action as MagentoAction;
use Magento\Customer\Model\Session\Proxy as CustomerSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Checkout\Model\Type\Onepage;
use ESPL\Onepagecheckout\Helper\Data as esplHelper;
use Magento\Checkout\Helper\Data as CheckoutHelper;
use Magento\Checkout\Model\Session\Proxy as CheckoutSession;
use Magento\Framework\View\Result\PageFactory;

abstract class Action extends MagentoAction
{

    public $customerSession;
    public $customerRepository;
    public $accountManagement;
    public $onepage;
    public $checkoutHelper;
    public $resultRawFactory;
    public $checkoutSession;
    public $resultPageFactory;
    public $helper;

    public function __construct(
        Context $context,
        CustomerSession $customerSession,
        Onepage $onepage,
        CheckoutHelper $checkoutHelper,
        CustomerRepositoryInterface $customerRepository,
        RawFactory $resultRawFactory,
        CheckoutSession $checkoutSession,
        PageFactory $resultPageFactory,
        esplHelper $esplHelper,
        AccountManagementInterface $accountManagement
    ) {
        $this->helper = $esplHelper;
        $this->resultPageFactory = $resultPageFactory;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->onepage = $onepage;
        $this->customerRepository = $customerRepository;
        $this->accountManagement = $accountManagement;
        $this->resultRawFactory = $resultRawFactory;
        $this->checkoutHelper = $checkoutHelper;
        parent::__construct($context);
    }

    public function getQuote()
    {
        return $this->onepage->getQuote();
    }

    public function isQuoteValid()
    {
        $quote = $this->getQuote();
        if (!$quote->hasItems() || $quote->getHasError() || !$quote->validateMinimumAmount()) {
            return false;
        }

        return true;
    }

    /**
     * Check can page show for unregistered users
     *
     * @return boolean
     */
    public function canShowForUnregisteredUsers()
    {
        return $this->customerSession->isLoggedIn()
            || $this->getRequest()->getActionName() == 'index'
            || $this->checkoutHelper->isAllowedGuestCheckout($this->getQuote())
            || !$this->checkoutHelper->isCustomerMustBeLogged();
    }

    /**
     * Make sure customer is valid, if logged in
     * @return bool
     */
    public function preDispatchValidateCustomer()
    {
        try {
            $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
        } catch (NoSuchEntityException $e) {
            return true;
        }

        if (isset($customer)) {
            $validationResult = $this->accountManagement->validate($customer);
            if (!$validationResult->isValid()) {
                foreach ($validationResult->getMessages() as $error) {
                    $this->messageManager->addErrorMessage($error);
                }

                return false;
            }
        }

        return true;
    }
}
