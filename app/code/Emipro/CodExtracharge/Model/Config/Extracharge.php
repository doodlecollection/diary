<?php

namespace Emipro\CodExtracharge\Model\Config;

class Extracharge implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Order Subtotal')],
            ['value' => 1, 'label' => __('Order Items')],
        ];
    }
}
