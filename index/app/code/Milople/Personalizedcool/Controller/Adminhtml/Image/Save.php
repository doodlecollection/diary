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

namespace Milople\Personalizedcool\Controller\Adminhtml\Image;

use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Controller\Adminhtml\Image;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Exception;
use Magento\Framework\File\Uploader;
use Magento\Framework\File\UploaderFactory;
use Milople\Personalizedcool\Api\ImageRepositoryInterface;
use Milople\Personalizedcool\Api\Data\ImageInterfaceFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Registry;

class Save extends Image
{
    /**
     * @var Filesystem
     */
    protected $filesystem;
    protected $_fileUploaderFactory;
    protected $_logger;
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ImageRepositoryInterface $imageRepository
     * @param ImageInterfaceFactory $imageFactory
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,

        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        ImageRepositoryInterface $imageRepository,
        ImageInterfaceFactory $imageFactory,
        UploaderFactory $uploaderFactory,
        \Psr\Log\LoggerInterface $logger,
        Filesystem $filesystem
    )
    {
        $this->uploaderFactory = $uploaderFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->filesystem = $filesystem;
          $this -> _logger = $logger;
        parent::__construct($context, $coreRegistry, $imageRepository, $imageFactory);
    }

    /**
     * Dispatch request
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {

            $id = $this->getRequest()->getParam('image_id');

            if ($id) {
                try {
                    $image = $this->imageRepository->getById($id);
                } catch (Exception $e) {
                    $this->messageManager->addError(__('This image no longer exists.'));
                    /** @var Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $image = $this->imageFactory->create();
            }

            $data = $this->removeFile($data);
            $data = $this->uploadFile($data);

            $image->setData($data);

            try {
                $this->imageRepository->save($image);
                $this->messageManager->addSuccess(__('You saved the image.'));
                $this->_session->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['image_id' => $image->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['image_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $data
     * @return $this
     */
    protected function uploadFile($data)
    {
        

        $uploadDirectory = implode(DIRECTORY_SEPARATOR, [
            BP,
            $this->filesystem->getUri('media'),
            ImageInterface::RELATIVE_PATH_FROM_MEDIA_TO_FILE
        ]);
        try {
            /** @var $uploader Uploader */
            $uploader = $this->_fileUploaderFactory->create(['fileId' => ImageInterface::PATH]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);

            $result = $uploader->save($uploadDirectory);
            $data['path'] = ImageInterface::RELATIVE_PATH_FROM_MEDIA_TO_FILE . $result['file'];
        } catch (\Exception $e) {
            if ($data['path'] && is_array($data['path'])) {
                $data['path'] = $data['path']['value'];
            }
            return $data;
        }

        return $data;
    }

    /**
     * @param $data
     * @return $this
     */
    protected function removeFile($data)
    {
        if (!array_key_exists('path', $data) || !array_key_exists('delete', $data['path']) || !$data['path']['delete']) {
            return $data;
        }
        
        $writer = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);

        try {
            $writer->delete(ImageInterface::RELATIVE_PATH_FROM_MEDIA_TO_FILE . $data['path']['value']);
            $data['path'] = null;
        } catch (\Exception $e) {}

        return $data;
    }
}