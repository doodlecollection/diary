<?php
/**
 * ESPL_Onepagecheckout extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ESPL  License
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.elitechsystems.com/license.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@elitechsystems.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * versions in the future. If you wish to customize this extension for your
 * needs please refer to http://www.elitechsystems.com for more information.
 *
 * @category   ESPL
 * @package    ESPL_Onepagecheckout
 * @author-email  info@elitechsystems.com
 * @copyright  Copyright 2017 � elitechsystems.com. All Rights Reserved
 */
namespace ESPL\Onepagecheckout\Block\Js;

use Magento\Customer\Model\Url;
use Magento\Framework\View\Element\Template;

class Components extends \Magento\Framework\View\Element\Template
{

    const DISCOUNTS_ENABLE = 'espl_opc/general/espl_discount_code_enable';

    public function getDiscountsEnable()
    {
        return $this->_scopeConfig->getValue(self::DISCOUNTS_ENABLE);
    }
}
