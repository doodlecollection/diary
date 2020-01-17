var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'ESPL_Onepagecheckout/js/model/agreements/place-order-mixin': true,
                'ESPL_Onepagecheckout/js/model/place-order-with-comments-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'ESPL_Onepagecheckout/js/model/payment/place-order-mixin': true
            }
        }
    },
    map: {
        "*": {
            "Magento_Checkout/js/model/shipping-save-processor/default": "ESPL_Onepagecheckout/js/model/shipping-save-processor/default"
        }
    }
};
