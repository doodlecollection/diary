<?php

namespace Vikas\ShipwayTracking\Block\Adminhtml;

//use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template;

class Couriermapping extends Template
{
    private $collectionFactory;
    protected $shipconfig;

protected $scopeConfig;


   /* public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        echo "<br>testblock";
       // parent::_construct();
        //$this->setTemplate('Magento_Config::page/system/config/robots/reset.phtml');
       // $this->setTemplate('shipwaytracking/couriermapping.phtml');
   
        
    }
*/

   /*  public function __construct(
    \Magento\Customer\Model\Session $customerSession,  
    \Magento\Framework\ObjectManagerInterface $objectManager
 ) {
    $this->customerSession = $customerSession;
    $this->_objectManager = $objectManager;
    echo "<br>testblockrtrtt";
  }*/


public function __construct(
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Customer\Model\Session $customerSession,  
    \Magento\Framework\ObjectManagerInterface $objectManager,
    //\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Shipping\Model\Config $shipconfig,
    array $data = []
 ) {

    parent::__construct($context, $data);
    $this->customerSession = $customerSession;
    $this->_objectManager = $objectManager;
       $this->shipconfig=$shipconfig;
    $this->scopeConfig = $context->getScopeConfig();
    
  }



public function getShippingMethods(){

        $activeCarriers = $this->shipconfig->getActiveCarriers();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            foreach($activeCarriers as $carrierCode => $carrierModel)
            {
               $options = array();
               if( $carrierMethods = $carrierModel->getAllowedMethods() )
               {
                   foreach ($carrierMethods as $methodCode => $method)
                   {
                        $code= $carrierCode.'_'.$methodCode;
                        $options[]=array('value'=>$code,'label'=>$method);

                   }
                   $carrierTitle =$this->scopeConfig->getValue('carriers/'.$carrierCode.'/title');

               }
                $methods[]=array('value'=>$options,'label'=>$carrierTitle);
            }
        return $options;        

    }





     /*protected function _toHtml() { echo "t666";
        return $this->_template;
    }*/
}