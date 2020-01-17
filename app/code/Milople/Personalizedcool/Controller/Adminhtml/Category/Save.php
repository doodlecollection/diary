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

namespace Milople\Personalizedcool\Controller\Adminhtml\Category;

use Milople\Personalizedcool\Controller\Adminhtml\Category;
use Milople\Personalizedcool\Api\CategoryRepositoryInterface;
use Milople\Personalizedcool\Api\Data\CategoryInterfaceFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Model\AbstractModel;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Helper\Js;
use Exception;

class Save extends Category
{
    /**
     * @var Js
     */
    protected $jsHelper;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryInterfaceFactory $categoryFactory
     * @param Js $jsHelper
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        CategoryRepositoryInterface $categoryRepository,
        CategoryInterfaceFactory $categoryFactory,
        Js $jsHelper
    )
    {
        parent::__construct($context, $coreRegistry, $categoryRepository, $categoryFactory);
        $this->jsHelper = $jsHelper;
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
            $id = $this->getRequest()->getParam('category_id');

            if ($id) {
                try {
                    $category = $this->categoryRepository->getById($id);
                } catch(Exception $e) {
                    $this->messageManager->addError(__('This Category no longer exists.'));
                    /** @var Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                $category = $this->categoryFactory->create();
            }

            $category->setData($data);
            $category = $this->decodeImageLinks($category);

            try {
                $this->categoryRepository->save($category);
                $this->messageManager->addSuccess(__('You saved the Category.'));
                $this->_session->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $category->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param AbstractModel $object
     *
     * @return AbstractModel
     */
    public function decodeImageLinks(AbstractModel $object)
    {
        if (false === $object->hasData('links')
            || false === array_key_exists('images', $object->getData('links'))
            || !$object->getData('links')['images']
        ) {
            return $object;
        }
        
        $postedImages = $this->jsHelper->decodeGridSerializedInput($object->getData('links')['images']);
       
        array_walk($postedImages, function (&$item) {
            $item = $item['position'];
        });
        return $object->setData('posted_images', $postedImages);
    }
}