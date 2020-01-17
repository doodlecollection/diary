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

class Delete extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
         \Milople\Personalizedcool\Model\Area $areaFactory,
       \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_logger = $logger;
        $this->areaFactory = $areaFactory;
        return parent::__construct($context);

    }
     
    public function execute()
    {
        $postdata = $this->_request->getPost();
        $fileURL=$postdata['fileUrl'];
        $imageId=$postdata['imageId'];
        $productId=$postdata['productId'];
        $model= $this->areaFactory;
        $areaCollection =$model->getCollection()
                ->addFieldToFilter('image_id', array('eq' => $imageId))
                ->addFieldToFilter('product_id',array('eq' => $productId))
                ->walk('delete');        
      
    }
}
