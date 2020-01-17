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
namespace Milople\Personalizedcool\Block\Product\View\Type;

class Product extends \Magento\Framework\View\Element\Template
{
    protected $fontModel;
	  protected $_filesystem ;
    protected $_imageFactory;
		protected $_productRepository;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Milople\Personalizedcool\Helper\Data $data_helper,
        \Milople\Personalizedcool\Model\FontsFactory $fontModel,
        \Milople\Personalizedcool\Helper\Product\View\Personalized $personalized_helper,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
				\Magento\Catalog\Model\ProductRepository $productRepository,
				\Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        array $data = []
    ) {
            
        $this->data_helper=$data_helper;
        $this->_coreRegistry = $context->getRegistry();
        $this->personalized_helper=$personalized_helper;
        $this->_storeManager= $context->getStoreManager();
        $this->fontModel=$fontModel;
        $this->_filesystem =$context->getFilesystem();              
        $this->_imageFactory = $imageFactory;
				$this->_productRepository = $productRepository;
				$this->productTypeConfig = $productTypeConfig;
			parent::__construct(
            $context,
            $data
        );
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
		public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    public function getProduct()
    {
        return $this->_coreRegistry->registry('product');
    }
	  //Get Font Collection
    public function getFontCollection()
    {

        return $this->fontModel->create()->getCollection()->addFieldToFilter('name', array(
   		 	'neq' => ''
 				 ))->addFieldToFilter('status', '1');;

    }
    public function getFontFolder()
    {
        $mediaUrl = $this->_storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl.'fonts/';
    }
	public function resize($image, $width = null, $height = null)
    {
        
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('catalog/product/').$image;
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('personalized/resized/'.$width.'/').$image;         
        //create image factory...
        $imageResize = $this->_imageFactory->create();         
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(TRUE);         
        $imageResize->keepTransparency(TRUE);         
        $imageResize->keepFrame(FALSE);         
        $imageResize->keepAspectRatio(FALSE);         
        $imageResize->resize($width,$height);  
        //destination folder                
        $destination = $imageResized ;    
        //save image      
        $imageResize->save($destination);         
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'personalized/resized/'.$width.'/'.$image;
        return $resizedURL;
	} 
}
