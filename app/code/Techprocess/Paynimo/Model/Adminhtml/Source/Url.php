<?php
/**
* Custom Options for paynimo backend configuration for WSD Locator Url
**/

namespace Techprocess\Paynimo\Model\Adminhtml\Source;

use Magento\Payment\Model\Method\AbstractMethod;

class Url implements \Magento\Framework\Option\ArrayInterface

{
    protected $_options;

    public function toOptionArray()
    {
         $trans_req = array(
           array('value' => 'test', 'label' => 'TEST'),
           array('value' => 'live', 'label' => 'LIVE'),
       );
 
       return $trans_req;
    }
}
?>