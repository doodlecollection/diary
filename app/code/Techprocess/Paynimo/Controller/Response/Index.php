<?php
namespace Techprocess\Paynimo\Controller\Response;
use Techprocess\Paynimo\Lib\TransactionResponseBean;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http;
use Magento\Sales\Model\Order\Payment\Transaction\Builder as TransactionBuilder;
use Magento\Sales\Model\Order\Payment\Transaction;

class Index extends  \Magento\Framework\App\Action\Action
{
    protected $_objectmanager;
    protected $_checkoutSession;
    protected $_orderFactory;
    protected $urlBuilder;
    protected $response;
    protected $config;
    protected $messageManager;
    protected $transactionRepository;
    protected $cart;
    protected $inbox;
     
    public function __construct( Context $context,
            Session $checkoutSession,
            OrderFactory $orderFactory,
            ScopeConfigInterface $scopeConfig,
            Http $response,
            TransactionBuilder $tb,
             \Magento\Checkout\Model\Cart $cart,
             \Magento\AdminNotification\Model\Inbox $inbox,
             \Magento\Sales\Api\TransactionRepositoryInterface $transactionRepository,

                 \Magento\Framework\Data\Form\FormKey $formKey,
                \Magento\Framework\App\Request\Http $request
        ) {

    $this->request = $request;
    $this->formKey = $formKey;
    $this->request->setParam('form_key', $this->formKey->getFormKey());
      
        $this->checkoutSession = $checkoutSession;
        $this->orderFactory = $orderFactory;
        $this->response = $response;
        $this->config = $scopeConfig;
        $this->transactionBuilder = $tb;                
        $this->cart = $cart;
        $this->inbox = $inbox;
        $this->transactionRepository = $transactionRepository;
        $this->urlBuilder = \Magento\Framework\App\ObjectManager::getInstance()
                            ->get('Magento\Framework\UrlInterface');
        
        parent::__construct($context);
    }

    public function execute()
    {   
        $str = $this->getRequest()->getParam('msg');
        $iv =  $this->config->getValue('payment/paynimo/paynimo_iv');
        $key = $this->config->getValue('payment/paynimo/paynimo_key');
        $trs = new TransactionResponseBean();
        $trs->setResponsePayload($str);
        $trs->__set('key', $key);
        $trs->__set('iv',$iv);

        $response = $trs->getResponsePayload(); 
        $responseDetails = explode('|',$response);
        $responseData = array();
        foreach($responseDetails as $responseDetailsData)
        {
            $data = explode("=",$responseDetailsData);
            $responseData[$data[0]] = $data[1];
        }
        
        //print_r($responseData);
        $orderId = $this->checkoutSession->getLastOrderId();

        $order = $this->orderFactory->create()->load($orderId);
        $payment = $order->getPayment();





        if($responseData['txn_status'] == 300) {
            $order->setState(Order::STATE_PROCESSING)->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING));

            $payment->setTransactionId($responseData['tpsl_txn_id']);
            $payment->setAdditionalInformation(  
                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => array("Transaction is yet to complete")]
            );
            $trn = $payment->addTransaction(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE,null,true);
            $trn->setIsClosed(1)->save();
            
            $payment->setParentTransactionId(null); 

            # send new email
            $order->setCanSendNewEmailFlag(true);
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $objectManager->create('Magento\Sales\Model\OrderNotifier')->notify($order);

            $payment->save();
            $order->save();
            $this->_redirect($this->urlBuilder->getUrl('checkout/onepage/success/',  ['_secure' => true]));
        }
        else {
                $this->cancelAction();
                $this->_redirect($this->urlBuilder->getUrl('checkout/onepage/failure/',  ['_secure' => true]));
        }
 
    }

    public function cancelAction() 
    {   
        if ($this->checkoutSession->getLastOrderId())
        {
            $orderId = $this->checkoutSession->getLastOrderId();
            $order = $this->orderFactory->create()->load($orderId);
            
            if($order->getId()) 
            {      
                $this->checkoutSession->setErrorMessage("AN ERROR OCCURRED IN THE PROCESS OF PAYMENT");          
                $order->cancel()->setState(Order::STATE_CANCELED, true, 'Gateway has declined the payment.')->save();
            }
        }
    }
}
