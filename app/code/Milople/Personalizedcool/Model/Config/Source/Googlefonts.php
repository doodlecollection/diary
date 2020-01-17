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
namespace Milople\Personalizedcool\Model\Config\Source;

class Googlefonts implements \Magento\Framework\Option\ArrayInterface
{
    private $fontCollection = "Droid Serif,Lobster,Open Sans,PT Serif,Lato,Ubuntu,Tangerine,Source Sans Pro,Roboto,Raleway,Rancho,Inconsolata,Droid Sans,Philosopher,Plaster,Rokkitt,Sofia,Playball,Nosifer,Pacifico,Volkorn,Gravitas One,Volkorn,Merriweather,Old Standard TT,Arvo,Josefin Slab,Libre Baskerville,Lora,Oswald";
    public function toOptionArray()
    {
        $names = explode(',', $this->fontCollection);
        $options = array();
        foreach ($names as $n) {
            $options[] = array( 'value' => $n, 'label' => $n);
        }
        return $options;
    }

    public function toArray()
    {
        $names = explode(',', $this->fontCollection);
        $options = array();
        foreach ($names as $n) {
            array_merge($options, array('label' => $n));
        }
        return $options;
    }
}
