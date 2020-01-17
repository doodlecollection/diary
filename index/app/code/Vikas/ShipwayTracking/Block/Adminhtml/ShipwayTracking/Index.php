<?php

namespace Vikas\ShipwayTracking\Block\Adminhtml\ShipwayTracking;

use Magento\Framework\View\Element\Template;

class Index extends Template
{
    private $collectionFactory;

    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        echo "testblock";
        
    }

     protected function _toHtml() {
        return $this->_template;
    }
}