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
namespace Milople\Personalizedcool\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $scopeConfig;

    
    public function __construct(
    \Magento\Framework\App\RequestInterface $httpRequest, 
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, 
    \Psr\Log\LoggerInterface $logger, 
    \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this -> scopeConfig = $scopeConfig;
        $this -> storeManager = $storeManager;
        $this -> logger = $logger;
        $this-> request = $httpRequest;
    }
    
    public function getDomain()
    {
        $domain =$this->request->getServer('SERVER_NAME');
        $temp = explode('.', $domain);
        $exceptions = array('co.uk', 'com.au', 'com.hk', 'co.nz', 'co.in', 'com.sg');
        $count = count($temp);
        if ($count === 1) {
            return $domain;
        }
        $last = $temp[($count - 2)] . '.' . $temp[($count - 1)];
        if (in_array($last, $exceptions)) {
            $new_domain = $temp[($count - 3)] . '.' . $temp[($count - 2)] . '.' . $temp[($count - 1)];
        } else {
            $new_domain = $temp[($count - 2)] . '.' . $temp[($count - 1)];
        }

        return $new_domain;
    }
    public function checkEntry($domain, $serial)
    {
        $key = sha1(base64_decode('TTJQZXJzb25hbGl6ZWRQcm9kdWN0c0Nvb2w='));
        if (sha1($key . $domain) == $serial) {
            return true;
        }
        return false;
    }

    public function canRun($temp = '')
    {

		$domain =$this->request->getServer('SERVER_NAME');
        if ($domain == "localhost" || $domain == "127.0.0.1") {
            return true;
        }

        if ($temp == '') {
            $temp = $this ->scopeConfig->getValue('personalizedcool/license_status_group/serial_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }
        $url = $this -> storeManager -> getStore() -> getBaseUrl();
        $parsedUrl = parse_url($url);
        $host = explode('.', $parsedUrl['host']);
        $subdomains = array_slice($host, 0, count($host) - 2);
        if (sizeof($subdomains) && ($subdomains[0] == 'test' || $subdomains[0] == 'demo' || $subdomains[0] == 'dev')) {
            return true;
        }
        $original = $this -> checkEntry($this->request->getServer('SERVER_NAME'), $temp);
        $wildcard = $this -> checkEntry($this -> getDomain(), $temp);
        if (!$original && !$wildcard) {
            return false;
        }
        return true;
    }
    public function getMessage()
    {
        return base64_decode('PGRpdj5MaWNlbnNlIG9mIDxiPk1pbG9wbGUgUGVyc29uYWxpemVkIENvb2wgUHJvZHVjdHM8L2I+IGV4dGVuc2lvbiBoYXMgYmVlbiB2aW9sYXRlZC4gVG8gZ2V0IHNlcmlhbCBrZXkgcGxlYXNlIGNvbnRhY3QgdXMgb24gPGI+aHR0cHM6Ly93d3cubWlsb3BsZS5jb20vbWFnZW50by1leHRlbnNpb25zL2NvbnRhY3RzLzwvYj4uPC9kaXY+');
    }
    public function getAdminMessage()
    {
        return base64_decode('PGRpdj5MaWNlbnNlIG9mIDxiPk1pbG9wbGUgUGVyc29uYWxpemVkIENvb2wgUHJvZHVjdHM8L2I+IGV4dGVuc2lvbiBoYXMgYmVlbiB2aW9sYXRlZC4gVG8gZ2V0IHNlcmlhbCBrZXkgcGxlYXNlIGNvbnRhY3QgdXMgb24gPGI+aHR0cHM6Ly93d3cubWlsb3BsZS5jb20vbWFnZW50by1leHRlbnNpb25zL2NvbnRhY3RzLzwvYj4uPC9kaXY+');
    }
}
