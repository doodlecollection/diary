define(
    [
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery/ui',
    'jquery/validate',
    'ESPL_Onepagecheckout/js/espl/plugins/jquery.nicescroll.min'
    ],
    function ($, modal) {
        'use strict';
        $.widget('mage.esplpopup', {
            popup: null,
            init: function () {
                this.showModal();
                this.inputText();
                this.cvvText();
                this.sendForm();
                this.newModal();
            },

            inputText: function () {
                $(document).off('blur', '#authorizenet_directpost_cc_number');
                $(document).on('blur', '#authorizenet_directpost_cc_number', function (e) {

                    if ($('#authorizenet_directpost_cc_number').val() == 0) {
                        $(this).closest('div.number').find('label').removeClass('focus');
                    }
                });

                $(document).off('focus', '#authorizenet_directpost_cc_number');
                $(document).on('focus', '#authorizenet_directpost_cc_number', function (e) {

                    $(this).closest('div.number').find('label').addClass('focus');


                });
            },
            cvvText: function () {
                $(document).off('blur', '#authorizenet_directpost_cc_cid');
                $(document).on('blur', '#authorizenet_directpost_cc_cid', function (e) {

                    if ($('#authorizenet_directpost_cc_cid').val() == 0) {
                        $(this).closest('div.cvv').find('label').removeClass('focus');
                    }
                });

                $(document).off('focus', '#authorizenet_directpost_cc_cid');
                $(document).on('focus', '#authorizenet_directpost_cc_cid', function (e) {

                    $(this).closest('div.cvv').find('label').addClass('focus');


                });
            },
            showModal: function () {
                var _self = this;
                $(document).off('click touchstart', '.actions-toolbar .remind');
                $(document).on('click touchstart', '.actions-toolbar .remind', function (e) {
                    e.preventDefault();
                    $('.espl-forgot-response-message').hide();
                    _self.displayModal();
                });
            },

            newModal: function () {
                var _self = this;
                $(document).on('click touchstart', '.actions-toolbar .remind', function (e) {
                    e.preventDefault();
                    _self.reopenModal();
                });
            },

            reopenModal: function () {
                $(".espl-forgot-main-wrapper").modal('openModal');
            },

            displayModal: function () {
                var modalContent = $(".espl-forgot-main-wrapper");
                this.popup = modalContent.modal({
                    autoOpen: true,
                    type: 'popup',
                    modalClass: 'espl-forgot-wrapper',
                    title: '',
                    buttons: [{
                        class: "espl-hidden-button-for-popup",
                        text: 'Back to Login',
                        click: function () {
                            $('.espl-forgot-response-message').hide();
                            this.closeModal();
                        }
                    }]
                });
            },

            sendForm: function () {
                $('.espl-forgot-password-submit').click(function (e) {
                    e.preventDefault();
                    var email = $('.espl-forgot-email').val();
                    var validator = $(".espl-forgot-form").validate();
                    var status = validator.form();
                    if (!status) {
                        return;
                    }

                    if (typeof(postUrl) != "undefined") {
                        var sendUrl = postUrl;
                    } else {
                        console.log("espl post url error.");
                    }

                    $.ajax({
                        type: "POST",
                        data: {email: email},
                        url: sendUrl,
                        showLoader: true
                    }).done(function (response) {
                        if (typeof(response.message != "undefined")) {
                            $('.espl-forgot-response-message').html(response.message);
                            $('.espl-forgot-email').val('');
                            $('.espl-forgot-response-message').show();
                        }
                    });
                });
            }
        });

        return $.mage.esplpopup;

    }
);