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
namespace Milople\Personalizedcool\Block;
use Magento\Framework\View\Element\Template;
class Personalized extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry = null;
    protected $_productRepository;
    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        array $data = []
    ) {
    
        $this->_productRepository = $productRepository;
        $this->productTypeConfig = $productTypeConfig;
        parent::__construct($context, $data);
       
    }
    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    public function shouldRenderQuantity()
    {
				$p_id = $this->getRequest()->getParam('id');
				$_product = $this->_productRepository->getById($p_id);
        return !$this->productTypeConfig->isProductSet($_product->getTypeId());
    }
		public function isRedirectToCartEnabled()
    {
        return $this->_scopeConfig->getValue(
            'checkout/cart/redirect_to_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
