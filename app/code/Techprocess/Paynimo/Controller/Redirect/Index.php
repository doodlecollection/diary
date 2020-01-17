<?php


namespace Techprocess\Paynimo\Controller\Redirect;
use Techprocess\Paynimo\Lib\TransactionRequestBean;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {   
        return $this->resultPageFactory->create();
        $this->getRequest();
    }

    public function getRequest() {
       
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();               
        // Singleton object 
        $customer_session = $objectManager->get('Magento\Customer\Model\Session');
        $checkout_session= $objectManager->get('Magento\Checkout\Model\Session');
        $sales_order= $objectManager->get('Magento\Sales\Model\Order');
        $url_model = $objectManager->get('Magento\Framework\UrlInterface');

        //Model object
        $customer_address= $objectManager->create('Magento\Customer\Model\Address');
        $config_data = $objectManager->create('Techprocess\Paynimo\Helper\Data');


        $orderId= $checkout_session->getLastRealOrderId();
        $order = $sales_order->loadByIncrementId($orderId);
        $customer = $customer_session->getCustomer();


         $billing = $order->getBillingAddress();
         $customerMobNumber = $billing->getTelephone();
         $customerEmail = $billing->getEmail();
         $customerName = $billing->getFirstname() ." ". $billing->getLastname();
         $customerId = $customer->getId();

        // if ($customer_session->isLoggedIn()){
        // $customerData = $customer_session->getCustomer();
        // $customerAddressId = $customer_session->getCustomer()->getDefaultBilling();
        // $address = $customer_address->load($customerAddressId);

        
        // $customerId = $customer_session->getId();
        // $customerEmail = $customer_session->getCustomer()->getData('email');
        
        // $customerMobNumber = $address->getTelephone();
        // $customerFirstName = $customer_session->getCustomer()->getData('firstname');
        // $customerLastName = $customer_session->getCustomer()->getData('lastname');


        // $customerName = $customerFirstName. " ". $customerLastName;
        // }

        // $orderId= $checkout_session->getLastRealOrderId();

        // $order = $sales_order->loadByIncrementId($orderId);
        // $customer = $customer_session->getCustomer();

        $iv = $config_data->getConfig('payment/paynimo/paynimo_iv');
        $key = $config_data->getConfig('payment/paynimo/paynimo_key');
        $ReqType = $config_data->getConfig('payment/paynimo/paynimo_request_type');
        $MrctCode = $config_data->getConfig('payment/paynimo/paynimo_mercode');
        $MrctsCode = $config_data->getConfig('payment/paynimo/paynimo_scode');
        $TpvAccntNo = '';
       
        $Itc = 'email:'.$customerEmail;
        $MobNo = $customerMobNumber;
        $MrctTxtID = rand(1,1000000);
        $CurrencyType = 'INR';
        $ReturnURL = $url_model->getBaseUrl().'paynimo/response';
        $Date = date("d-m-Y");
        $TPSLTxnID = 'TXN00'.rand(1,10000);     
        $_trc = new TransactionRequestBean;
        $_trc->__set('merchantCode', $MrctCode);
        $_trc->__set('accountNo', $TpvAccntNo);
        $_trc->__set('ITC',$customerEmail);
        $_trc->__set('email', $customerEmail);
        $_trc->__set('uniqueCustomerId', $customerId);
        $_trc->__set('customerName', $customerName);
        $_trc->__set('mobileNumber', $customerMobNumber);
        $_trc->__set('iv', $iv);
        $_trc->__set('key', $key);
        $_trc->__set('requestType', $ReqType);
        $_trc->__set('merchantTxnRefNumber', $MrctTxtID);     

    
        if($config_data->getConfig('payment/paynimo/paynimo_url') == 'test'){
            $_trc->__set('webServiceLocator','https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl');
            $_trc->__set('bankCode','470');
            $Amount = "1.0";
        }
        else{
            $_trc->__set('webServiceLocator','https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl');
            $Amount = round($order->getBaseGrandTotal(),2);
        }
        $_trc->__set('amount', $Amount);
        $ShoppingCartStr = $MrctsCode.'_'.$Amount.'_0.0';
        $_trc->__set('shoppingCartDetails', $ShoppingCartStr);           
     
        $_trc->__set('currencyCode',$CurrencyType);
        $_trc->__set('returnURL', $ReturnURL);
        $_trc->__set('txnDate', $Date);
        $_trc->__set('TPSLTxnID', $TPSLTxnID);
        $_trc->__set('custId', $customerId);
        $responseDetails = $_trc->getTransactionToken();

        $responseDetails = (array)$responseDetails;
        $response = $responseDetails[0];
        header("Location: $response");
        exit;
    }
}
