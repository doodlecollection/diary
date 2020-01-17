<?php
namespace Magento\CatalogSearch\Model\Autocomplete\DataProvider;

/**
 * Interceptor class for @see \Magento\CatalogSearch\Model\Autocomplete\DataProvider
 */
class Interceptor extends \Magento\CatalogSearch\Model\Autocomplete\DataProvider implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Search\Model\QueryFactory $queryFactory, \Magento\Search\Model\Autocomplete\ItemFactory $itemFactory)
    {
        $this->___init();
        parent::__construct($queryFactory, $itemFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getItems');
        if (!$pluginInfo) {
            return parent::getItems();
        } else {
            return $this->___callPlugins('getItems', func_get_args(), $pluginInfo);
        }
    }
}
