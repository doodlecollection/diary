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
class Saveraw extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem $filesystem,
         \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        return parent::__construct($context);

    }
     
    public function execute()
    {
            $target = $this->_mediaDirectory->getAbsolutePath('personalized/uploads');        
	        $uploader = $this->_fileUploaderFactory->create(['fileId' => 'afile']);
	        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
	        $uploader->setAllowRenameFiles(true);
	        $result = $uploader->save($target);
	        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        	$resultJson->setData($result['file']);
        	return $resultJson;
    }
}
