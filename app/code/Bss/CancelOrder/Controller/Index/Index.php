<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_CancelOrder
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\CancelOrder\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $customerSessionFactory;

    /**
     * @var \Bss\CancelOrder\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Customer\Model\SessionFactory $customerSessionFactory
     * @param \Bss\CancelOrder\Helper\Data $helper
     * @param \Magento\Sales\Model\Order $order
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Escaper $escaper,
        \Magento\Customer\Model\SessionFactory $customerSessionFactory,
        \Bss\CancelOrder\Helper\Data $helper,
        \Magento\Sales\Model\Order $order,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->customerSessionFactory = $customerSessionFactory;
        $this->helper = $helper;
        $this->order = $order;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        if (!$this->helper->isEnabled()) {
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        $customerSession = $this->customerSessionFactory->create();

        if (!$customerSession->isLoggedIn()) {
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        if (empty($this->getRequest()->getPost('id')) || empty($this->getRequest()->getPost('customer_id'))) {
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        $idCustomer = $this->getRequest()->getPost('customer_id');
        $sessionCustomerid =  $customerSession->getCustomer()->getId();

        if ($sessionCustomerid != $idCustomer) {
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        $incrementId = $this->getRequest()->getPost('id');
        $order = $this->order->loadByIncrementId($incrementId);
        
        if (!$order->getId()) {
            $this->messageManager->addErrorMessage(__('We can\'t find your order.'));
            return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
        }

        if ($order->getState() != 'new' || $order->getCustomerId() != $idCustomer || !$order->canCancel()) {
            $this->messageManager->addErrorMessage(__('You are not permitted to cancel this order'));
            return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
        }

        $cancelComment = $this->getCancelComment();

        try {
            $order->cancel();
            $order->addStatusHistoryComment($cancelComment);
            $order->save();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('There is a system error. Please try again later.'));
            return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
        }

        $emailSender = $this->helper->getEmailSender();

        if ($emailSender && $emailSender != '') {
            $subject = "Frontend Cancel Order # " . $incrementId;

            $emailTo = $this->helper->getEmailReceived();
            $emailTemplate = $this->helper->getEmailTemplate();
            $nameSender = $this->helper->getNameSender();

            $customerId = $customerSession->getCustomer()->getId();
            $customerName = $customerSession->getCustomer()->getName();
            $customerEmail = $customerSession->getCustomer()->getEmail();

            $emailInfo = [
                'subject' => $subject,
                'email_sender' => $emailSender,
                'name_sender' => $nameSender,
                'order_id'    => $incrementId,
                'customer_id'=> $customerId,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'cancel_comment' => $cancelComment,
                'to_email'   =>  $emailTo,
                'email_template' => $emailTemplate
            ];

            $this->sendEmail($emailInfo);
        }

        $this->messageManager->addSuccessMessage(__('Your order canceled'));
        return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
    }

    /**
     * @param array $info
     * @return void
     */
    protected function sendEmail($info)
    {
        $data = [
            'subject' => $info['subject'],
            'order_id' => '#'.$info['order_id'],
            'customer_name' => $info['customer_name'],
            'customer_email' => $info['customer_email'],
            'cancel_comment' => $info['cancel_comment']
        ];

        $this->inlineTranslation->suspend();

        try {
            $sender = [
                'name' => $this->escaper->escapeHtml($info['name_sender']) ,
                'email' => $this->escaper->escapeHtml($info['email_sender']),
            ];

            $transport = $this->transportBuilder->setTemplateIdentifier($info['email_template'])
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ])
                ->setTemplateVars($data)
                ->setFrom($sender)
                ->addTo($info['to_email'], $info['to_email'])
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->inlineTranslation->resume();
        }
    }

    /**
     * @return string
     */
    protected function getCancelComment()
    {
        if (!empty($this->getRequest()->getPost('cancel_comment'))) {
            $cancelComment = $this->getRequest()->getPost('cancel_comment');
        } else {
            $cancelComment = "*no comment";
        }
        return $cancelComment;
    }
}
