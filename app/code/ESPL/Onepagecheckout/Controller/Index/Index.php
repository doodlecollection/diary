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
namespace ESPL\Onepagecheckout\Controller\Index;

use ESPL\Onepagecheckout\Controller\Action;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NotFoundException;

class Index extends Action
{

    /**
     * Dispatch request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        if ($this->getQuote()->isMultipleShippingAddresses()) {
            $this->getQuote()->removeAllAddresses();
        }

        return parent::dispatch($request);
    }

    public function execute()
    {
        if (!$this->helper->getEnable()) {
            return $this->resultRedirectFactory->create()->setPath('checkout');
        }

        if (!$this->preDispatchValidateCustomer()) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            return $this->resultRedirectFactory->create()->setPath('customer/account/edit');
        }

        if (!$this->canShowForUnregisteredUsers()) {
            throw new NotFoundException(__('Page not found.'));
        }

        if (!$this->checkoutHelper->canOnepageCheckout()) {
            $this->messageManager->addErrorMessage(__('One-page checkout is turned off.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $quote = $this->onepage->getQuote();
        if (!$quote->hasItems() || $quote->getHasError() || !$quote->validateMinimumAmount()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        if (!$this->customerSession->isLoggedIn() && !$this->checkoutHelper->isAllowedGuestCheckout($quote)) {
            $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $this->customerSession->regenerateId();
        $this->checkoutSession->setCartWasUpdated(false);
        $this->onepage->initCheckout();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set("Checkout");
        return $resultPage;
    }
}
