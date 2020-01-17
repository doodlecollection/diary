<?php
namespace Emipro\CodExtracharge\Observer;

use Emipro\CodExtracharge\Helper\Data as HelperData;
use Magento\Framework\Event\ObserverInterface;

class Saveproduct implements ObserverInterface
{
    /**
     * [__construct description]
     * @param HelperData $helper [description]
     */
    public function __construct(
        HelperData $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * [execute description]
     * @param  \Magento\Framework\Event\Observer $observer [description]
     * @return [type]                                      [description]
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->helper->getProductIds();
    }
}
