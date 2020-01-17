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

class Clipart extends \Magento\Framework\App\Action\Action
{ 
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Milople\Personalizedcool\Helper\Product\View\Personalized $helperPersonalized,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this->helperPersonalized=$helperPersonalized;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_logger = $logger;
        return parent::__construct($context);

    }
     
    public function execute()
    {
        $postdata = $this->_request->getPost();
        $categoryId=$postdata['clipart_cat_id'];
        $data=$this->helperPersonalized->getFilteredCategoryImage($categoryId);
        if(empty($data))
        $data= __("Sorry, no images found.");
        $returnData = $this->resultJsonFactory->create();
        return $returnData->setData(['image' => $data]);
    }
}

     