(function(require){
(function() {
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
 var config = {
 	map: {
 		'*': {
 			lofallOwlCarousel: 'Lof_All/lib/owl.carousel/owl.carousel.min',
 			lofallBootstrap: 'Lof_All/lib/bootstrap/js/bootstrap.min',
 			lofallColorbox: 'Lof_All/lib/colorbox/jquery.colorbox.min',
 			lofallFancybox: 'Lof_All/lib/fancybox/jquery.fancybox.pack',
 			lofallFancyboxMouseWheel: 'Lof_All/lib/fancybox/jquery.mousewheel-3.0.6.pack'
 		}
 	},
 	shim: {
        'Lof_All/lib/bootstrap/js/bootstrap.min': {
            'deps': ['jquery']
        },
        'Lof_All/lib/bootstrap/js/bootstrap': {
            'deps': ['jquery']
        },
        'Lof_All/lib/owl.carousel/owl.carousel': {
            'deps': ['jquery']
        },
        'Lof_All/lib/owl.carousel/owl.carousel.min': {
        	'deps': ['jquery']
        },
        'Lof_All/lib/fancybox/jquery.fancybox': {
            'deps': ['jquery']  
        },
        'Lof_All/lib/fancybox/jquery.fancybox.pack': {
            'deps': ['jquery']  
        },
        'Lof_All/lib/colorbox/jquery.colorbox': {
            'deps': ['jquery']  
        },
        'Lof_All/lib/colorbox/jquery.colorbox.min': {
            'deps': ['jquery']  
        }
    }
 };
require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    "waitSeconds": 0,
    "map": {
        "*": {
            "ko": "knockoutjs/knockout",
            "knockout": "knockoutjs/knockout",
            "mageUtils": "mage/utils/main",
            "rjsResolver": "mage/requirejs/resolver"
        }
    },
    "shim": {
        "jquery/jquery-migrate": ["jquery"],
        "jquery/jquery.hashchange": ["jquery", "jquery/jquery-migrate"],
        "jquery/jstree/jquery.hotkeys": ["jquery"],
        "jquery/hover-intent": ["jquery"],
        "mage/adminhtml/backup": ["prototype"],
        "mage/captcha": ["prototype"],
        "mage/common": ["jquery"],
        "mage/new-gallery": ["jquery"],
        "mage/webapi": ["jquery"],
        "jquery/ui": ["jquery"],
        "MutationObserver": ["es6-collections"],
        "tinymce": {
            "exports": "tinymce"
        },
        "moment": {
            "exports": "moment"
        },
        "matchMedia": {
            "exports": "mediaCheck"
        },
        "jquery/jquery-storageapi": {
            "deps": ["jquery/jquery.cookie"]
        }
    },
    "paths": {
        "jquery/validate": "jquery/jquery.validate",
        "jquery/hover-intent": "jquery/jquery.hoverIntent",
        "jquery/file-uploader": "jquery/fileUploader/jquery.fileupload-fp",
        "jquery/jquery.hashchange": "jquery/jquery.ba-hashchange.min",
        "prototype": "legacy-build.min",
        "jquery/jquery-storageapi": "jquery/jquery.storageapi.min",
        "text": "mage/requirejs/text",
        "domReady": "requirejs/domReady",
        "tinymce": "tiny_mce/tiny_mce_src"
    },
    "deps": [
        "jquery/jquery-migrate"
    ],
    "config": {
        "mixins": {
            "jquery/jstree/jquery.jstree": {
                "mage/backend/jstree-mixin": true
            }
        }
    }
};

require(['jquery'], function ($) {
    $.noConflict();
});

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        "*": {
            "rowBuilder":             "Magento_Theme/js/row-builder",
            "toggleAdvanced":         "mage/toggle",
            "translateInline":        "mage/translate-inline",
            "sticky":                 "mage/sticky",
            "tabs":                   "mage/tabs",
            "zoom":                   "mage/zoom",
            "collapsible":            "mage/collapsible",
            "dropdownDialog":         "mage/dropdown",
            "dropdown":               "mage/dropdowns",
            "accordion":              "mage/accordion",
            "loader":                 "mage/loader",
            "tooltip":                "mage/tooltip",
            "deletableItem":          "mage/deletable-item",
            "itemTable":              "mage/item-table",
            "fieldsetControls":       "mage/fieldset-controls",
            "fieldsetResetControl":   "mage/fieldset-controls",
            "redirectUrl":            "mage/redirect-url",
            "loaderAjax":             "mage/loader",
            "menu":                   "mage/menu",
            "popupWindow":            "mage/popup-window",
            "validation":             "mage/validation/validation",
            "welcome":                "Magento_Theme/js/view/welcome"
        }
    },
    paths: {
        "jquery/ui": "jquery/jquery-ui"
    },
    deps: [
        "jquery/jquery.mobile.custom",
        "js/responsive",
        "mage/common",
        "mage/dataPost",
        "js/theme",
        "mage/bootstrap"
    ]
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            checkoutBalance:    'Magento_Customer/js/checkout-balance',
            address:            'Magento_Customer/address',
            changeEmailPassword: 'Magento_Customer/change-email-password',
            passwordStrengthIndicator: 'Magento_Customer/js/password-strength-indicator',
            zxcvbn: 'Magento_Customer/js/zxcvbn'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            compareItems:           'Magento_Catalog/js/compare',
            compareList:            'Magento_Catalog/js/list',
            relatedProducts:        'Magento_Catalog/js/related-products',
            upsellProducts:         'Magento_Catalog/js/upsell-products',
            productListToolbarForm: 'Magento_Catalog/js/product/list/toolbar',
            catalogGallery:         'Magento_Catalog/js/gallery',
            priceBox:               'Magento_Catalog/js/price-box',
            priceOptionDate:        'Magento_Catalog/js/price-option-date',
            priceOptionFile:        'Magento_Catalog/js/price-option-file',
            priceOptions:           'Magento_Catalog/js/price-options',
            priceUtils:             'Magento_Catalog/js/price-utils',
            catalogAddToCart:       'Magento_Catalog/js/catalog-add-to-cart'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            creditCardType: 'Magento_Payment/cc-type'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            quickSearch: 'Magento_Search/form-mini'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
/*global alert*/
var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Magento_CheckoutAgreements/js/model/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Magento_CheckoutAgreements/js/model/set-payment-information-mixin': true
            }
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            addToCart: 'Magento_Msrp/js/msrp'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            requireCookie: 'Magento_Cookie/js/require-cookie',
            cookieNotices: 'Magento_Cookie/js/notices'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            giftMessage:    'Magento_Sales/gift-message',
            ordersReturns:  'Magento_Sales/orders-returns'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            bundleOption:   'Magento_Bundle/bundle',
            priceBundle:    'Magento_Bundle/js/price-bundle',
            slide:          'Magento_Bundle/js/slide',
            productSummary: 'Magento_Bundle/js/product-summary'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            downloadable: 'Magento_Downloadable/downloadable'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            giftOptions:    'Magento_GiftMessage/gift-options',
            extraOptions:   'Magento_GiftMessage/extra-options'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            discountCode:           'Magento_Checkout/js/discount-codes',
            shoppingCart:           'Magento_Checkout/js/shopping-cart',
            regionUpdater:          'Magento_Checkout/js/region-updater',
            sidebar:                'Magento_Checkout/js/sidebar',
            checkoutLoader:         'Magento_Checkout/js/checkout-loader',
            checkoutData:           'Magento_Checkout/js/checkout-data',
            proceedToCheckout:      'Magento_Checkout/js/proceed-to-checkout'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    paths: {
        'ui/template': 'Magento_Ui/templates'
    },
    map: {
        '*': {
            uiElement:      'Magento_Ui/js/lib/core/element/element',
            uiCollection:   'Magento_Ui/js/lib/core/collection',
            uiComponent:    'Magento_Ui/js/lib/core/collection',
            uiClass:        'Magento_Ui/js/lib/core/class',
            uiEvents:       'Magento_Ui/js/lib/core/events',
            uiRegistry:     'Magento_Ui/js/lib/registry/registry',
            uiLayout:       'Magento_Ui/js/core/renderer/layout',
            buttonAdapter:  'Magento_Ui/js/form/button-adapter'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            configurable: 'Magento_ConfigurableProduct/js/configurable'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            multiShipping: 'Magento_Multishipping/js/multi-shipping',
            orderOverview: 'Magento_Multishipping/js/overview',
            payment: 'Magento_Multishipping/js/payment'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            pageCache:  'Magento_PageCache/js/page-cache'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            orderReview:            'Magento_Paypal/order-review',
            paypalCheckout:         'Magento_Paypal/js/paypal-checkout'
        }
    },
    paths: {
        paypalInContextExpressCheckout: 'https://www.paypalobjects.com/api/checkout'
    },
    shim: {
        paypalInContextExpressCheckout: {
            exports: 'paypal'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            transparent:            'Magento_Payment/transparent'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*eslint no-unused-vars: 0*/
var config = {
    map: {
        '*': {
            loadPlayer: 'Magento_ProductVideo/js/load-player',
            fotoramaVideoEvents: 'Magento_ProductVideo/js/fotorama-add-video-events'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            captcha: 'Magento_Captcha/captcha'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            recentlyViewedProducts: 'Magento_Reports/js/recently-viewed'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            transparent: 'Magento_Payment/transparent'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            catalogSearch: 'Magento_CatalogSearch/form-mini'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            editTrigger:   'mage/edit-trigger',
            addClass:      'Magento_Translation/add-class'
        }
    },
    deps: [
        'mage/translate-inline'
    ]
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            braintree: 'https://js.braintreegateway.com/js/braintree-2.25.0.min.js'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        "*": {
            "taxToggle": "Magento_Weee/tax-toggle"
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            wishlist:       'Magento_Wishlist/wishlist',
            addToWishlist:  'Magento_Wishlist/js/add-to-wishlist',
            wishlistSearch: 'Magento_Wishlist/js/search'
        }
    }
};

require.config(config);
})();
(function() {
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Core
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

var config = {
    paths: {
        'mageplaza/core/jquery/popup': 'Mageplaza_Core/js/jquery.magnific-popup.min',
        'mageplaza/core/owl.carousel': 'Mageplaza_Core/js/owl.carousel.min',
        'mageplaza/core/bootstrap': 'Mageplaza_Core/js/bootstrap.min',
        mpIonRangeSlider: 'Mageplaza_Core/js/ion.rangeSlider.min',
        touchPunch: 'Mageplaza_Core/js/jquery.ui.touch-punch.min',
        mpDevbridgeAutocomplete: 'Mageplaza_Core/js/jquery.autocomplete.min'
    },
    shim: {
        "mageplaza/core/jquery/popup": ["jquery"],
        "mageplaza/core/owl.carousel": ["jquery"],
        "mageplaza/core/bootstrap": ["jquery"],
        mpIonRangeSlider: ["jquery"],
        mpDevbridgeAutocomplete: ["jquery"],
        touchPunch: ['jquery', 'jquery/ui']
    }
};

require.config(config);
})();
(function() {
var config = {
	map: {
		'*': {
			'magestore/note': 'Magestore_Bannerslider/js/jquery/slider/jquery-ads-note',
			'magestore/impress': 'Magestore_Bannerslider/js/report/impress',
			'magestore/clickbanner': 'Magestore_Bannerslider/js/report/clickbanner',
		},
	},
	paths: {
		'magestore/flexslider': 'Magestore_Bannerslider/js/jquery/slider/jquery-flexslider-min',
		'magestore/evolutionslider': 'Magestore_Bannerslider/js/jquery/slider/jquery-slider-min',
		'magestore/popup': 'Magestore_Bannerslider/js/jquery.bpopup.min',
	},
	shim: {
		'magestore/flexslider': {
			deps: ['jquery']
		},
		'magestore/evolutionslider': {
			deps: ['jquery']
		},
		'magestore/zebra-tooltips': {
			deps: ['jquery']
		},
	}
};

require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            personalized: 'Milople_Personalized/js/personalized'
	    }
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            productListToolbarForm : 'TemplateMonster_AjaxCatalog/js/tm-catalog-ajax'
        }
    },
    paths: {
        priceRangeSlider: 'TemplateMonster_AjaxCatalog/js/tm-price-slider'
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
             "compareItems" : "TemplateMonster_AjaxCompare/js/tm-compare-ajax",
             "showCompareProduct" : "TemplateMonster_AjaxCompare/js/compare-products-top-link",
        }
    }
};

