/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Emipro_CodExtracharge/js/view/checkout/cart/summary/paymentfee'
    ],
    function (Component) {
        'use strict';

        return Component.extend({

            isDisplayed: function () {
                return true;
            }
        });
    }
);