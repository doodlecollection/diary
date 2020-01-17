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
namespace Milople\Personalizedcool\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Milople\Personalizedcool\Helper\Product\View\Personalized $personalized_helper,
        array $data = []
    ) {
        $this->personalized_helper=$personalized_helper;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }
		/*
		* Show Personalized It! Button on list page.
		*/
    public function getProductDetailsHtml(\Magento\Catalog\Model\Product $product)
    {
				$status=$this->personalized_helper->getConfig('personalizedcool/license_status_group/status');
				$html='';
				$renderer = $this->getDetailsRenderer($product->getTypeId());
        if ($renderer) {
            $renderer->setProduct($product);
            $enableFromProduct=$product->getAllowPersonalization();
            if(($status && $enableFromProduct) || ($product->getTypeId()=='personalized' && $status)) {
                $buttonLabel=$this->personalized_helper->getConfig('personalizedcool/general_setting_group/button_label');
							$html ="<a href='".$product->getProductUrl(). "' class='listButton'>".__($buttonLabel)."</a><br/><br/>";	
           }
           return $renderer->toHtml() . $html;
        }
        
    }
}
