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
namespace Milople\Personalizedcool\Helper\Product\View;

use Milople\Personalizedcool\Api\Data\CategoryInterface;
use Milople\Personalizedcool\Api\Data\ImageInterface;
use Milople\Personalizedcool\Api\CategoryRepositoryInterface;
use Milople\Personalizedcool\Model\ResourceModel\Image\CollectionFactory;
use Magento\Bundle\Helper\Catalog\Product\Configuration;
class Personalized extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $scopeConfig;
    public $_storeManager;
    protected $category;
    protected $images;
    protected $categoryRepository;
    protected  $resource;
    protected $_filesystem ;
    protected $_imageFactory;
    const IMAGEPATH = 'personalized/images/';

    public function __construct(
        \Magento\Framework\App\RequestInterface $httpRequest,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\Product $product,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\LayoutInterface $layout,
        CategoryRepositoryInterface $categoryRepository,
        \Milople\Personalizedcool\Model\CategoryFactory $categoryModel,
        \Milople\Personalizedcool\Model\ImageFactory $imageModel,
        \Magento\Framework\App\ResourceConnection $resource,
				\Milople\Personalizedcool\Model\Area $areaFactory,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,   
        \Magento\Catalog\Model\ProductRepository $productRepo,
				\Magento\Bundle\Model\Product\Type $bundleselection,
				\Magento\Directory\Model\Currency $currency,
				Configuration $bundleProductConfiguration

			 ) {
        $this->_productRepo = $productRepo;
        $this -> scopeConfig = $scopeConfig;
        $this -> storeManager = $storeManager;
        $this ->logger = $logger;
        $this-> request = $httpRequest;
        $this->categoryRepository = $categoryRepository;
        $this->_storeManager=$storeManager;
        $this->_layout = $layout;
				$this->areaFactory = $areaFactory;
     
        $this->category=$categoryModel;
        $this->resource = $resource;
        $this->product=$product;
        $this->images=$imageModel;
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory;
				$this->_bundleProductConfiguration = $bundleProductConfiguration;
				$this->_bundleSelection=$bundleselection;
				$this->_currency = $currency;
    }
		public function getCurrencySymbol() {
        return $this ->_storeManager-> getStore()->getBaseCurrency()->getCurrencySymbol();
    }
		public function getOptionList($item)
    {
        return $this->_bundleProductConfiguration->getOptions($item);
    }
		public function getChildrenIds($parentId, $required = true)
		{
   		return $this->_bundleSelection->getChildrenIds($parentId, $required);
		}
    #General Function for generating Configuration
    public function getConfig($config_path)
    {
        
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    #get Image Media URL
    public function getImageMediaUrl($fileName)
    {
        
        $path = $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		
				$imageUrls =$fileName;
        if(!is_array($imageUrls)){
            $imageUrls=explode(" ",$imageUrls);
        }
				foreach ($imageUrls as  $imageName) {
			  $imageName = explode(",",$imageName);
				foreach ($imageName as $image) {
     		if($image != 'blank') {
					$images[] = $path.self::IMAGEPATH . $image ;
				}
				else {
					$images[] = 'blank';
				}
			}
		}
        return $images;
    }
    #get Image Name
    public function getImageName($fileName)
    {
        
        $imageUrls =$fileName;
        if(!is_array($imageUrls)){
            $imageUrls=explode(" ",$imageUrls);
        }
        foreach ($imageUrls as  $imageName) {
          $imageName = explode(",",$imageName);
            foreach ($imageName as $image) {
               
                if($image != 'blank') {
                    $images[] = $image ;
                }
                else {
                    $images[] = 'blank';
                }
            }
        }
        return $images;
    }
    #Get original Media if module is disabled.
    public function getMedia()
    {
         $html = $this->_layout
            ->createBlock('Magento\Catalog\Block\Product\View\Gallery')
            ->setTemplate('Magento_Catalog::product/view/gallery.phtml')
            ->toHtml();

        return $html;
    }
    # Get product by id
    public function getProductById($id)
    {
        return $this->_productRepo
            ->getById($id);
    }
    #Get clipart category
    public function getCategory()
    {
       $categoryModel=$this->category->create()->getCollection();
       return $categoryModel->addFieldToFilter('name', array('neq'=>''))
                ->addFieldToFilter('status', '1');
    }
    # Get all image for clipart
    public function getAllImages()
    {
        $imageModel=$this->images->create()->getCollection();
        return $imageModel->addFieldToFilter('name', array('neq'=>''))
                ->addFieldToFilter('status', '1');
    }
   
    # Get Media URL
    public function getMediaPath()
    {
        $mediaUrl = $this->_storeManager->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
    
    #Check check image status
    public function checkCategoryImageStatus($id)
    {
       $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
       $tblPersonalizedImage = $connection->getTableName('personalized_category_category_image'); 
       $result=$connection->fetchAll('SELECT category_id FROM `'.$tblPersonalizedImage.'` WHERE image_id='.$id);
       return $this->checkCategoryStatus($result);
    }

    # Check category status enabled or not for image 
    public function checkCategoryStatus($results)
    {
        $status=false;
        foreach ($results as $result) {
            $categoryModel=$this->category->create()->getCollection();
            $categoryModel->addFieldToFilter('category_id', $result['category_id'])
                ->addFieldToFilter('status', '1');
            $hasData=count($categoryModel);
            if($hasData)
            {
                $status=true;
            }
         }
        return $status;
    }
    # get Base url of store
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
    
    #get Category image for clipart
    public function getFilteredCategoryImage($id)
    {
       $connection = $this->resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
       $tblImage=$connection->getTableName('personalized_category_image');
       $tblCategory = $connection->getTableName('personalized_category_category_image'); 
       if($id!='') { 
            $results=$connection->fetchAll('SELECT * FROM `' .$tblImage.'` ,`'.$tblCategory.'` WHERE `'.$tblImage.'`.image_id=`'.$tblCategory.'`.image_id and `'.$tblCategory.'`.category_id='.$id.' and `'.$tblImage.'`.status=1');
       }else{
            $results=$this->getAllImages();
       }

       $thumbWidth=$this->getConfig('personalizedcool/Image_setting_group/thumb_width').'px';
       $thumbHeight=$this->getConfig('personalizedcool/Image_setting_group/thumb_height').'px';
       $rawHtml='<div id="image_list" class="image_list">';
       foreach ($results as $result) {
           if($this->checkCategoryImageStatus($result['image_id'])){
             $src=$this->getMediaPath().$result['path'];
             $rawHtml.='<img style="width:'.$thumbWidth .';height:'. $thumbHeight.';" src="'.$src .'" class="clipart-image"  alt="'.$result['name'] .'" title="'.$result['name'] .'">';
           }
       }
       $rawHtml.='</div>';
       return $rawHtml;
    }
    #check product has area defined
	public function isRestrictedAreaDrawn($product,$fromWhere='Media'){
		$result=false;
        $model=$this->areaFactory;
       	$areaCollection=$model->getCollection()
    	  ->addFieldToFilter('product_id',array('eq' => $product->getId()));
    	if($areaCollection->count()>0){
    		$result=true;
    	}
        return $result;
	}
    # Check image has area defined
	public function isImageHasArea($productId,$imageId){
		$result="not allowed";
		$model=$this->areaFactory;
		$areaCollection=$model->getCollection()
						->addFieldToFilter('image_id',array('eq' => $imageId))
						->addFieldToFilter('product_id',array('eq' => $productId));
		if($areaCollection->count()>=1){
			$result='allowed';
        }
      
        return $result;	
	}
	# Check product has side.
	public function isProductHasSide($product){
		$result=false;
		$model=$this->areaFactory;
		$areaCollection=$model->getCollection()
						 ->addFieldToFilter('product_id',array('eq' => $product->getId()));
		if($areaCollection->count()>1){
			$result=true;
		}
		return $result;
	}
    # Get Area value based on product , image id and label
	public function getCoordinates($productId,$imageId,$label){
		$model=$this->areaFactory;
		$areaCollection=$model->getCollection()
						->addFieldToFilter('image_id',array('eq' => $imageId))
						->addFieldToFilter('product_id',array('eq' => $productId));
		$area=$areaCollection->getFirstItem();
		$result;
		switch($label){
			case "X1" :
							$result=$area->getData('x1');
							break;
			case "X2" :
							$result=$area->getData('x2');
							break;
			case "Y1" :
							$result=$area->getData('y1');
							break;
			case "Y2" :
							$result=$area->getData('y2');
							break;
			case "Width":
							$result=$area->getData('width');
							break;
			case "Height":
							$result=$area->getData('height');
							break;
		}
		return $result;
		
		
	}
    # Get product side count from product.
		public function getSideCount($product){
			$model=$this->areaFactory;
			$areaCollection=$model->getCollection()
							 ->addFieldToFilter('product_id',array('eq' => $product->getId()));
			return $areaCollection->count();
		}
 	 # Resize the image before showing on product page.
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

    # Get template based on product and image id
    public function getTemplateCollection($product,$imageId){

        $productId=$product->getId();
        $model=$this->templateFactory;
        $templateCollection=$model->getCollection()
                         ->addFieldToFilter('image_id',array('eq' => $imageId))
                         ->addFieldToFilter('product_id',array('eq' => $productId));
        return $templateCollection;
    }

    # Get Base image id of product
    public function getBaseImageId($product,$imageHelper)
    {
         $image = 'product_base_image';
         $url=$imageHelper->init($product, $image)
        ->constrainOnly(FALSE)
        ->keepAspectRatio(false)
        ->keepFrame(FALSE)->getUrl();
        $imageCollection=$product->getMediaGalleryImages();
        foreach ($imageCollection as $image) {
            if(basename($image->getUrl()) == basename($url))
            {
                return $image->getId();
            }
        }
    }  

    # Get Base Image of cofigurable product`s simple products
    public function getBaseImage($productId,$imageHelper)
    {
        $image = 'product_base_image';
        $product=$this->product->load($productId);
        $width=$this->getConfig('personalizedcool/area_setting_group/width');
        $height=$this->getConfig('personalizedcool/area_setting_group/height');
      
        $url=$imageHelper->init($product, $image)
        ->constrainOnly(FALSE)
        ->keepAspectRatio(false)
        ->resize($width,$height)
        ->keepFrame(FALSE)->getUrl();
        $imageCollection=$product->getMediaGalleryImages();
        foreach ($imageCollection as $image) {
            if(basename($image->getUrl()) == basename($url))
            {
                return array(
                    'id' => $image->getId(),
                    'url' => $url
                );   
            }
        }
    }  
}