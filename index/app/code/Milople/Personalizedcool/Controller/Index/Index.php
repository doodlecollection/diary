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
use Magento\Framework\Controller\ResultFactory;
class Index extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
      
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_logger = $logger;
        return parent::__construct($context);

    }
     
    public function execute()
    {
           $postdata = $this->_request->getPost();
		   $fileName=array();
		   $content= $postdata['imagedata'];
		   $count=0;
           foreach ($content as  $data) {
			# code for creation images...
			if($data!='blank'){
			   $data = str_replace('data:image/png;base64,', '', $data);
			   $data = str_replace(' ', '+', $data);
			   $data = base64_decode($data);
			   $fileName[]= uniqid() . '.png';
			   $imageUrl = $this->directory_list->getPath('media').'/personalized/images/';
			   $result = $this->filesystem->checkAndCreateFolder($imageUrl);
			   $file= $imageUrl.$fileName[$count];
			   $success = $this->filesystem->write($file, $data);
			   $count++;
			}
			else
			{
			   $fileName[]="blank";
			}
		 }
		 $returnValue=implode(",",$fileName);
		 $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
         $resultJson->setData($returnValue);
         return $resultJson;
		 
    }
}
