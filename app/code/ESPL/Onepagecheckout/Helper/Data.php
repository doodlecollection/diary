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

namespace ESPL\Onepagecheckout\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const ENABLE_OPC = 'espl_opc/general/enable_in_frontend';
    const META_TITLE = 'espl_opc/general/espl_title';

    public function getEnable()
    {
        return (bool)$this->scopeConfig->getValue(self::ENABLE_OPC);
    }

    public function getMetaTitle()
    {
        return $this->scopeConfig->getValue(self::META_TITLE);
    }
}
