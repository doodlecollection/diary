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
namespace Milople\Personalizedcool\Controller\Index;

class Save extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Milople\Personalizedcool\Model\Area $areaFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_logger = $logger;
        $this->areaFactory = $areaFactory;
        return parent::__construct($context);

    }
     
    public function execute()
    {
        $postdata = $this->_request->getPost();
        $imageId=$postdata['imageId'];
        $productId=$postdata['productId'];
        $x1=$postdata['x1'];
        $x2=$postdata['x2'];
        $y1=$postdata['y1'];
        $y2=$postdata['y2'];
        $w=$postdata['w'];
        $h=$postdata['h'];
     
       $model= $this->areaFactory;
       $areaCollection =$model->getCollection()
                ->addFieldToFilter('image_id', array('eq' => $imageId))
                                ->addFieldToFilter('product_id',array('eq' => $productId));
        $area=$areaCollection->getFirstItem();  
        if($area->getAreaId()!=''){
          $model->load($area->getAreaId());
          $model->setData(
              array(
              'area_id'=>$area->getAreaId(),
              'image_id'=>$area->getImageId(),
              'product_id'=>$area->getProductId(),
               'x1' => $x1,
               'y1' => $y1,
               'x2' => $x2,
               'y2' => $y2,
               'width'=>$w,
               'height'=>$h
               ));
          $model->save();
        }else{
           $model->setData(
              array(
              'image_id'=>$imageId,
              'product_id'=>$productId,
               'x1' => $x1,
               'y1' => $y1,
               'x2' => $x2,
               'y2' => $y2,
               'width'=>$w,
               'height'=>$h
               ));
          $model->save();
        }    
    }
}
