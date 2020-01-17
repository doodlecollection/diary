<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_CancelOrder
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\CancelOrder\Block;

class Template extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Bss\CancelOrder\Helper\Data
     */
    protected $helper;

    /**
     * Template constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Bss\CancelOrder\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Bss\CancelOrder\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Bss\CancelOrder\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
