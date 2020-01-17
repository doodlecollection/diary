<?php
namespace Magento\Theme\Model\Favicon\Favicon;

/**
 * Interceptor class for @see \Magento\Theme\Model\Favicon\Favicon
 */
class Interceptor extends \Magento\Theme\Model\Favicon\Favicon implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDatabase, \Magento\Framework\Filesystem $filesystem)
    {
        $this->___init();
        parent::__construct($storeManager, $scopeConfig, $fileStorageDatabase, $filesystem);
    }

    /**
     * {@inheritdoc}
     */
    public function getFaviconFile()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFaviconFile');
        if (!$pluginInfo) {
            return parent::getFaviconFile();
        } else {
            return $this->___callPlugins('getFaviconFile', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultFavicon()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDefaultFavicon');
        if (!$pluginInfo) {
            return parent::getDefaultFavicon();
        } else {
            return $this->___callPlugins('getDefaultFavicon', func_get_args(), $pluginInfo);
        }
    }
}
