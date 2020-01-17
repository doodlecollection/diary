<?php
namespace Magento\Config\Model\Config\Loader;

/**
 * Interceptor class for @see \Magento\Config\Model\Config\Loader
 */
class Interceptor extends \Magento\Config\Model\Config\Loader implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\App\Config\ValueFactory $configValueFactory)
    {
        $this->___init();
        parent::__construct($configValueFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigByPath($path, $scope, $scopeId, $full = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getConfigByPath');
        if (!$pluginInfo) {
            return parent::getConfigByPath($path, $scope, $scopeId, $full);
        } else {
            return $this->___callPlugins('getConfigByPath', func_get_args(), $pluginInfo);
        }
    }
}
