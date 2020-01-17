define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'paynimopayment',
                component: 'Techprocess_Paynimo/js/view/payment/method-renderer/paynimopayment-method'
            }
        );
        return Component.extend({});
    }
);