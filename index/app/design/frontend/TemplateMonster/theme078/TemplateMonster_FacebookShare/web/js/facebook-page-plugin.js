/**
 *
 * Copyright © 2015 TemplateMonster. All rights reserved.
 * See COPYING.txt for license details.
 *
 */


define([
    'jquery',
    'mage/template',
    'text!TemplateMonster_FacebookShare/templates/facebook/plugin.html'
],function($,mageTemplate,pluginTemplate) {
'use strict';
    $.widget('tm.facebookPagePlugin',{

        options: {
            pluginPageData: {},
            // Columns selectors
            mainsidebar: '.sidebar.sidebar-main',
            additional: '.sidebar.sidebar-additional',
            contentmain: '.column.main',
            footer: '.footer .order-md-4',
            // Page type class
            pagetype: {
                homepage: 'cms-index-index',
                cms: 'cms-page-view',
                category: 'catalog-category-view',
                product: 'catalog-product-view',
            },
            // Layout page types
            layoutTypes: {
                column1: 'page-layout-1column',
                columns2left: 'page-layout-2columns-left',
                columns2right: 'page-layout-2columns-right',
                columns3: 'page-layout-3columns'
            },
            pageSelectorsMap: {},
            template: pluginTemplate
        },

        _create: function() {
            //Init map array for page type
            this._createHomePageMap();
            this._createCmsMap();
            this._createProductMap();
            this._createCategory();
            //Data from configurations
            var pagesData = this.options.pluginPageData;
            //Check if pages data exists
            if(!pagesData) {return false;}

            //Check page type and page layout that can be  handlered
            var pageObj = this._getCurrentPageType();
            var layoutObj = this._getCurrentPageLayoutType();

            if(pageObj && layoutObj) {
                //Get types by page
                var pageType = pageObj.type;
                var layoutType = layoutObj.type;

                var pageData = pagesData[pageType]
                //check if current page type is disable
                if(!pageData) {
                    return false;
                }

                //Get page part for widget insert
                var insertWidgetPagePart = pageData.position;
                if(!insertWidgetPagePart) {
                    return false;
                }
                //Get layouts for current page type.
                var selectorType = this.options.pageSelectorsMap[pageType];
                //Get selectors by layout type.
                var selectorLayout = selectorType[layoutType];
                //Get selector for insert widget
                var selectorPagePart = selectorLayout[insertWidgetPagePart];

                if(selectorPagePart){
                    pageData.url = pagesData.url;
                    $(selectorPagePart).append(this._facebookPluginCode(pageData));
                    this._initPacebookPlugin();
                }
            }
        },

        _getCurrentPageType: function() {
            var body = $('body');
            var pageType = null;
            $.each(this.options.pagetype,function(index,value){
                if(body.hasClass(value)){
                    pageType = {class : value, type: index};
                }
            });
            return pageType;
        },

        _getCurrentPageLayoutType: function() {
            var body = $('body');
            var pageType = null;
            $.each(this.options.layoutTypes,function(index,value){
                if(body.hasClass(value)){
                    pageType = {class : value, type: index};
                }
            });
            return pageType;
        },

        _initPacebookPlugin: function(){
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        },

        _facebookPluginCode: function(pageData) {
            var source = this.options.template;
            var data = pageData;
            var template = mageTemplate(source);
            return template({
                data: data
            });
        },

        _createHomePageMap: function() {
            this.options.pageSelectorsMap.homepage = this._pageViewMap();
        },

        _createCmsMap: function() {
            this.options.pageSelectorsMap.cms = this._pageViewMap();
        },

        _createProductMap: function() {
            this.options.pageSelectorsMap.product = this._pageViewMap();
        },

        _createCategory: function() {
            this.options.pageSelectorsMap.category = {
                column1: {
                    content: this.options.contentmain,
                    footer: this.options.footer
                },
                columns2left: {
                    content: this.options.contentmain,
                    footer: this.options.footer,
                    left: this.options.additional
                },
                columns2right:  {
                    content: this.options.contentmain,
                    footer: this.options.footer,
                    right: this.options.additional
                },
                columns3: {
                    content: this.options.contentmain,
                    footer: this.options.footer,
                    left: this.options.mainsidebar,
                    right: this.options.additional
                }
            };
        },

        _pageViewMap: function() {
           var selectorsMap =  {
                column1: {
                    content: this.options.contentmain,
                    footer: this.options.footer
                },
                columns2left: {
                    content: this.options.contentmain,
                    footer: this.options.footer,
                    left: this.options.additional
                },
                columns2right:  {
                    content: this.options.contentmain,
                    footer: this.options.footer,
                    right: this.options.additional
                },
                columns3: {
                    content: this.options.contentmain,
                    footer: this.options.footer,
                    right: this.options.additional
                }
            };
           return selectorsMap;
        }
    });

    return $.tm.facebookPagePlugin;
});