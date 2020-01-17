<?php

namespace Emipro\CodExtracharge\Plugin\Payment\Method\CashOnDelivery;

use Emipro\CodExtracharge\Helper\Data as HelperData;
use Magento\OfflinePayments\Model\Cashondelivery;

class Available
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
     * [afterIsAvailable description]
     * @param  Cashondelivery $subject [description]
     * @param  [type]         $result  [description]
     * @return [type]                  [description]
     */
    public function afterIsAvailable(Cashondelivery $subject, $result)
    {
        $codc = $this->helper->codCharges();

        if ($codc["status"] == 2) {
            return false;
        } else {
            return $result;
        }
    }
}
