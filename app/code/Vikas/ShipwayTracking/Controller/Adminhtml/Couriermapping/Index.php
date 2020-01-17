<?php
namespace Vikas\ShipwayTracking\Controller\Adminhtml\Couriermapping;



use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Magento\Backend\App\Action 
{
    /**
     * Hello test controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
   

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */


  /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
     protected $pagefactory;
    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Backend\App\Action\Context $context,
        PageFactory $resultPageFactory
 
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);


        $post = $this->getRequest()->getPostValue();

        if(count($post)){
            echo "<pre>";
            print_r($_REQUEST);
            print_r($post);

            echo "</pre>";
           

            $this->savecouriermapp($post);
           //  die;

        }

    }



    public function savecouriermapp($data){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager



        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $write = $resource->getConnection();


        /*
         $read = Mage::getSingleton('core/resource')->getConnection('core_read');
                        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $data = $this->getRequest()->getPost();*/

        //print_r($data); die; 
        //$read->fetchAll(" DELETE FROM shipwa_couriermapping where 1 "); 
            $deploymentConfig = $objectManager->get('Magento\Framework\App\DeploymentConfig');
    $table_prefix = $deploymentConfig->get('db/table_prefix');
    $table_name =  $table_prefix.'shipway_couriermapping';

        $write->query("delete from ".$table_name." where 1"); 

        $i=1;
        foreach($data['shipway'] as $key => $value){
            $write->insert($table_name, array("id"=>$i,"default_courier"=>$key , "shipway_courierid"=>$value,"courier_type"=>"default"));
            $i++;
        }

        foreach($data['shipway_customcourier_name'] as $key => $value){
            if($value!=''){
                $write->insert($table_name, array("id"=>$i,"default_courier"=>$value , "shipway_courierid"=>$data['shipway_courier_id'][$key],"courier_type"=>"custom"));
                $i++;
            }
        }


 $this->_redirect($this->_redirect->getRefererUrl());
     
    }



 
    public function execute()
    {
 //$this->_redirect($this->_redirect->getRefererUrl());
       
        //echo "hello world shipway";
        // $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        //  $result->setContents('Hello Admins!');
       // return $result;
        //$this->_resultPageFactory = new ResultPageFactory();
     /*   $resultPage =  $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        //$resultPage = $this->PageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__(' heading '));
 
        $block = $resultPage->getLayout()
                ->createBlock('Vikas\ShipwayTracking\Block\Adminhtml\Couriermapping')
                ->setTemplate('Vikas_ShipwayTracking::shipwaytracking/couriermapping.phtml')
                ->toHtml();
        $this->getResponse()->setBody($block);*/
    /*
            $this->pagefactory = new \Magento\Framework\View\Result\PageFactory;
            $resultPage = $this->pagefactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__(' heading '));
     
            $block = $resultPage->getLayout()
                    ->createBlock('Vikas\ShipwayTracking\Block\Adminhtml\Couriermapping')
                    ->setTemplate('Vikas_ShipwayTracking::shipwaytracking/couriermapping.phtml')
                    ->toHtml();
            $this->getResponse()->setBody($block);*/
		$this->_view->loadLayout();
		$this->_view->getLayout()->getBlock('head');
    	//$this->_view->renderLayout();

		//$resultPage = $this->_resultPageFactory->create();
		//$resultPage->getConfig()->getTitle()->prepend(__(' Shipway Couriermapping '));

		$block = $this->_view->getLayout()
		->createBlock('Vikas\ShipwayTracking\Block\Adminhtml\Couriermapping')
		->setTemplate('Vikas_ShipwayTracking::shipwaytracking/couriermapping.phtml');
		//->toHtml();
		$this->getResponse()->appendBody($block->toHtml());
//$this->_view->renderLayout(); 
//echo "responsetest";
	 $this->_view->renderLayout(); ;
        
    }

}