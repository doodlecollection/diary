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
namespace Milople\Personalizedcool\Controller\Page;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultPageFactory;
    protected $_productRepositoryFactory;
    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
       \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
       \Magento\Framework\View\Result\PageFactory $resultPageFactory)
        {
               $this->_productRepositoryFactory = $productRepositoryFactory;
               $this->_resultPageFactory = $resultPageFactory;
               parent::__construct($context);
        }
    /**
     * View  page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
      $p_id = $this->getRequest()->getParam('id');
      $product = $this->_productRepositoryFactory->create()->getById($p_id);
      $product->getData('image');
      $product->getData('thumbnail');
      $product->getData('small_image');
      $resultPage = $this->_resultPageFactory->create();
      $resultPage->addHandle('personalized');
      return $resultPage;
    } 
}