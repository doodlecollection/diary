<?php
/**
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future. If you wish to customize the module for your
* needs please contact us to https://www.milople.com/contact-us.html
*
* @category    Ecommerce
* @package     Milople_Personlized
* @copyright   Copyright (c) 2017 Milople Technologies Pvt. Ltd. All Rights Reserved.
* @url         https://www.milople.com/magento2-extensions/personalizedcool-products-m2.html
*
**/
namespace Milople\Personalizedcool\Model;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception;
use Magento\Bundle\Model\Product\Type;
class ProcessPersonalized implements ObserverInterface
{
    protected $request;
    public function __construct(
        \Magento\Framework\Registry $registry,
      
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\ProductFactory $productModel,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Milople\Personalizedcool\Helper\Product\View\Personalized $personalized_helper,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_registry = $registry;
        $this->_request = $request;
        $this->cart = $cart;
        $this->_productModel = $productModel->create();
        $this->_checkoutSession = $checkoutSession;
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this ->_logger = $logger;
        $this->personalized_helper=$personalized_helper;
    }
    public function execute(Observer $observer)
    {
    
        $postdata = $this->_request->getPost();
        $item = $observer->getEvent()->getQuoteItem();
        if (isset($postdata['image_html'])) {
            try {
                $content= $postdata['image_html'];
                $rawImages=str_replace('"', '',$postdata['raw_images']);
                $product = $this->_productModel->load($postdata['product']);
                $tierPrice=$product->getTierPrice($postdata['qty']);
                $result=$product->addCustomOption('image_html', $content);
                $product->addCustomOption('raw_images', $rawImages);
                $productPrice=$item->getProduct()->getPrice();
                //Getting price of objects
                $pricePerText=$this->personalized_helper
                               ->getConfig('personalizedcool/text_setting_group/price_per_text');
                $pricePerImage=$this->personalized_helper
                               ->getConfig('personalizedcool/Image_setting_group/personalized_price_per_image');
                // Getting object count
                $textObjects=$postdata['tObject'];
                $imageObjects=$postdata['iObject'];
                $bundle_price_final=$postdata['bundle_price'];
                $testing_bundle = $item->getProductType();
                $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
                if($testing_bundle=='bundle')
                {
                  $optionCollection = $product->getTypeInstance()->getOptionsCollection($item->getProduct());
                  $selectionCollection =$product->getTypeInstance()->getSelectionsCollection($product->getTypeInstance()->getOptionsIds($item->getProduct()),$item->getProduct());
                  $options = $optionCollection->appendSelections($selectionCollection);
                  $single_product_of_bundle = NULL;
                  $product_simple = NULL;
                  $items=$this->cart->getQuote()->getAllItems();
                  $size_of_items = sizeof($items) - 1;
                  $per_item_price = ($bundle_price_final+($pricePerText*$textObjects)+($pricePerImage*$imageObjects)) / $size_of_items;
                  foreach($items as $i){
                    if ($i->getId() == '') // not getting id for currently adding bundle item.
                    {
                        $options = $i->getOptions();
                        $price = $per_item_price;
                        $i->setCustomPrice($price);
                        $i->setOriginalCustomPrice($price);
                    }
                  }
                  $price = $bundle_price_final+($pricePerText*$textObjects)+($pricePerImage*$imageObjects); //set your price here
                  $item->setCustomPrice($price);
                  $item->setOriginalCustomPrice($price);
                } else{
                  if($tierPrice>0){
                    $productPrice=$tierPrice;
                  }  
                  $price = $productPrice+($pricePerText*$textObjects)+($pricePerImage*$imageObjects); //set your price here
                  $item->setCustomPrice($price);
                  $item->setOriginalCustomPrice($price);
                }
                $item->getProduct()->setIsSuperMode(true);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
