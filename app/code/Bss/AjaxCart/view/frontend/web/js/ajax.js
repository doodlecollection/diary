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
 * @package    Bss_AjaxCart
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
define([
    'jquery',
    'bssFancybox'
], function ($) {
    'use strict';

    $.widget('bss.ajaxcart', {
        options: {
            addUrl: '',
            quickview: false,
            isProductView: false,
            isSuggestPopup: false,
            quickViewUrl: '',
            addToCartSelector: '.action.tocart'
        },

        productIdInputName: ["product", "product-id", "data-product-id", "data-product"],

        _create: function () {
            this._initAjaxCart();
            window.ajaxCart = this;
        },

        _initAjaxCart: function () {
            var options = this.options;
            var self = this;

            self.element.find(options.addToCartSelector).off('click');
            self.element.find(options.addToCartSelector).click(function (e) {
                e.preventDefault();

                var form = $(this).parents('form').get(0);
                var data = new FormData(form);
                if (form) {
                    var isValid = true;
                    if (options.isProductView) {
                        try {
                            isValid = $(form).valid();
                        } catch(err) {
                            isValid = true;
                        }
                    }

                    if (isValid) {
                        var oldAction = $(form).attr('action');
                        var id = self._findId(this, oldAction, form);

                        if ($.isNumeric(id)) {
                            data.append('id', id);
                            $(form).find('input, select').each(function () {
                                if ($(this).attr('type') === 'file') {
                                    data.append($(this).attr('name'), $(this)[0].files[0]);
                                }
                            });

                            var url_post = $(form).attr('action');
                            if (url_post.indexOf('?options=') != -1) {
                                data.append('specifyOptions','1');
                            }
                            
                            if (options.quickview) {
                                $.fancybox.helpers.overlay.open({parent: 'body'});
                                window.parent.ajaxCart._sendAjax(options.addUrl, data, oldAction);
                                return false;
                            }
                            self._sendAjax(options.addUrl, data, oldAction);
                            return false;
                        }

                        window.location.href = oldAction;
                    }
                } else {
                    var dataPost = $.parseJSON($(this).attr('data-post'));
                    if (dataPost) {
                        var formKey = $("input[name='form_key']").val();
                        var oldAction = dataPost.action;
                        data.append('id', dataPost.data.product);
                        data.append('product', dataPost.data.product);
                        data.append('form_key', formKey);
                        data.append('uenc', dataPost.data.uenc);
                        self._sendAjax(options.addUrl, data, oldAction);
                        return false;
                    } else {
                        var id = self._findId(this);
                        if (id) {
                            e.stopImmediatePropagation();
                            self._requestQuickview(options.quickViewUrl + 'id/' + id);
                            return false;
                        }
                    }
                }
            });
        },

        _findId: function (btn, oldAction, form) {
            var self = this;
            var id = $(btn).attr('data-product-id');

            if($.isNumeric(id)) {
                return id;
            }

            var item = $(btn).closest('li.product-item');
            id = $(item).find('[data-product-id]').attr('data-product-id');

            if ($.isNumeric(id)) {
                return id;
            }

            if (oldAction) {
                var formData = oldAction.split('/');
                for (var i = 0; i < formData.length; i++) {
                    if (self.productIdInputName.indexOf(formData[i]) >= 0) {
                        if ($.isNumeric(formData[i + 1])) {
                            id = formData[i + 1];
                        }
                    }
                }

                if ($.isNumeric(id)) {
                    return id;
                }
            }

            if (form) {
                $(form).find('input').each(function () {
                    if (self.productIdInputName.indexOf($(this).attr('name')) >= 0) {
                        if ($.isNumeric($(this).val())) {
                            id = $(this).val();
                        }
                    }
                });

                if ($.isNumeric(id)) {
                    return id;
                }
            }

            var priceBox = $(btn).closest('.price-box.price-final_price');
            id = $(priceBox).attr('data-product-id');

            if ($.isNumeric(id)) {
                return id;
            }

            return false;
        },

        _sendAjax: function (addUrl, data, oldAction) {
            var options = this.options;
            var self = this;

            self._showLoader();

            if (options.isSuggestPopup) {
                self._extendOverlay();
            }

            $.ajax({
                type: 'post',
                url: addUrl,
                data: data,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.popup) {
                        $('#bss_ajaxcart_popup').html(data.popup);
                        self._showPopup();
                    } else if (data.error && data.view) {
                        if (!options.quickview && !options.isProductView) {
                            self._requestQuickview(data.url);
                        } else {
                            self._hideLoader();
                        }
                    } else {
                        self._hideLoader();
                    }
                },
                error: function () {
                    window.location.href = oldAction;
                }
            });
        },

        _requestQuickview: function (url) {
            $.fancybox.open({
                padding : 0,
                href: url,
                fitToView: false,
                autoSize: false,
                autoWidth: true,
                type: 'iframe',
                helpers: {
                    overlay: {
                        locked: false
                    }
                },
                afterLoad: function () {
                    this.height = $(this.element).data("height");
                    var id = $('.fancybox-type-iframe iframe').prop('id');
                    var height = document.getElementById(id).contentWindow.document.body.scrollHeight + 25;
                    var maxHeight = parseInt($(window).height() * 95 / 100);
                    height = (height > maxHeight) ? maxHeight : height;
                    this.height = height + 'px';
                }
            });
        },

        _showLoader: function () {
            $.fancybox.showLoading();
            $.fancybox.helpers.overlay.open({parent: 'body'});
        },

        _hideLoader: function () {
            $.fancybox.hideLoading();
            $.fancybox.helpers.overlay.close();
        },

        _showPopup: function () {
            var isMobile = false;
            if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)                 || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
                isMobile = true;            
            }   
            var configFancy = {
                href: '#bss_ajaxcart_popup',
                modal: false,
                helpers: {
                    overlay: {
                        locked: false
                    }
                },
                afterClose: function () {
                    clearInterval(window.count);
                },
            }
            if (!isMobile) {
                configFancy.wrapCSS = 'bss-ajaxcart-popup';
            }
            $.fancybox(configFancy);

            $("#bss_ajaxcart_popup").trigger('contentUpdated');
        },

        _extendOverlay: function () {
            $('.fancybox-overlay').css('z-index', '8050');
        }
    });

    return $.bss.ajaxcart;
});
