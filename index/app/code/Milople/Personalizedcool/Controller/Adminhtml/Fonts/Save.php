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
namespace Milople\Personalizedcool\Controller\Adminhtml\Fonts;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action\Context;

class Save extends \Magento\Backend\App\Action
{

    protected $_fileUploaderFactory;
 
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->filesystem=$filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        parent::__construct($context);
    }
   
    public function execute()
    {
        $ttf_flag = 0;
        $woff_flag = 0;
        $data = $this->getRequest()->getParams();
        if ($data) {
            $model = $this->_objectManager->create('Milople\Personalizedcool\Model\Fonts');
            try {
                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'ttf']);
                if (!empty($uploader)) {
                    $uploader->setAllowedExtensions(['ttf']);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
                            ->getAbsolutePath('fonts/');
                    $resultTTF=$uploader->save($path);
                    $ttf_flag = 1;
                }
                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'woff']);
                if (!empty($uploader)) {
                    $uploader->setAllowedExtensions(['woff']);
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
                          ->getAbsolutePath('fonts/');
                    $resultWOFF=$uploader->save($path);
                    $woff_flag = 1;
                }
            } catch (\Exception $e) {
                //$this->messageManager->addError($e->getMessage());
                //$this->_redirect('*/*/');
            }

         
            $id = $this->getRequest()->getParam('font_id');
            if ($id) {
                $model->load($id);
            }
            
            $model->setName($data['name']);
            $model->setStatus($data['status']);
            if (!empty($uploader)) {
                if($ttf_flag){
                  $model->setTtfname($resultTTF['file']);
                }
                if($woff_flag){
                  $model->setWoffname($resultWOFF['file']);
                }
            }
            try {
                $model->save();
                $this->messageManager->addSuccess(__('Font was saved successfully'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the font.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('font_id' => $this->getRequest()->getParam('font_id')));
            return;
        }
        $this->_redirect('*/*/');
    }
}
