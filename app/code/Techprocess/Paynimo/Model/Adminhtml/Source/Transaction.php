<?php
/**
* Custom Options for paynimo backend configuration for Transaction Request Type
**/
namespace Techprocess\Paynimo\Model\Adminhtml\Source;

use Magento\Payment\Model\Method\AbstractMethod;

class Transaction implements \Magento\Framework\Option\ArrayInterface

{
    protected $_options;

    public function toOptionArray()
    {
         $trans_req = array(
           array('value' => 'T', 'label' => 'T'),
       );
 
       return $trans_req;
    }
}
?>