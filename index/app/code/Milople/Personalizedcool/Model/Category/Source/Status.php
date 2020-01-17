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
namespace Milople\Personalizedcool\Model\Category\Source;
use Magento\Framework\Data\OptionSourceInterface;
class Status implements OptionSourceInterface
{
    const ENABLED = 1;
    const DISABLED = 0;

    protected $options = null;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [
                [
                    'label' => __('Enabled'),
                    'value' => static::ENABLED,
                ],
                [
                    'label' => __('Disabled'),
                    'value' => static::DISABLED,
                ]
            ];
        }

        return $this->options;
    }
}
