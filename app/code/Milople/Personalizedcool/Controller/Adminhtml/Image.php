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

namespace Milople\Personalizedcool\Controller\Adminhtml;

use Milople\Personalizedcool\Api\ImageRepositoryInterface;
use Milople\Personalizedcool\Api\Data\ImageInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\Page;

abstract class Image extends Action
{
    /**
     * @var ImageInterfaceFactory
     */
    protected $imageFactory;

    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ImageRepositoryInterface $imageRepository
     * @param ImageInterfaceFactory $imageFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ImageRepositoryInterface $imageRepository,
        ImageInterfaceFactory $imageFactory
    )
    {
        $this->coreRegistry = $coreRegistry;
        $this->imageRepository = $imageRepository;
        $this->imageFactory = $imageFactory;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage)
    {
        $resultPage->setActiveMenu('Milople_Personalizedcool::clip_image')
            ->addBreadcrumb(__('Category'), __('Category'))
            ->addBreadcrumb(__('Images'), __('Images'));
        return $resultPage;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Milople_Personalizedcool::clip_image');
    }
}