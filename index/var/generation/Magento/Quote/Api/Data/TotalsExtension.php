<?php
namespace Magento\Quote\Api\Data;

/**
 * Extension class for @see \Magento\Quote\Api\Data\TotalsInterface
 */
class TotalsExtension extends \Magento\Framework\Api\AbstractSimpleObject implements \Magento\Quote\Api\Data\TotalsExtensionInterface
{
    /**
     * @return \Amasty\Rules\Api\Data\DiscountBreakdownLineInterface[]|null
     */
    public function getAmruleDiscountBreakdown()
    {
        return $this->_get('amrule_discount_breakdown');
    }

    /**
     * @param \Amasty\Rules\Api\Data\DiscountBreakdownLineInterface[] $amruleDiscountBreakdown
     * @return $this
     */
    public function setAmruleDiscountBreakdown($amruleDiscountBreakdown)
    {
        $this->setData('amrule_discount_breakdown', $amruleDiscountBreakdown);
        return $this;
    }
}
