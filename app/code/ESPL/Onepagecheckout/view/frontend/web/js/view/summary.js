/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define*/
define(
    [
        'uiComponent',
        'ESPL_Onepagecheckout/js/model/totals'
    ],
    function (Component, totals) {
        'use strict';
        return Component.extend({
            isLoading: totals.isLoading
        });
    }
);

