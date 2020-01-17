<?php
namespace Magento\SalesRule\Model\RulesApplier;

/**
 * Interceptor class for @see \Magento\SalesRule\Model\RulesApplier
 */
class Interceptor extends \Magento\SalesRule\Model\RulesApplier implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\SalesRule\Model\Rule\Action\Discount\CalculatorFactory $calculatorFactory, \Magento\Framework\Event\ManagerInterface $eventManager, \Magento\SalesRule\Model\Utility $utility)
    {
        $this->___init();
        parent::__construct($calculatorFactory, $eventManager, $utility);
    }

    /**
     * {@inheritdoc}
     */
    public function applyRules($item, $rules, $skipValidation, $couponCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'applyRules');
        if (!$pluginInfo) {
            return parent::applyRules($item, $rules, $skipValidation, $couponCode);
        } else {
            return $this->___callPlugins('applyRules', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addDiscountDescription($address, $rule)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addDiscountDescription');
        if (!$pluginInfo) {
            return parent::addDiscountDescription($address, $rule);
        } else {
            return $this->___callPlugins('addDiscountDescription', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function maintainAddressCouponCode($address, $rule, $couponCode)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'maintainAddressCouponCode');
        if (!$pluginInfo) {
            return parent::maintainAddressCouponCode($address, $rule, $couponCode);
        } else {
            return $this->___callPlugins('maintainAddressCouponCode', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAppliedRuleIds(\Magento\Quote\Model\Quote\Item\AbstractItem $item, array $appliedRuleIds)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setAppliedRuleIds');
        if (!$pluginInfo) {
            return parent::setAppliedRuleIds($item, $appliedRuleIds);
        } else {
            return $this->___callPlugins('setAppliedRuleIds', func_get_args(), $pluginInfo);
        }
    }
}
