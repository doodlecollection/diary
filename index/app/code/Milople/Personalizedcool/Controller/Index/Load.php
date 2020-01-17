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
* @url         https://www.milople.com/magento2-extensions/personalized-products.html
*
**/
namespace Milople\Personalizedcool\Controller\Index;
use Magento\Catalog\Model\Product;
class Load extends \Magento\Framework\App\Action\Action
{
	protected $templateFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Catalog\Model\ProductFactory $productModelFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
    		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      	\Magento\Swatches\Helper\Data $swatchHelper,
    		\Magento\Catalog\Helper\Image $imageHelper,
    		\Milople\Personalizedcool\Helper\Product\View\Personalized $personalizedHelper,
    		\Psr\Log\LoggerInterface $logger
    	) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_logger = $logger;
        $this->swatchHelper = $swatchHelper;
        $this->productModelFactory = $productModelFactory;
    		$this->_scopeConfig = $scopeConfig;
     		$this->imageHelper=$imageHelper;
    		$this->personalizedHelper=$personalizedHelper;
    		$this->resultJsonFactory = $resultJsonFactory;
        return parent::__construct($context);

    }
     
    public function execute()
    {

     		$count=1; 
     		$i=0;
				$result = $this->resultJsonFactory->create();
        if ($productId = (int)$this->getRequest()->getParam('product_id')) {
            $currentConfigurable = $this->productModelFactory->create()->load($productId);
            $attributes = (array)$this->getRequest()->getParam('attributes');
            if (!empty($attributes)) {
                $product = $this->getProductVariationWithMedia($currentConfigurable, $attributes);
            }
            if ((empty($product) || (!$product->getImage() || $product->getImage() == 'no_selection'))
                && isset($currentConfigurable)
            ) {
                $product = $currentConfigurable;
            }
        }
    		$productId = $product->getId();
    		$model= $this->templateFactory;
        $base=$this->personalizedHelper->getBaseImage($productId,$this->imageHelper);
        $imageId=$base['id'];
        $imageUrl=$base['url'];
        $noarea=0;
          $width = $this->personalizedHelper->getConfig('personalizedcool/area_setting_group/width');
         $height = $this->personalizedHelper->getConfig('personalizedcool/area_setting_group/height');
         if($this->personalizedHelper->isImageHasArea($productId,$imageId)=='allowed'){
          $area="<div id='drawingArea-".$imageId."' class='designAreasDiv' style='height:". $this->personalizedHelper->getCoordinates($productId,$imageId,'Height') . "px; width: ".  $this->personalizedHelper->getCoordinates($productId,$imageId,'Width') . "px; left: ". $this->personalizedHelper->getCoordinates($productId,$imageId,'X1') ."px;top: " . $this->personalizedHelper->getCoordinates($productId,$imageId,'Y1'). "px; position: absolute;z-index: 10;border: 2px dotted;'>".
               "<canvas id='imageCanvas-".$imageId."' width='".$this->personalizedHelper->getCoordinates($productId,$imageId,'Width')." ' height='". $this->personalizedHelper->getCoordinates($productId,$imageId,'Height')."' ></canvas></div><div id='product-zoom-container-".$imageId."' style='display: none;'><canvas id='product-zoom-canvas-".$imageId."' width='". $width ."' height='". $height . "'></canvas></div>";
            $noarea=1;   
         }else{
          $area='<div id="container"><canvas id="imageCanvas" class="canvas"   width="'.$width.'" height="'.$height.'"></canvas> 
                         </div> <div id="product-zoom-container" style="display: none;">
                            <canvas id="product-zoom-canvas" width="'.$width.'" height="'.$height.'"></canvas></div>';
            $noarea=0;
        }
        $result->setData([
       	   'base'  => $imageUrl,
           'area'  => $area,
           'id'    => $imageId,
           'noarea' =>$noarea
         ]);	
       return $result; 
   }

    function getProductVariationWithMedia(Product $currentConfigurable, array $attributes)
    {
        $product = null;
        $layeredAttributes = [];
        $configurableAttributes = $this->swatchHelper->getAttributesFromConfigurable($currentConfigurable);
        if ($configurableAttributes) {
            $layeredAttributes = $this->getLayeredAttributesIfExists($configurableAttributes);
        }
        $resultAttributes = array_merge($layeredAttributes, $attributes);

        $product = $this->swatchHelper->loadVariationByFallback($currentConfigurable, $resultAttributes);
        if (!$product || (!$product->getImage() || $product->getImage() == 'no_selection')) {
            $product = $this->swatchHelper->loadFirstVariationWithSwatchImage($currentConfigurable, $resultAttributes);
        }
        if (!$product) {
            $product = $this->swatchHelper->loadFirstVariationWithImage($currentConfigurable, $resultAttributes);
        }
        return $product;
    }

    /**
     * @param array $configurableAttributes
     * @return array
     */
    protected function getLayeredAttributesIfExists(array $configurableAttributes)
    {
        $layeredAttributes = [];

        foreach ($configurableAttributes as $attribute) {
            if ($urlAdditional = (array)$this->getRequest()->getParam('additional')) {
                if (array_key_exists($attribute['attribute_code'], $urlAdditional)) {
                    $layeredAttributes[$attribute['attribute_code']] = $urlAdditional[$attribute['attribute_code']];
                }
            }
        }
        return $layeredAttributes;
    }
}
