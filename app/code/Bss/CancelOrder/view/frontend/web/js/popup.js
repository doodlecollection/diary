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
define([
    'jquery'
], function ($) {
    'use strict';
    $.widget('bss.cancel_order_popup', {
        _create: function () {          
            //open popup
            $('.cd-popup-trigger').on('click', function (event) {
                event.preventDefault();
                var orderId = $(this).attr('data-order-id');
                $('#cancel-form .order-id').val(orderId);
                $('.cd-popup').addClass('is-visible');
            });
                
            //close popup
            $('.cd-popup').on('click', function (event) {
                if($(event.target).is('.closepopup') || $(event.target).is('.cd-popup')) {
                    event.preventDefault();
                    $(this).removeClass('is-visible');
                } else if ($(event.target).is('.submitform')) {
                    event.preventDefault();
                    $('#cancel-form').submit();
                }
            });

            //close popup when clicking the esc keyboard button
            $(document).keyup(function (event) {
                if(event.which == '27'){
                    $('.cd-popup').removeClass('is-visible');
                }
            });
        }
    });
    return $.bss.cancel_order_popup;
});