require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            "quickSearch" : "TemplateMonster_AjaxSearch/js/tm-search-ajax"
        }
    },
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            'ajaxWishlist': 'TemplateMonster_AjaxWishlist/js/ajaxwishlist',
            'wishlist': 'TemplateMonster_AjaxWishlist/js/wishlist'
        }
    }
};
require.config(config);
})();
(function() {

var config = {
    map: {
        '*': {}
    },
    paths: {
        "blogCarousel":           "TemplateMonster_Blog/js/blog-carousel",
        "categoriesListCollapse": "TemplateMonster_Blog/js/categories-list-collapse",
        "blogOwlCarousel":        "TemplateMonster_Blog/js/owl.carousel"
    },
    shim: {
        "blogOwlCarousel":  ["jquery"]
    }
};


require.config(config);
})();
(function() {

var config = {

	map: {
        '*': {
            "countdowntimer": "TemplateMonster_CountdownTimer/js/countdowntimer",
            "jquery.countdown": "TemplateMonster_CountdownTimer/js/jquery.countdown"
        }
    },
    "shim" : {
        "jquery.countdown": ["jquery"]
    }};
require.config(config);
})();
(function() {
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {}
    },
    paths: {
        "featuredCarousel":       "TemplateMonster_FeaturedProduct/js/featured-carousel",
        "featuredTab":            "TemplateMonster_FeaturedProduct/js/featured-tab",
        "featuredOwlCarousel":    "TemplateMonster_FeaturedProduct/js/owl.carousel2",
        "featuredProductGallery": "TemplateMonster_FeaturedProduct/js/featured-product-gallery",
        "featuredSwitchImage":    "TemplateMonster_FeaturedProduct/js/featured-switch-image",
    },
    shim: {
        "featuredOwlCarousel":  ["jquery"]
    }
};


