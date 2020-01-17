<?php
/**
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future. If you wish to customize the module for your
* needs please contact us to https://www.milople.com/contact-us.html
*
* @category    Ecommerce
* @package     Milople_Personlized
* @copyright   Copyright (c) 2016 Milople Technologies Pvt. Ltd. All Rights Reserved.
* @url         https://www.milople.com/magento2-extensions/personalizedcool-products.html
*
**/
namespace Milople\Personalizedcool\Controller\Index;

class Popup extends \Magento\Framework\App\Action\Action
{
	protected $areaFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Milople\Personalizedcool\Model\AreaFactory $areaFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_logger = $logger;
		$this->_scopeConfig = $scopeConfig;
		$this->areaFactory = $areaFactory;
		$this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);

    }
     
    public function execute()
    {
         $postdata = $this->_request->getPost();
		 $fileURL=$postdata['fileUrl'];
		 $imageId=$postdata['imageId'];
		 $productId=$postdata['productId'];
		 $width =  $this->_scopeConfig->getValue('personalizedcool/area_setting_group/width', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)."px";
		 $height =  $this->_scopeConfig->getValue('personalizedcool/area_setting_group/height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)."px";
		 $model = $this->_objectManager->create('Milople\Personalizedcool\Model\Area');
		 if ($imageId) {
             $areaModel = $this->areaFactory->create();
			 $areaCollection = $areaModel->getCollection()
			 					->addFieldToFilter('image_id', array('eq' => $imageId))
                                ->addFieldToFilter('product_id',array('eq' => $productId));
			  $area=$areaCollection->getFirstItem();
			  $html="<div class='areaImageContainer'> 
				<div class='inline-labels' id='coords'>
				<label>X1 <input type='text' size='4' id='x1' name='x1' /></label>
				<label>Y1 <input type='text' size='4' id='y1' name='y1' /></label>
				<label>X2 <input type='text' size='4' id='x2' name='x2' /></label>
				<label>Y2 <input type='text' size='4' id='y2' name='y2' /></label>
				<label>W <input type='text' size='4' id='w' name='w' /></label>
				<label>H <input type='text' size='4' id='h' name='h' /></label>
				</div>
				<img id='target' src='$fileURL' width='$width'  height='$height'/>
			   </div>";
			    $result = $this->resultJsonFactory->create();
				if($area->getAreaId()!='') {
				$result->setData([
				'html'   => $html,
				'x1' => $area->getData('x1'),
				'x2' =>  $area->getData('x2'),
				'y1' =>  $area->getData('y1'),
				'y2' =>  $area->getData('y2')
				]);	
				
             } else{
				$result->setData([
				'html'   => $html,
				'x1' => 0,
				'x2' => 0,
				'y1' => 0,
				'y2' => 0
				]);	
				
			 }
    		  return $result; 
         } // If Image ID
		
    } //
}
