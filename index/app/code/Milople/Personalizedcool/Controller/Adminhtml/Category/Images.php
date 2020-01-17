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
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Layout;
use Magento\Framework\Exception;


class Images extends Category
{
    /**
     * Dispatch request
     *
     * @return Layout
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $filter = $this->getRequest()->getParam('filter');
        $params=$this->getRequest()->getParams();
        if ($id) {
            try {
                $category = $this->categoryRepository->getById($id);
            } catch(Exception $e) {
                $this->messageManager->addError(__('This category no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $category = $this->categoryFactory->create();
        }

        $data = $this->_session->getFormData(true);
		if (!empty($data)) {
            $category->setData($data);

        }

        $this->coreRegistry->register('personalized_category_category', $category);

        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    }
}