require.config(config);
})();
(function() {
var config = {

    paths: {
        sliderInit: 'TemplateMonster_FilmSlider/js/jquery.sliderPro',
        FilmSlider: 'TemplateMonster_FilmSlider/js/film-slider'
    },
    shim: {
    	"sliderInit": ["jquery"],
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
             "googleMapPagePlugin" : "TemplateMonster_GoogleMap/js/google-map-page-plugin"
        }
    }
};

require.config(config);
})();
(function() {

var config = {

	map: {
        '*': {}
    },
    paths: {
        "megamenu":        "TemplateMonster_Megamenu/js/megamenu",
        "subMenuViewMode": "TemplateMonster_Megamenu/js/subMenuViewMode"
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            'newsletterPopup': 'TemplateMonster_NewsletterPopup/js/popup'
        }
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        "*": {
            "parallax": "TemplateMonster_Parallax/js/parallax"
        }
    },
    paths: {
        "jquery.rd-parallax": "TemplateMonster_Parallax/js/rd-parallax/jquery.rd-parallax",
        "jquery.youtubebackground": "TemplateMonster_Parallax/js/jquery-youtubebackground/jquery.youtubebackground",
        "jquery.vide": "TemplateMonster_Parallax/js/jquery-vide/jquery.vide"
    },
    shim: {
        "jquery.rd-parallax": ["jquery"],
        "jquery.youtubebackground": ["jquery"],
        "jquery.vide": ["jquery"]
    }
};
require.config(config);
})();
(function() {
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            "smartLabels":     "TemplateMonster_ProductLabels/js/smart-labels"
        }
    }
};


