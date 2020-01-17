<?php
namespace Magecomp\Sms\Observer\Customer;

use Magento\Framework\Event\ObserverInterface;

class OrderSaveObserver implements ObserverInterface
{
    protected $helperapi;
    protected $helperorder;
    protected $emailfilter;
    protected $customerFactory;
    protected $checkoutSession;

    public function __construct(
        \Magecomp\Sms\Helper\Apicall $helperapi,
        \Magecomp\Sms\Helper\Order $helperorder,
        \Magento\Email\Model\Template\Filter $filter,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->helperapi = $helperapi;
        $this->helperorder = $helperorder;
        $this->emailfilter = $filter;
        $this->customerFactory = $customerFactory;
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->helperorder->isEnabled())
            return $this;

        $order = $observer->getEvent()->getOrder();
        if($order)
        {
            $billingAddress = $order->getBillingAddress();
            $mobilenumber = $billingAddress->getTelephone();

            if($order->getCustomerId() > 0)
            {
                $customer = $this->customerFactory->create()->load($order->getCustomerId());
                $mobile = $customer->getMobilenumber();
                if($mobile != '' && $mobile != null)
                {
                    $mobilenumber = $mobile;
                }

                $this->emailfilter->setVariables([
                    'order' => $order,
                    'customer' => $customer,
                    'order_total' => $order->formatPriceTxt($order->getGrandTotal()),
                    'mobilenumber' => $mobilenumber
                ]);
            }
            else
            {
                $this->emailfilter->setVariables([
                    'order' => $order,
                    'order_total' => $order->formatPriceTxt($order->getGrandTotal()),
                    'mobilenumber' => $mobilenumber
                ]);
            }

            if ($this->helperorder->isOrderNotificationForUser())
            {
                
                    $message = $this->helperorder->getOrderNotificationUserTemplate();
                    $finalmessage = $this->emailfilter->filter($message);
                    $this->helperapi->callApiUrl($mobilenumber,$finalmessage);
                
            }

            if($this->helperorder->isOrderNotificationForAdmin() && $this->helperorder->getAdminNumber())
            {
                if($order->getState() == 'pending' || $order->getState() == 'processing') {
                    $message = $this->helperorder->getOrderNotificationForAdminTemplate();
                    $finalmessage = $this->emailfilter->filter($message);
                    $this->helperapi->callApiUrl($this->helperorder->getAdminNumber(),$finalmessage);
                }
            }
        }
        return $this;
    }
}
