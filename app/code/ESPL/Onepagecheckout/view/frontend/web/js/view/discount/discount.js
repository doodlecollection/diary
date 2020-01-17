/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'ESPL_Onepagecheckout/js/espl/set-coupon-code',
        'ESPL_Onepagecheckout/js/espl/cancel-coupon',
        'ESPL_Onepagecheckout/js/espl/components'
    ],
    function ($, ko, Component, quote, setCouponCodeAction, cancelCouponAction) {
        'use strict';
        var totals = quote.getTotals();
        var couponCode = ko.observable(null);
        if (totals()) {
            couponCode(totals()['coupon_code']);
        }

        var isApplied = ko.observable(couponCode() != null);
        var isLoading = ko.observable(false);
        return Component.extend({
            defaults: {
                template: 'ESPL_Onepagecheckout/payment/discount'
            },
            couponCode: couponCode,
            /**
             * Applied flag
             */
            isApplied: isApplied,
            isLoading: isLoading,
            /**
             * Coupon code application procedure
             */

            apply: function () {
                if (this.validate()) {
                    isLoading(true);
                    setCouponCodeAction(couponCode(), isApplied, isLoading);
                }
            },
            /**
             * Cancel using coupon
             */
            cancel: function () {
                if (this.validate()) {
                    isLoading(true);
                    couponCode('');
                    cancelCouponAction(isApplied, isLoading);
                }
            },
            /**
             * Coupon form validation
             *
             * @returns {boolean}
             */
            validate: function () {
                var form = '#discount-form';
                return $(form).validation() && $(form).validation('isValid');
            }

        });
    }
);