require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {}
    },
    paths: {
        "productListingGallery": "TemplateMonster_ThemeOptions/js/product-listing-gallery",
        "switchImage": "TemplateMonster_ThemeOptions/js/switch-image",
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            "brandAddProductPage" : "TemplateMonster_ShopByBrand/js/brand-add-product-page",
            "brandCarousel":     "TemplateMonster_ShopByBrand/js/brand-carousel",
            "brandOwlCarousel":      'TemplateMonster_ShopByBrand/js/owl.carousel'
        }
    },
    shim: {
        "brandOwlCarousel":  ["jquery"]
    }
};

require.config(config);
})();
(function() {

var config = {

	map: {
        '*': {
            "smcountdowntimer": "TemplateMonster_SiteMaintenance/js/countdowntimer",
            "jquery.countdown.sm": "TemplateMonster_SiteMaintenance/js/jquery.countdown"
        }
    },
    "shim" : {
        "jquery.countdown.sm": ["jquery"]
    }};
require.config(config);
})();
(function() {
/**
 * Require-js
 *
 * @category  Webkul
 * @package   Webkul_Walletsystem
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
var config = {
    map: {
        '*': {
            Wkwalletsystem: 'Webkul_Walletsystem/js/Wkwalletsystem',
        }
    }
};
require.config(config);
})();
(function() {
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            "theme": 'js/theme',
            "selectize":    "js/selectize",
            "googleMapOptions": "js/googleMapOptions"
        }
    },
    paths: {
        "carouselInit":     'js/carouselInit',
        "blockCollapse":    'js/sidebarCollapse',
        "animateNumber":    'Magento_Theme/js/jquery.animateNumber.min',
        "owlcarousel":      'Magento_Theme/js/owl.carousel.min',
        "customSelect":     "Magento_Theme/js/select2",
        "doubleTap":        "Magento_Theme/js/doubletaptogo",
        "stickUpNav":        "Magento_Theme/js/stickUp",
        "googleMapOptions": "js/googleMapOptions"
    },
    shim: {
        "owlcarousel":      ["jquery"],
        "animateNumber":    ["jquery"],
        "doubleTap":        ["jquery", "jquery/ui"],
        "stickUpNav":        ["jquery"]
    },
    deps: [
        "jquery",
        "jquery/jquery.mobile.custom",
        "mage/common",
        "mage/dataPost",
        "mage/bootstrap"
    ]
};
require.config(config);
})();
(function() {
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            "modalFilter":     "Magento_LayeredNavigation/js/modal-filter"
        }
    }
};
require.config(config);
})();
(function() {
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    map: {
        '*': {
            reviewRatingBehave: 'Magento_Review/js/reviewRating'
        }
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            "comparePopup" : "TemplateMonster_AjaxCompare/js/compare-popup",
        }
    }
};
require.config(config);
})();
(function() {
var config = {
    map: {
        '*': {
            "quickSearch": "TemplateMonster_AjaxSearch/js/form-mini-plugin"
        }
    }
};
require.config(config);
})();
(function() {

var config = {
    map: {
        '*': {}
    },
    paths: {
        "themeBlogCarousel":       "TemplateMonster_Blog/js/theme-blog-carousel"
    }
};

require.config(config);
})();
(function() {

var config = {
    map: {
        '*': {}
    },
    paths: {
        "themeFeaturedCarousel":       "TemplateMonster_FeaturedProduct/js/theme-featured-carousel"
    }
};

require.config(config);
})();



})(require);