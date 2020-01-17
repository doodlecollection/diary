define(
    [
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/full-screen-loader',
        'jquery',
        'Magento_Checkout/js/action/get-totals',
        'mage/url',
    ],
    function (quote, fullScreenLoader, jQuery, getTotalsAction,url) {
        'use strict';
        return function (paymentMethod) {
            quote.paymentMethod(paymentMethod);

            fullScreenLoader.startLoader();

            jQuery.ajax(url.build('codextracharge/checkout/applyPaymentMethod'), {
                data: {payment_method: paymentMethod},
                complete: function () {
                    getTotalsAction([]);
                    fullScreenLoader.stopLoader();
                }
            });

        }
    }
);