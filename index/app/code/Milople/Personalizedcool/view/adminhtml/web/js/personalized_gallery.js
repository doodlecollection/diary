/**
*
* Do not edit or add to this file if you wish to upgrade the module to newer
* versions in the future. If you wish to customize the module for your
* needs please contact us to https://www.milople.com/contact-us.html
*
* @category    Ecommerce
* @package     Milople_Personlized
* @copyright   Copyright (c) 2016 Milople Technologies Pvt. Ltd. All Rights Reserved.
* @url         https://www.milople.com/magento2-extensions/personalizedcool-products-m2.html
*
**/
require(["jquery"], function(jQuery) {
jQuery(window).load(function() {
getAllDesignTemplate = function (file,imageId,productId,baseUrl)
{
       if (imageId === ""){
            alert("Please save product then set design area.");
            return;
        }
   			require(
        [
            'jquery',
            'Magento_Ui/js/modal/modal'
        ],
        function($,modal) {
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                title: 'Draw Area',
        closed: function () {

           $( "aside" ).removeClass( "_show" );
           $("div.modals-overlay").remove();
					 $( "body" ).removeClass( "_has-modal" );
        },
          buttons: [{
          text: $.mage.__('Save'),
          class: '',
          click: function () {
            var formData=new FormData(jQuery('#form-template')[0]);
             x1=$('#x1').val();
             x2=$('#x2').val();
             y1=$('#y1').val();
             y2=$('#y2').val();
             w=$('#w').val();
             h=$('#h').val();
             formData.append("productId",productId );
             formData.append("imageId", imageId);
             formData.append("x1", x1);
             formData.append("x2", x2);
             formData.append("y1", y1);
             formData.append("y2", y2);
             formData.append("w", w);
             formData.append("h", h);
             $.ajax({
                  url: baseUrl+'personalizedcool/index/save',
                  data:formData ,
                  showLoader: true,
                  cache: false,
                  contentType: false,
                  processData: false,
                  type     : 'post',
                }).done(function(data){
                     $( "aside" ).removeClass( "_show" );
                     $("div.modals-overlay").remove();
										 $( "body" ).removeClass( "_has-modal" );
                });     
            }},{
            text: $.mage.__('Delete'),
                    class: '',
                    click: function () {
                      $.ajax({
                      url:  baseUrl+'personalizedcool/index/delete',
                      data:{imageId:imageId,productId:productId} ,
                      showLoader: true,
                      type : 'post',
                    }).done(function(data){
                       $( "aside" ).removeClass( "_show" );
                       $("div.modals-overlay").remove();
											 $( "body" ).removeClass( "_has-modal" );
                    });    
            }},{
            text: $.mage.__('Cancel'),
                    class: '',
                    click: function () {
                      this.closeModal();
											$( "body" ).removeClass( "_has-modal" );
            }}]
        };
         require([
          'jquery',
          'mage/template',
          'jquery/ui',
          'mage/translate'
        ], function($) {
        $.ajax({
          url:  baseUrl+'personalizedcool/index/template',
          data:{fileUrl:file,imageId:imageId,productId:productId} ,
          showLoader: true,
          dataType : 'json',
          type     : 'post',
        }).done(function(data){
          x1=data.x1;
          y1=data.y1;
          x2=data.x2;
          y2=data.y2;
          jQuery('#template-area div').css('z-index', 200);
          jQuery('#jcrop-tracker').css('z-index', 290);
          if(isNaN(data.x1) && isNaN(data.y1) && isNaN(data.x2) && isNaN(data.y2) ){
            x1=0;
            x2=0;
            y1=0;
            y2=0;
          }
          jQuery('.jcrop-holder').remove;
          jQuery('.jcrop-holder img').attr('src',file);
          var popup = modal(options, $('#template-area'));
          $('#template-area').modal('openModal');
          jQuery(function($){
            $('#imageDiv').Jcrop({
              setSelect:[x1,y1,x2,y2],
              onChange:showCoords,
              onSelect:showCoords,
              onRelease:clearCoords,
              onSelect:cropingComplete
            },function(){
              jcrop_api = this;
            });
            function showCoords(c)
            {
            $('#x1').val(c.x);
            $('#y1').val(c.y);
            $('#x2').val(c.x2);
            $('#y2').val(c.y2);
            $('#w').val(c.w);
            $('#h').val(c.h);
            }
            function cropingComplete(c)
            {
              canvas.setHeight(c.h);
              canvas.setWidth(c.w);
              canvas.renderAll();
              jQuery('#template-area div.canvas-container').css('left', c.x);
              jQuery('#template-area div.canvas-container').css('top', c.y);
            }
            function clearCoords()
            {
             $('#coords input').val('');
            }
            jQuery('div#container').css('z-index', 9999); 
          });
          canvas.renderAll();
         });
       });
     });    
	 }	// getAllDesignTemplate
 }); // Window Load. 
}); // Require.