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
namespace ESPL\Onepagecheckout\Block\Account;

use Magento\Customer\Model\Url;
use Magento\Framework\View\Element\Template;

class Forgotpassword extends Template
{
    public $customerUrl;
    
    public function __construct(
        Template\Context $context,
        Url $customerUrl,
        array $data = []
    ) {
        $this->customerUrl = $customerUrl;
        parent::__construct($context, $data);
    }

    public function getLoginUrl()
    {
        return $this->customerUrl->getLoginUrl();
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('customer/account/forgotpasswordpost', ['_secure' => true]);
    }

    public function getPostUrl()
    {
        return $this->getUrl('onepagecheckout/account/forgotpassword', ['_secure' => true]);
    }
}
