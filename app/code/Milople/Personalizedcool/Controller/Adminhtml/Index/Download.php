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
* @url         https://www.milople.com/magento2-extensions/personalizedcool-products-m2.html
*
**/
namespace Milople\Personalizedcool\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Exception;
class Download extends \Magento\Backend\App\Action
{
    
    protected $_publicActions = ['download'];

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
 
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
         $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
       
        $customDir = $this->directory_list->getPath('media').'/personalized/images/';
        $file_arr=array();
        $files = explode(',', $this->getRequest()->getParam('names'));
        foreach ($files as $file) {
            $file_arr[]= $customDir.$file;
        }
        if (count($file_arr)>0) {
            $result = $this->create_zip($file_arr, "milople_personalized_products.zip");
        }
       
    }

    /**
     * Is the user allowed to view the fonts grid.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return true;
    }
    
    public function create_zip($files = array(), $destination)
    {
        //vars
        $valid_files = array();
         $overwrite = false;
        //if files were passed in...
        if(is_array($files)) {
            //cycle through each file
            foreach($files as $file) {
                //make sure the file exists
              
                if(file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have files to zips
        if(count($valid_files)) {
            //create the archive
            $zip = new \ZipArchive();
            if($zip->open($destination,$overwrite ? \ZIPARCHIVE::OVERWRITE : \ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach($valid_files as $file) {
                $zip->addFile($file,basename($file));
            }
            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            header('Content-Type: application/zip');
            header('Content-disposition: attachment; filename='.$destination);
            header('Content-Length: ' . filesize($destination));
            readfile($destination);
            unlink($destination);
            
        }
    }
}
