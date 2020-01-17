/**
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future. If you wish to customize the module for your
 * needs please contact us to https://www.milople.com/contact-us.html
 *
 * @category    Ecommerce
 * @package     Milople_Personlized
 * @copyright   Copyright (c) 2016 Milople Technologies Pvt. Ltd. All Rights Reserved.
 * @url         https://www.milople.com/magento2-extensions/personalized-products-m2.html
 *
 **/
var side_counter = 0;
var flag_for_text_baground;
var canvas_objects_aspect_ratio = 1;
var test_variable = 45667;
var height_for_restricted = 0;
var width_for_resticted = 0;
var name_number_counter = 1;
var global_canvas_width = 0;
var global_canvas_height = 0;
var state; // current unsaved state
var undo = []; // past states
var redo = [];  // reverted states
window.canvasObjects = new Array(); //javascript array
require(["jquery", "Magento_Catalog/js/price-utils"], function (jQuery, priceUtils) {
	var jQuery = jQuery.noConflict();
	decideWhichBlockToShow = function () {
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		if (isMobile.matches) {
			if (!jQuery('#add_text_button').hasClass("tab-view")) {
				jQuery('.update-text').hide();
				jQuery('#add_text_button').hide();
				jQuery('.text_effect_buttons').hide();
				jQuery('.font_style_popup').hide();
			}
		} else {
			jQuery('#format_text').hide();
		}
	}
	save = function () {
          // clear the redo stack
				  redo = [];
          jQuery('#redo').prop('disabled', true);
					jQuery('#redoImage').prop('disabled', true);
					jQuery('#redoClipart').prop('disabled', true);
          // initial call won't have a state
          if (state) {
            undo.push(state);
            jQuery('#undo').prop('disabled', false);
						jQuery('#undoImage').prop('disabled', false);
						jQuery('#undoClipart').prop('disabled', false);
          }
          state = JSON.stringify(canvas);
  }
	function checkCanvas () {

		if(typeof canvas == 'undefined'){
			setTimeout(checkCanvas, 50);
		 } else {
		 save();
		}
	}
	jQuery(document).ready(function () {
		
		decideWhichBlockToShow();
		checkCanvas();
		jQuery("#imageDiv").click(function () {
			resetDefault();
		});
		if(jQuery(".font_color").length){
            var color = new Piklor(".color-picker", [
              "#ff0000"
            , "#ff6666"
            , "#00ff80"
            , "#0080ff"
            , "#99ccff"
            , "#000000"
            , "#666666"
            , "#ffff00"
            , "#1abc9c"
            , "#2ecc71"
            , "#3498db"
            , "#9b59b6"
            , "#34495e"
            , "#f1c40f"
            , "#e67e22"
            , "#e74c3c"
            , "#ecf0f1"
            , "#95a5a6"
            ], {
                open: ".font_color",
							  closeOnBlur: true
            }) ;
			      
            color.colorChosen(function (col) {
                canvas.getActiveObject().setFill(col);
                canvas.renderAll();
            }); 
        }
      
        if(jQuery(".background_color").length){
          var backgroundcolor = new Piklor(".backgroundcolor-picker", [
              "#ff0000"
            , "#ff6666"
            , "#00ff80"
            , "#0080ff"
            , "#99ccff"
            , "#000000"
            , "#666666"
            , "#ffff00"
            , "#1abc9c"
            , "#2ecc71"
            , "#3498db"
            , "#9b59b6"
            , "#34495e"
            , "#f1c40f"
            , "#e67e22"
            , "#e74c3c"
            , "#ecf0f1"
            , "#95a5a6"
          ], {
              open: ".background_color",
							closeOnBlur: true
          }) ;
          backgroundcolor.colorChosen(function (col) {
              canvas.getActiveObject().setBackgroundColor(col);
              canvas.renderAll();
          });
        }
		jQuery(".designAreasDiv").click(function () {
			var isMobile = window.matchMedia("only screen and (max-width: 760px)");
			if (isMobile.matches && canvas.getActiveObject() == null) {
				resetDefault();
			}
		});
	});

	resetDefault = function () {
		jQuery("#content-upload").hide();
		jQuery("#content-clipart").hide();
		jQuery("#content-text").hide();
		jQuery("#content-template").hide();
		jQuery("#content-name-number").hide();
		jQuery(".label_in_popup").css("display", "inline-block");
		canvas.deactivateAll().renderAll();
		jQuery(".small-content-text").css({
			"display": "none"
		});
		jQuery(".small_border_label").css({
			"display": "block"
		});
		jQuery(".dir_pad").css({
			"display": "none"
		});
		jQuery(".personalized-container").css({
			"display": "block"
		});
		jQuery(".small-directional-pad").css({
			"display": "none"
		});
		jQuery("#content-template").css({
			"display": "none"
		});
		jQuery('.personalizer_popup').css({
			"bottom": "0"
		});
		jQuery(".text_content").css({
			"display": "block"
		});
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		if (isMobile.matches) {
			jQuery('.text_effect_buttons').hide();
			jQuery('.font_style_popup').hide();
		}
		if (jQuery('#format_text').length) document.getElementById("format_text").disabled = true;
		jQuery('#add_text').val('');
		jQuery('button.circle').hide();
		jQuery('label').removeClass('active');
		jQuery('.update-text').hide();
		if (jQuery('#add_text_button').hasClass("tab-view")) {
			jQuery('#add_text_button').show();
		}
	}
	// Tab click on popup
	jQuery(document).on('click', '.label_in_popup', function () {
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		activeBorderColor = jQuery(this).attr('data-border');
		if (isMobile.matches) {
			// Display in popup
			if (jQuery(this).attr('id') == 'tab-name-number') {
				jQuery('.personalizer_popup').css({
					"bottom": "25%"
				});
			} else {
				jQuery('.personalizer_popup').css({
					"bottom": "0"
				});
			}
			if (jQuery('#' + jQuery(this).attr('data-section')).css('display') == 'none') {
				if (jQuery(this).attr('id') == 'tab-name-number') {
					jQuery('section').slideUp();
				} else {
					jQuery('section').slideUp();
					jQuery('button.circle').show();
					jQuery('button.circle').css({
						'border-color': activeBorderColor
					});
				}
				jQuery('.small_border_label').find('.label_in_popup').hide();
				jQuery('label').removeClass('active');
			}
			jQuery(this).toggleClass("active");
			jQuery('#' + jQuery(this).attr('data-section')).slideToggle();
			return;
		}
		if (jQuery('#' + jQuery(this).attr('data-section')).css('display') == 'none') {
			jQuery('section').slideUp();
			jQuery('label').removeClass('active');
		}
		jQuery(this).toggleClass("active");
		jQuery('#' + jQuery(this).attr('data-section')).slideToggle();
	})
	jQuery(document).on('keypress keydown keyup change', '#add_name', function () {
		var firstname = jQuery("input[name^='milople_name']");
		var f_name = document.getElementById('add_name');
		if (!firstname[0].value) {
			f_name.style.borderColor = "red";
		} else {
			f_name.style.borderColor = "green";
		}
		addname();
	});
	jQuery(document).on('keypress keydown keyup change', '#add_numb', function () {
		var firstnumber = jQuery("input[name^='milople_number']");
		var f_numb = document.getElementById('add_numb');
		if (!firstnumber[0].value) {
			f_numb.style.borderColor = "red";
		} else {
			f_numb.style.borderColor = "green";
		}
		addnumber();
	});
	//function for adding name and number field dynamcally
	jQuery('#nameAndNumbertable').on('click', '.remove_row', function () {
		jQuery(this).closest('tr').remove();
		name_number_counter--;
		if (name_number_counter == 0) {
			canvas.getObjects().forEach(function (obj) {
				if (obj.id == 'nameObject') {
					textObj = obj;
					canvas.remove(textObj);
					canvas.renderAll();
					save();
				}
			});
			canvas.getObjects().forEach(function (obj) {
				if (obj.id == 'numberObject') {
					textObj = obj;
					canvas.remove(textObj);
					canvas.renderAll();
					save();
				}
			});
		}
	});
	jQuery('p span.addone').click(function () {
		if (name_number_counter == 0) {
			jQuery('#nameAndNumbertable').append('<tr><td><input type="text" id="add_name" name="milople_name[]"/></td><td><input id="add_numb" type="text" name="milople_number[]"/><td><input type="text" name="milople_size[]"/></td><td><span data-icon="Q" class="remove_row round"></span></td></tr>')
		} else {
			jQuery('#nameAndNumbertable').append('<tr><td><input type="text" name="milople_name[]"/></td><td><input type="text" name="milople_number[]"/><td><input type="text" name="milople_size[]"/></td><td><span data-icon="Q" class="remove_row round"></span></td></tr>')
		}
		name_number_counter++;
	});
	jQuery('p span.close-name').click(function () {
		jQuery('.personalizer_popup').css({
			"bottom": "0"
		});
		resetDefault();
	});
	jQuery("#font_style_effect").click(function () {
		jQuery(".text_effect_buttons").css("display", "none");
		jQuery(".font_style_popup").css("display", "block");
	});
	jQuery('#font_color').click(function () {
		flag_for_text_baground = 0;
	});
	jQuery('#font_bg_color').click(function () {
		flag_for_text_baground = 1;
	});
	//thumb click
	jQuery(".product-thumbs a").on('click', function () {
		var target = jQuery(this).attr('data-main-canvas-id');
		jQuery("#drawingArea-" + target).show().siblings("div.designAreasDiv").hide();
		if (jQuery("#image-template-" + target).length > 0) {
			jQuery("#image-template-" + target).show().siblings("div.imageTemplate").hide();
			jQuery("label#tab-template").show();
			jQuery("#content-template ").hide();
		} else {
			jQuery("#tab1").prop("checked", true);
			jQuery("label#tab-template").hide();
			jQuery("#content-template ").hide();
		}
		canvas = canvasObjects[target];
		canvas.off({
			'object:moving': onSelected,
			'object:selected': onSelected,
			'object:modified': onModified,
			'selection:created': onSelected,
			'selection:cleared': onDeSelected
		});
		canvas.on({
			'object:moving': onSelected,
			'object:selected': onSelected,
			'object:modified': onModified,
			'selection:created': onSelected,
			'selection:cleared': onDeSelected
		});
		if (jQuery('#imageDiv').length) {
			jQuery('#imageDiv').attr("src", jQuery(this).attr("data-main-image-src"));
		} else {
			canvas.setBackgroundImage(jQuery(this).attr("data-main-image-src"), canvas.renderAll.bind(canvas));
		}
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		if (isMobile.matches) {
			jQuery('#add_text_button').hide();
		} else {
			change_add_update_buttons('', 'inline-block', 'none', 'silver');
		}
	});
	//Set the radius ,spacing and effect for text object
	jQuery('#radius, #spacing, #effect').change(function () {
		if (canvas.getActiveObject()) {
			if (jQuery(this).attr('id') == 'effect') {
				if (canvas.getActiveObject().type == 'text' && jQuery(this).val() != 'STRAIGHT') {
					convert_text_object('curvedText');
				} else if (canvas.getActiveObject().type == 'curvedText' && jQuery(this).val() == 'STRAIGHT') {
					convert_text_object('text');
				}
			}
			canvas.getActiveObject().set(jQuery(this).attr('id'), jQuery(this).val());
			canvas.renderAll();
			save();
		}
		if (jQuery(this).attr('id') == 'effect') {
			if (jQuery(this).val() == 'curved' || jQuery(this).val() == 'arc') {
				jQuery('.radius-effect, .spacing-effect, .reverse-effect').css('display', 'block');
			} else {
				jQuery('.radius-effect, .spacing-effect, .reverse-effect').css('display', 'none');
			}
			if (jQuery(this).val() == 'STRAIGHT') {
				jQuery('#font_inc').css('display', 'inline-block');
				jQuery('#font_dec').css('display', 'inline-block');
			} else {
				jQuery('#font_inc').css('display', 'none');
				jQuery('#font_dec').css('display', 'none');
			}
		}
	});
	//Reserve active object
	jQuery('#reverse').click(function () {
		if (canvas.getActiveObject()) {
			canvas.getActiveObject().set('reverse', jQuery(this).is(':checked'));
			canvas.renderAll();
			save();
		}
	});
	// Adding text on image
	addtext = function (mobile) {
		var textVar = document.getElementById('add_text');
		var imageObjects = parseInt(document.getElementById('iObject').value);
		var textObjects = parseInt(document.getElementById('tObject').value);
		textObjects++;
		changePrice(textObjects, imageObjects);
		var textFont = jQuery("#font_selection option:selected").val();
		if (textFont == '') {
			textFont = 'Calibri';
		}
		if (textVar.value) {
			var center = canvas.getCenter();
			var textObj = new fabric.Text(textVar.value, {
				fontSize: 18,
				textAlign: "left",
				originX: 'center',
				originY: 'center',
				fontFamily: textFont,
				top: center.top,
				left: center.left,
			});
			if (mobile == undefined) {
				textVar.value = "";
			}
			canvas.add(textObj);
			canvas.setActiveObject(textObj);
			canvas.renderAll();
			save();
			if (jQuery('#effect').val() == 'curved' || jQuery('#effect').val() == 'arc') {
				convert_text_object('curvedText');
			}
			change_add_update_buttons(textVar.value, 'none', 'inline-block', 'silver');
		} else {
			textVar.style.borderColor = "red";
		}
	}
	//Add Name function
	addname = function () {
		var mode = "insert";
		textObj = '';
		if (jQuery('#format_text').length) document.getElementById("format_text").disabled = false;
		var textVar = document.getElementById('add_name');
		var imageObjects = parseInt(document.getElementById('iObject').value);
		var textObjects = parseInt(document.getElementById('tObject').value);
		canvas.getObjects().forEach(function (obj) {
			if (obj.id == 'nameObject') {
				mode = "update";
				textObj = obj;
			}
		})
		changePrice(textObjects, imageObjects);
		if (mode == 'insert') {
			if (textVar.value) {
				var center = canvas.getCenter();
				var textFont = jQuery("#font_selection option:selected").val();
				if (textFont == '') {
					textFont = 'Calibri';
				}
				var textObj = new fabric.Text(textVar.value, {
					fontSize: 24,
					textAlign: "left",
					originX: 'center',
					originY: 'center',
					fontFamily: textFont,
					top: 50,
					left: center.left,
				});
				textObj.id = 'nameObject';
				canvas.add(textObj);
				canvas.setActiveObject(textObj);
				canvas.renderAll();
				if (jQuery('#effect').val() == 'curved' || jQuery('#effect').val() == 'arc') {
					convert_text_object('curvedText');
				}
				change_add_update_buttons(textVar.value, 'none', 'inline-block', 'silver');
				textVar.style.borderColor = "green";
			} else {
				textVar.style.borderColor = "red";
			}
		} else { // update
			if (textVar.value != '') {
				textObj.setText(textVar.value);
				canvas.setActiveObject(textObj);
				canvas.renderAll();
			} else {
				canvas.remove(textObj);
				canvas.renderAll();
				save();
			}
		}
	}
	//Add Number function
	addnumber = function () {
		var mode = "insert";
		textObj = '';
		if (jQuery('#format_text').length) document.getElementById("format_text").disabled = false;
		var textVar = document.getElementById('add_numb');
		var imageObjects = parseInt(document.getElementById('iObject').value);
		var textObjects = parseInt(document.getElementById('tObject').value);
		canvas.getObjects().forEach(function (obj) {
			if (obj.id == 'numberObject') {
				mode = "update";
				textObj = obj;
			}
		})
		changePrice(textObjects, imageObjects);
		if (mode == 'insert') {
			if (textVar.value) {
				var center = canvas.getCenter();
				var textObj = new fabric.Text(textVar.value, {
					fontSize: 24,
					textAlign: "left",
					originX: 'center',
					originY: 'center',
					fontFamily: jQuery("#font_selection option:selected").val(),
					top: 80,
					left: center.left,
				});
				//textVar.value = "";
				textObj.id = 'numberObject';
				canvas.add(textObj);
				canvas.setActiveObject(textObj);
				canvas.renderAll();
				save();
				if (jQuery('#effect').val() == 'curved' || jQuery('#effect').val() == 'arc') {
					convert_text_object('curvedText');
				}
				change_add_update_buttons(textVar.value, 'none', 'inline-block', 'silver');
				textVar.style.borderColor = "green";
			} else {
				textVar.style.borderColor = "red";
			}
		} else {
			if (textVar.value != '') {
				textObj.setText(textVar.value);
				canvas.setActiveObject(textObj);
				canvas.renderAll();
			} else {
				canvas.remove(textObj);
				canvas.renderAll();
				save();
			}
		}
	}
	//Covert the normal text object to curved text object
	convert_text_object = function (to_object_type) {
		var props = {};
		var obj = canvas.getActiveObject();
		if (obj) {
			props = obj.toObject();
			delete props['type'];
			props['textAlign'] = obj.getTextAlign();
			props['originX'] = obj.getOriginX();
			props['originY'] = obj.getOriginY();
			props['fontFamily'] = obj.getFontFamily();
			props['fill'] = obj.getFill();
			props['backgroundColor'] = obj.getBackgroundColor();
			props['top'] = obj.getTop();
			props['left'] = obj.getLeft();
			props['objectType'] = obj.objectType;
			if (obj.type == 'curvedText' && to_object_type == 'text') {
				var convertedText = new fabric.Text(obj.getText(), props);
			} else if (obj.type == 'text' && to_object_type == 'curvedText') {
				props['effect'] = jQuery('#effect').val();
				props['radius'] = jQuery('#radius').val();
				props['spacing'] = jQuery('#spacing').val();
				props['reverse'] = jQuery('#reverse').is(':checked');
				var convertedText = new fabric.CurvedText(obj.getText(), props);
			}
			canvas.remove(obj);
			canvas.add(convertedText).renderAll();
			save();
			canvas.setActiveObject(canvas.item(canvas.getObjects().length - 1));
		}
	}
	// Removing image on product image
	removeImageFromServer = function (imageId) {
		var baseUrl = jQuery("#baseURL").val();
		jQuery.ajax({
			type: 'POST',
			url: baseUrl + 'personalizedcool/index/deleteraw',
			data: {
				'imageId': imageId
			},
			dataType: 'text',
			showLoader: true,
			success: function (result) {
				var rawImageValue = jQuery("#raw_images").val();
				rawImageid = result;
				if (rawImageValue == imageId) {
					rawImageValue = 0;
				} else {
					var rawImageArray = rawImageValue.split(",");
					var index = rawImageArray.indexOf(imageId);
					if (index > -1) {
						rawImageArray.splice(index, 1);
						rawImageArray.toString();
						rawImageValue = rawImageArray;
					}
				}
				jQuery("#raw_images").val(rawImageValue);
			},
			error: function (xhr, status) {}
		});
	}
	// Adding image on product image
	handleImage = function (e) {
		var reader = new FileReader();
		var baseUrl = jQuery("#baseURL").val();
		var file = this.files[0];
		var fd = new FormData();
		fd.append("afile", file);
		isRawEnabled = parseInt(document.getElementById('is_raw').value);
		var rawImageid;
		jQuery.ajax({
			type: 'POST',
			url: baseUrl + 'personalizedcool/index/saveraw',
			data: fd,
			dataType: 'text',
			cache: false,
			showLoader: true,
			contentType: false,
			processData: false,
			beforeSend: function (xhr, opts) {
				if (isRawEnabled == 0) //just an example
				{
					imageUpload(reader, e, 0);
					xhr.abort();
				}
			},
			success: function (result) {
				rawImageid = result;
				var rawImageValue = jQuery("#raw_images").val();
				if (rawImageValue == 0) {
					rawImageValue = result;
				} else {
					rawImageValue = rawImageValue + ',' + result;
				}
				jQuery("#raw_images").val(rawImageValue);
				imageUpload(reader, e, rawImageid);
			} //success
		}); //ajax call
	}
	// Upload image event handler
	var imageLoader = document.getElementById('uploadimage');
	if (typeof (imageLoader) != 'undefined' && imageLoader != null) {
		imageLoader.addEventListener('change', handleImage, false);
	}
	//Upload the image to server based on the setting at backend.
	function imageUpload(reader, e, rawImageid) {
		var textObjects = parseInt(document.getElementById('tObject').value);
		var imageObjects = parseInt(document.getElementById('iObject').value);
		imageObjects++;
		changePrice(textObjects, imageObjects);
		var center = canvas.getCenter();
		reader.onload = function (event) {
			var img = new Image();
			img.onload = function () {
				var imgInstance = new fabric.Image(img, {
					scaleX: 0.5,
					scaleY: 0.5,
					originX: 'center',
					originY: 'center',
					top: center.top,
					left: center.left,
					objectType: 'image'
				});
				imgInstance.RawImageId = rawImageid;
				isFitEnable = parseInt(document.getElementById('image_fit').value);
				if (isFitEnable == 1) {
					imgInstance.set({
						top: canvas.height / 2,
						left: canvas.width / 2,
						scaleY: (canvas.height / imgInstance.height) - 0.03,
						scaleX: (canvas.width / imgInstance.width) - 0.03
					});
				}
				canvas.add(imgInstance);
				canvas.setActiveObject(canvas.item(canvas.getObjects().length - 1));
				canvas.renderAll();
				save();
			}
			img.src = event.target.result;
		}
		reader.readAsDataURL(e.target.files[0]);
	}
	// Apply bold effect to text object
	jQuery("#text-bold").click(function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type == 'text') {
			activeObject.fontWeight = (activeObject.fontWeight == 'bold' ? '' : 'bold');
			canvas.renderAll();
			save();
		}
	});
	// Apply Italic effect to text object
	jQuery("#text-italic").click(function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'text') {
			activeObject.fontStyle = (activeObject.fontStyle == 'italic' ? '' : 'italic');
			canvas.renderAll();
			save();
		}
	});
	// Apply strike effect to text object
	jQuery("#text-strike").click(function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'text') {
			activeObject.textDecoration = (activeObject.textDecoration == 'line-through' ? '' : 'line-through');
			canvas.renderAll();
			save();
		}
	});
	// Apply underline effect to text object
	jQuery("#text-underline").click(function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'text') {
			activeObject.textDecoration = (activeObject.textDecoration == 'underline' ? '' : 'underline');
			canvas.renderAll();
			save();
		}
	});
	// Apply align left to text object
	jQuery("#text-left").click(function () {
		canvas.getActiveObject().setLeft(0);
		canvas.getActiveObject().setCoords();
		canvas.renderAll();
		save();
	});
	// Apply align center to text object
	jQuery("#text-center").click(function () {
		canvas.getActiveObject().setLeft(parseInt((canvas.width - canvas.getActiveObject().width) / 2));
		canvas.getActiveObject().setCoords();
		canvas.renderAll();
		save();
	});
	// Apply align right to text object
	jQuery("#text-right").click(function () {
		canvas.getActiveObject().setLeft(parseInt(canvas.getWidth() - canvas.getActiveObject().getWidth()));
		canvas.getActiveObject().setCoords();
		canvas.renderAll();
		save();
	});
	// Apply font family to text object on change event of font family dropdown
	jQuery("#font-family").change(function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'text') {
			activeObject.fontFamily = this.value;
			canvas.renderAll();
			save();
		}
	});
	// Increase the font size of text
	jQuery("#font_inc").on('click', function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'text') {
			fontSize = activeObject.getFontSize();
			fontSize = parseInt(fontSize);
			fontSize += 2;
			if (fontSize > 10) {
				activeObject.setFontSize(fontSize);
			}
			canvas.renderAll();
			save();
		}
	});
	// DeIncrease the font size of text
	jQuery("#font_dec").on('click', function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && activeObject.type === 'text') {
			fontSize = activeObject.getFontSize();
			fontSize = parseInt(fontSize);
			fontSize -= 2;
			if (fontSize > 10) {
				activeObject.setFontSize(fontSize);
			}
			canvas.renderAll();
			save();
		}
	});
	updateFontColor = function (picker) {
		var activeObject = canvas.getActiveObject();
		if (activeObject && (activeObject.type === 'text' || activeObject.type === 'curvedText')) {
			var hex = document.getElementById('valueInput').value;
			jQuery('span#font_preview').css("border-color", "#" + hex);
			activeObject.setFill(picker.toHEXString());
			canvas.renderAll();
			save();
		}
	}
	updateBackround = function (picker) {
		var activeObject = canvas.getActiveObject();
		if (activeObject && (activeObject.type === 'text' || activeObject.type === 'curvedText')) {
			var hex = document.getElementById('valueSpan').value;
			jQuery('#font_bg_color').css("background-color", "#" + hex);
			activeObject.setBackgroundColor(picker.toHEXString());
			canvas.renderAll();
			save();
		}
	}
	// Handling the delete key event for deleting object
	window.onkeydown = function (e) {
		switch (e.keyCode) {
		case 46: // delete
			var activeObject = canvas.getActiveObject();
			if (activeObject && activeObject.type === 'text') {
				var textObjects = parseInt(document.getElementById('tObject').value);
				var imageObjects = parseInt(document.getElementById('iObject').value);
				textObjects--;
				changePrice(textObjects, imageObjects);
			} else if (activeObject) {
				isRawEnabled = parseInt(document.getElementById('is_raw').value);
				var rawImageValue = jQuery("#raw_images").val();
				if (activeObject.get('type') != "text" && isRawEnabled == 1 && rawImageValue != 0) {
					removeImageFromServer(activeObject.RawImageId);
				}
				var textObjects = parseInt(document.getElementById('tObject').value);
				var imageObjects = parseInt(document.getElementById('iObject').value);
				imageObjects--;
				changePrice(textObjects, imageObjects);
			}
			canvas.remove(activeObject);
			break;
		}
		if (jQuery('#format_text').length) document.getElementById("format_text").disabled = true;
		jQuery(".small_border_label").css({
			"display": "block"
		});
		jQuery("#small-edit-element").css({
			"display": "none"
		});
	}
	jQuery('select#font_selection').on('change', function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject && (activeObject.type === 'text' || activeObject.type === 'curvedText')) {
			activeObject.setFontFamily(this.value);
			canvas.renderAll();
			save();
		}
	});

	function changePrice(textObjects = 0, imageObjects = 0) {
		var pricePerText = 0;
		var pricePerImage = 0;
		var textVar = document.getElementById('add_text');
		if (textVar) {
			pricePerText = textVar.getAttribute('data-price-per-text');
			//alert("pricePerText = " + pricePerText);
		}
		else {
			pricePerText = 0;
		}

		var imageUploader = document.getElementById('uploadimage');
		//alert(imageUploader);
		if (imageUploader) {
			pricePerImage = imageUploader.getAttribute('data-price-per-image');
			//alert("pricePerImage = " + pricePerImage);
		} else {
			pricePerImage = 0;
		}
		document.getElementById('tObject').value = textObjects;
		document.getElementById('iObject').value = imageObjects;
		var productPrice = parseFloat(jQuery('span.special-price > span.price-final_price > span.price-wrapper').attr('data-price-amount'));
		var finalPrice = productPrice + (pricePerText * textObjects) + (pricePerImage * imageObjects);
		formatedPrice = priceUtils.formatPrice(parseFloat(finalPrice));
		jQuery('span.special-price > span.price-final_price > span.price-wrapper > span.price').text(formatedPrice);
	}

	moveCenter = function () {
		var obj = canvas.getActiveObject();
		obj.center();
		obj.setCoords();
		canvas.renderAll();
		save();
	}
	moveLeft = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldLeft = parseInt(activeObject.getLeft(), 10);
			oldLeft -= 10;
			activeObject.setLeft(oldLeft).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveRight = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldRight = parseInt(activeObject.getLeft(), 10);
			oldRight += 10;
			activeObject.setLeft(oldRight).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveTop = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldTop = parseInt(activeObject.getTop(), 10);
			oldTop -= 10;
			activeObject.setTop(oldTop).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveTopLeft = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldTop = parseInt(activeObject.getTop(), 10);
			oldTop -= 10;
			oldLeft = parseInt(activeObject.getLeft(), 10);
			oldLeft -= 10;
			activeObject.setLeft(oldLeft).setCoords();
			activeObject.setTop(oldTop).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveTopRight = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldTop = parseInt(activeObject.getTop(), 10);
			oldTop -= 10;
			oldRight = parseInt(activeObject.getLeft(), 10);
			oldRight += 10;
			activeObject.setLeft(oldRight).setCoords();
			activeObject.setTop(oldTop).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveBottomLeft = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldBottom = parseInt(activeObject.getTop(), 10);
			oldBottom += 10;
			oldLeft = parseInt(activeObject.getLeft(), 10);
			oldLeft -= 10;
			activeObject.setTop(oldBottom).setCoords();
			activeObject.setLeft(oldLeft).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveBottomRight = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldBottom = parseInt(activeObject.getTop(), 10);
			oldBottom += 10;
			oldLeft = parseInt(activeObject.getLeft(), 10);
			oldLeft += 10;
			activeObject.setTop(oldBottom).setCoords();
			activeObject.setLeft(oldLeft).setCoords();
			canvas.renderAll();
			save();
		}
	}
	moveBottom = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldBottom = parseInt(activeObject.getTop(), 10);
			oldBottom += 10;
			activeObject.setTop(oldBottom).setCoords();
			canvas.renderAll();
			save();
		}
	}
	rotateClock = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldAngle = parseInt(activeObject.getAngle(), 10);
			oldAngle += 10;
			activeObject.setAngle(oldAngle).setCoords();
			canvas.renderAll();
			save();
		}
	}
	rotateAntiClock = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			oldAngle = parseInt(activeObject.getAngle(), 10);
			oldAngle -= 10;
			activeObject.setAngle(oldAngle).setCoords();
			canvas.renderAll();
			save();
		}
	}
	jQuery('#undo').click(function() {
            replay(undo, redo, '#redo', this);
   });
  jQuery('#redo').click(function() {
            replay(redo, undo, '#undo', this);

  });
  jQuery('#undoImage').click(function() {
            replay(undo, redo, '#redoImage', this);
						var textObjects = parseInt(document.getElementById('tObject').value);
            var imageObjects = parseInt(document.getElementById('iObject').value);
            textObjects--;
            changePrice(textObjects, imageObjects);
  });
  jQuery('#redoImage').click(function() {
            replay(redo, undo, '#undoImage', this);
						var textObjects = parseInt(document.getElementById('tObject').value);
            var imageObjects = parseInt(document.getElementById('iObject').value);
            textObjects++;
            changePrice(textObjects, imageObjects);
  });
	jQuery('#undoClipart').click(function() {
            replay(undo, redo, '#redoClipart', this);
   });
  jQuery('#redoClipart').click(function() {
            replay(redo, undo, '#undoClipart', this);
  });

	function replay(playStack, saveStack, buttonsOn, buttonsOff) {
         	saveStack.push(state);
          state = playStack.pop();
          var on = jQuery(buttonsOn);
          var off = jQuery(buttonsOff);
          // turn both buttons off for the moment to prevent rapid clicking
          on.prop('disabled', true);
          off.prop('disabled', true);
          canvas.clear();
					canvas.loadFromJSON(state, function() {
							 //alert(state);
							 canvas.renderAll();
					     // now turn the buttons back on if applicable
					     on.prop('disabled', false);
					     if (playStack.length) {
					         off.prop('disabled', false);
					     }
					});
					var textObjects=0;
					var imageObjects=0;
					var counterz = (state.match(/image/g) || []).length;
					//alert(counterz);
					if(counterz>0){
						imageObjects++;
					}
					canvas.getObjects().forEach(function (obj) {
					if(obj.type=='text'){
							textObjects++;
						}
 					})
          changePrice(textObjects, (counterz/2));
  }
	deleteObject = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject.id != 'nameObject' && activeObject.id != 'numberObject') {
			if (activeObject && activeObject.type === 'text') {
				var textObjects = parseInt(document.getElementById('tObject').value);
				var imageObjects = parseInt(document.getElementById('iObject').value);
				textObjects--;
				changePrice(textObjects, imageObjects);
			}
			else if (activeObject) {
				isRawEnabled = parseInt(document.getElementById('is_raw').value);
				if (activeObject.get('type') != "text" && isRawEnabled == 1) {
					removeImageFromServer(activeObject.RawImageId);
				}
				var textObjects = parseInt(document.getElementById('tObject').value);
				var imageObjects = parseInt(document.getElementById('iObject').value);
				imageObjects--;
				changePrice(textObjects, imageObjects);
			}
		}
		if (activeObject.id == 'nameObject' || activeObject.id == 'numberObject') {
			jQuery('#add_name').val('');
			jQuery('#add_numb').val('');
			jQuery('#add_size').val('');
		}
		canvas.remove(activeObject);
		if (jQuery('#format_text').length) document.getElementById("format_text").disabled = true;
		resetDefault();
	}
	jQuery(document).on('click', '.clipart-image', function () {
		var textObjects = parseInt(document.getElementById('tObject').value);
		var imageObjects = parseInt(document.getElementById('iObject').value);
		imageObjects++;
		changePrice(textObjects, imageObjects);
		var imageName = jQuery(this).attr("src")
		fabric.Image.fromURL(imageName, function (img) {
			var center = canvas.getCenter();
			var oImg = img.set({
				top: center.top,
				left: center.left,
				originX: 'center',
				originY: 'center'
			}).scale(0.6);
			isFitEnable = parseInt(document.getElementById('image_fit').value);
			if (isFitEnable == 1) {
				oImg.set({
					top: canvas.height / 2,
					left: canvas.width / 2,
					scaleY: (canvas.height / oImg.height) - 0.03,
					scaleX: (canvas.width / oImg.width) - 0.03
				});
			}
			canvas.add(oImg);
			canvas.renderAll();
			save();
		}, {
			crossOrigin: 'anonymous'
		});
	});
	filterClipart = function () {
		var clipart = document.getElementById('clipart_categories');
		var baseURL = document.getElementById('baseURL').value;
		value = clipart.options[clipart.selectedIndex].value
		if (jQuery('clipart-images-loader')) {
			var clip_process_loader = jQuery('#clipart-images-loader');
			jQuery('#clipart-images-loader').css("display", "block");
			jQuery('#clipart_images_container').html(jQuery('#clipart-images-loader').html());
		}
		jQuery.ajax({
			url: baseURL + 'personalizedcool/index/clipart/',
			data: {
				clipart_cat_id: value
			},
			type: 'POST',
			beforeSend: function () {},
			success: function (result) {
				jQuery('#clipart_images_container').html(result.image);
				clip_process_loader.css("display", "none");
				jQuery('#clipart_images_container').append(clip_process_loader);
			}
		});
	} //  Clip Art
	var clipart = document.getElementById('clipart_categories');
	if (typeof (clipart) != 'undefined' && clipart != null) {
		clipart.addEventListener('change', filterClipart, false);
	}
	bringToFront = function () {
		var activeObject = canvas.getActiveObject(),
			activeGroup = canvas.getActiveGroup();
		if (activeObject) {
			activeObject.bringToFront();
		} else if (activeGroup) {
			var objectsInGroup = activeGroup.getObjects();
			canvas.discardActiveGroup();
			objectsInGroup.forEach(function (object) {
				object.bringToFront();
			});
		}
	};
	cloneObject = function () {
		var activeObject = canvas.getActiveObject();
		if (activeObject) {
			var imageObjects = parseInt(document.getElementById('iObject').value);
			var textObjects = parseInt(document.getElementById('tObject').value);
			object = fabric.util.object.clone(activeObject);
			object.set("top", object.top + 5);
			object.set("left", object.left + 5);
			canvas.add(object);
			if (activeObject.get('type') == 'curvedText' || activeObject.get('type') == 'text') {
				textObjects++;
				changePrice(textObjects, imageObjects);
			} else {
				imageObjects++;
				changePrice(textObjects, imageObjects);
			}
		}
	};
	sendToBack = function () {
		var activeObject = canvas.getActiveObject(),
			activeGroup = canvas.getActiveGroup();
		if (activeObject) {
			activeObject.sendToBack();
		} else if (activeGroup) {
			var objectsInGroup = activeGroup.getObjects();
			canvas.discardActiveGroup();
			objectsInGroup.forEach(function (object) {
				object.sendToBack();
			});
		}
		canvas.renderAll();
		save();
	};
	flipHorizontal = function () {
		var activeObject = canvas.getActiveObject();
		if (!activeObject) {
			return;
		}
		var flip = false;
		var originalFlipX = activeObject.flipX;
		var originalFlipY = activeObject.flipY;
		if (activeObject.flipX == false) {
			flip = true;
		} else {
			flip = false;
		}
		activeObject.set('flipX', flip);
		canvas.renderAll();
		save();
	};
	flipVertical = function () {
		var activeObject = canvas.getActiveObject();
		if (!activeObject) {
			return;
		}
		var flip = false;
		var originalFlipX = activeObject.flipX;
		var originalFlipY = activeObject.flipY;
		if (activeObject.flipY == false) {
			flip = true;
		} else {
			flip = false;
		}
		activeObject.set('flipY', flip);
		canvas.renderAll();
		save();
	};
	// Template Settings
	jQuery.event.special.inputchange = {
		setup: function () {
			var self = this,
				val;
			jQuery.data(this, 'timer', window.setInterval(function () {
				val = self.value;
				if (jQuery.data(self, 'cache') != val) {
					jQuery.data(self, 'cache', val);
					jQuery(self).trigger('inputchange');
				}
			}, 20));
		},
		teardown: function () {
			window.clearInterval(jQuery.data(this, 'timer'));
		},
		add: function () {
			jQuery.data(this, 'cache', this.value);
		}
	};
	//Event for template on product page.
	jQuery(document).on('blur', 'input.template-text', function () {
		var id = jQuery(this).attr('id');
		var characterId = jQuery("#" + id).attr('data-id');
		jQuery('#character-left' + characterId).removeClass('data-validation');
		jQuery('#' + id).removeClass('data-validation-textbox');
	});
	//Event for template on product page.
	jQuery(document).on('keypress keyup change blur', 'input.template-text', function () {
		var mode = "insert";
		var textObj;
		var id = this.id;
		var max = jQuery("#" + id).attr('data-char');
		var characterId = jQuery("#" + id).attr('data-id');
		var len = (this.value).length;
		var char = max - len;
		var family = jQuery("#" + id).attr('data-family');
		var size = jQuery("#" + id).attr('data-size');
		var top = jQuery("#" + id).attr('data-top');
		var left = jQuery("#" + id).attr('data-left');
		var angle = jQuery("#" + id).attr('data-angle');
		var effect = jQuery("#" + id).attr('data-effect');
		var radius = jQuery("#" + id).attr('data-radius');
		var spacing = jQuery("#" + id).attr('data-spacing');
		if (len < max) {
			jQuery('#character-left' + characterId).text('(' + char + ' characters left)');
			jQuery('#character-left' + characterId).removeClass('data-validation');
			jQuery('#' + id).removeClass('data-validation-textbox');
		} else {
			jQuery('#character-left' + characterId).html('(' + char + ' characters left)');
			jQuery('#character-left' + characterId).addClass('data-validation');
			jQuery('#' + id).addClass('data-validation-textbox');
		}
		canvas.getObjects().forEach(function (obj) {
			if (obj.id == id) {
				mode = "update";
				textObj = obj;
			}
		})
		if (mode == "insert" && this.value != '') {
			textObj = new fabric.Text(this.value, {
				fontSize: size,
				textAlign: "left",
				originX: 'center',
				originY: 'center',
				fontFamily: family,
				top: top,
				left: left,
				angle: angle,
				lockMovementX: true,
				lockMovementY: true,
				lockScalingX: true,
				lockScalingY: true,
				lockUniScaling: true,
				lockRotation: true,
				hasControls: false,
				hasBorders: false
			});
			if (effect != 'STRAIGHT') {
				var props = {};
				to_object_type = effect;
				var obj = textObj;
				if (obj) {
					props = obj.toObject();
					delete props['type'];
					props['textAlign'] = obj.getTextAlign();
					props['originX'] = obj.getOriginX();
					props['originY'] = obj.getOriginY();
					props['fontFamily'] = obj.getFontFamily();
					props['fill'] = obj.getFill();
					props['backgroundColor'] = obj.getBackgroundColor();
					props['top'] = obj.getTop();
					props['left'] = obj.getLeft();
					props['objectType'] = obj.objectType;
					props['lockMovementX'] = obj.lockMovementX;
					props['lockMovementY'] = obj.lockMovementY;
					props['lockScalingX'] = obj.lockScalingX;
					props['lockScalingY'] = obj.lockScalingY;
					props['lockRotation'] = obj.lockRotation;
					props['hasControls'] = obj.hasControls;
					props['hasBorders'] = obj.hasBorders;
					if (obj.type == 'curvedText' && to_object_type == 'text') {
						var convertedText = new fabric.Text(obj.getText(), props);
					} else if (obj.type == 'text' && to_object_type == 'curved') {
						props['effect'] = effect;
						props['radius'] = radius;
						props['spacing'] = spacing;
						var convertedText = new fabric.CurvedText(obj.getText(), props);
					}
					canvas.add(convertedText).renderAll();
					convertedText.id = id;
					canvas.setActiveObject(canvas.item(canvas.getObjects().length - 1));
				}
			} else {
				textObj.id = id;
				canvas.add(textObj);
			}
		} else if (mode == 'update') {
			if (this.value == '') {
				canvas.remove(textObj);
			} else {
				textObj.setText(this.value);
			}
		}
		canvas.renderAll();
	});
	onSelected = function (e) {
		proper_object_selected = true;
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		if (e.target.get('type') == 'curvedText' || e.target.get('type') == 'text') {
			if (isMobile.matches) {
				if (jQuery('#format_text').length) document.getElementById("format_text").disabled = false;
				change_add_update_buttons(e.target.get('text'), 'none', 'none', 'silver');
			} else {
				// Added this condition due to control
				if (canvas.getActiveObject() != null) {
					select_text_styles();
					change_add_update_buttons(e.target.get('text'), 'none', 'inline-block', 'silver');
				}
			}
		}
		if (isMobile.matches && canvas.getActiveObject() != null) {
			jQuery("#small-edit-element").css({
				"display": "block"
			});
		}
		if ((!jQuery('#add_text_button').hasClass("tab-view"))) {
			if (jQuery('#format_text').length) document.getElementById("format_text").disabled = false;
		}
	}
	//On selection of object set value to text effect options.
	select_text_styles = function () {
		if (jQuery('#text-bold').length) {
			if (canvas.getActiveObject().getFontWeight() == 'bold') {
				document.getElementById('text-bold').style.background = '#e6e4df';
			} else {
				document.getElementById('text-bold').style.background = '#fff';
			}
			if (canvas.getActiveObject().getFontStyle() == 'italic') {
				document.getElementById('text-italic').style.background = '#e6e4df';
			} else {
				document.getElementById('text-italic').style.background = '#fff';
			}
			if (canvas.getActiveObject().getTextDecoration() == 'underline') {
				document.getElementById('text-underline').style.background = '#e6e4df';
			} else {
				document.getElementById('text-underline').style.background = '#fff';
			}
			jQuery('#font_bg_color').css('background-color', canvas.getActiveObject().getBackgroundColor());
			jQuery('#font_selection').val(canvas.getActiveObject().getFontFamily());
			if (canvas.getActiveObject().type == 'curvedText') {
				jQuery('#effect').val(canvas.getActiveObject().getEffect());
				if (canvas.getActiveObject().getEffect() == 'curved' || canvas.getActiveObject().getEffect() == 'arc') {
					jQuery('.radius-effect, .spacing-effect, .reverse-effect').css('display', 'block');
					jQuery('#radius').val(canvas.getActiveObject().getRadius());
					jQuery('#spacing').val(canvas.getActiveObject().getSpacing());
					jQuery('#reverse').prop('checked', canvas.getActiveObject().getReverse());
				} else {
					jQuery('.radius-effect, .spacing-effect, .reverse-effect').css('display', 'none');
				}
			} else {
				jQuery('#effect').val('STRAIGHT');
				jQuery('.radius-effect, .spacing-effect, .reverse-effect').css('display', 'none');
			}
		}
	}
	//Reset button and text effect style
	onDeSelected = function () {
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		if (!isMobile.matches) {
			change_add_update_buttons('', 'none', 'inline-block', 'silver');
			deselect_text_styles();
		}
		else {
			change_add_update_buttons('', 'none', 'inline-block', 'silver');
			deselect_text_styles();
		}
	}
	//set value on modification of object
	onModified = function (e) {
		save();
		if (e.target.get('type') == 'curvedText' || e.target.get('type') == 'text') {
			e.target.setFontSize(parseFloat(e.target.fontSize * e.target.scaleX).toFixed(2));
			e.target.setScaleX(1);
			e.target.setScaleY(1);
			e.target.setCoords();
			canvas.renderAll();
		}
	}

	// Update the text object text
	updatetext = function () {
		if (jQuery('#add_text').val()) {
			if (canvas.getActiveObject().type == 'curvedText' || canvas.getActiveObject().type == 'text') {
				canvas.getActiveObject().setText(jQuery('#add_text').val());
				canvas.renderAll();
				save();
				jQuery('#add_text').css('border-color', 'silver');
			}
		} else {
			jQuery('#add_text').css('border-color', 'red');
		}
	}
	//Update the name
	updatename = function () {
		if (jQuery('#add_name').val()) {
			if (canvas.getActiveObject().type == 'curvedText' || canvas.getActiveObject().type == 'text') {
				canvas.getActiveObject().setText(jQuery('#add_name').val());
				canvas.renderAll();
				save();
				jQuery('#add_name').css('border-color', 'silver');
			}
		} else {
			jQuery('#add_name').css('border-color', 'red');
		}
	}
	// Change button text from add to update
	change_add_update_buttons = function (text_value, add_button_display, update_button_display, border_color) {
		jQuery('#add_text').val(text_value);
		jQuery('#add_text_button').css('display', add_button_display);
		jQuery('.update-text').css('display', update_button_display);
		jQuery('#add_text').css('border-color', border_color);
	}
	// Reset Text effect
	deselect_text_styles = function () {
		if (jQuery('#text-bold').length) {
			document.getElementById('text-bold').style.background = '#fff';
			document.getElementById('text-italic').style.background = '#fff';
			document.getElementById('text-underline').style.background = '#fff';
			jQuery('#font_bg_color').css('background-color', '');
			jQuery('#font_selection option:first').attr('selected', 'selected');
			jQuery('#effect').val('STRAIGHT');
			jQuery('.radius-effect, .spacing-effect, .reverse-effect').css('display', 'none');
		}
		// Hide the small-edit-element
		jQuery("#small-edit-element").css({
			"display": "none"
		});
	}
	var modal = document.getElementById('myModal');
	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
			if (event.target == modal) {
					modal.style.display = "none";
					//jQuery(".color-picker").hide();
			}
			
	}
	
	  jQuery('#upper-canvas').mousedown(function() {
        if(jQuery('.update-text').css('display')=='inline-block') {
				jQuery('.update-text').css('display', 'inline-block !important');
				jQuery('#add_text_button').css('display', 'none !important');
				console.log('g');
			}
			else {
				jQuery('.update-text').css('display', 'none !important');
				jQuery('#add_text_button').css('display', 'inline-block !important');
				console.log('s');
			}
    });
	
// Load all the option of simple product on selection of swatch on configurabl product page.
	jQuery("div.swatch-opt").on("click", "div.swatch-option", function () {
		setTimeout(function () {
			attributeConfig = {};
			var hasArea = document.getElementById('hasArea').value;
			if (hasArea == '') {
				jQuery('div.swatch-attribute').find('.selected').each(function () {
					var $selectedGrand = jQuery(this).parent().parent();
					attributeConfig[$selectedGrand.attr('attribute-code')] = $selectedGrand.attr('option-selected');
				});
			}
			var numItems = jQuery('.swatch-attribute').length;
			if (numItems == Object.keys(attributeConfig).length) {
				jQuery('#list_personalizer').show();
				jQuery('#enquire_now_btn').show();
				var baseUrl = jQuery("#baseURL").val();
				productId = document.getElementsByName('product')[0].value;
				attributeCallData = {
					'product_id': productId,
					'attributes': attributeConfig,
					'additional': ''
				};
				jQuery.ajax({
					type: 'POST',
					url: baseUrl + 'personalizedcool/index/load/',
					data: attributeCallData,
					showLoader: true,
					success: function (result) {
						jQuery('#personalized_it_btn').show();
						if (result.noarea != '') {
							canvas.clear();
							canvas.deactivateAll().renderAll();
							jQuery('#imageDiv').attr('src', result.base);
							jQuery('#config_id').val(result.id);
							jQuery('#container').remove();
							jQuery('#no-area').html('');
							jQuery('#product-zoom-container').remove();
							jQuery('div.gallery-placeholder').append(result.area);
							window.canvasObjects[result.id] = new fabric.Canvas('imageCanvas-' + result.id);
							canvas = window.canvasObjects[result.id];
							canvas.on({
								'object:moving': onSelected,
								'object:selected': onSelected,
								'object:modified': onModified,
								'selection:created': onSelected,
								'selection:cleared': onDeSelected
							});
						} else {
							jQuery('#no-area').html('');
							configurableId = jQuery('#config_id').val();
							jQuery('#drawingArea-' + configurableId).remove();
							jQuery('#product-zoom-container-' + configurableId).remove();
							jQuery('#no-area').append(result.area);
							jQuery('#imageDiv').attr('src', result.base);
							canvas = new fabric.Canvas('imageCanvas');
							canvas.setHeight(global_canvas_height);
							canvas.setWidth(global_canvas_width);
							canvas.renderAll();
							canvas.on({
								'object:moving': onSelected,
								'object:selected': onSelected,
								'object:modified': onModified,
								'selection:created': onSelected,
								'selection:cleared': onDeSelected
							});
						}
						if (result.html != '') {
							jQuery('<input id="template" class="personalized_tab_radio" name="tabs" type="radio"><label id="tab-template" for="template">Template</label>').insertAfter("#tab-clip");
							jQuery('<section id="content-template"> </section>').insertAfter('#content-clipart');
							jQuery('#content-template').html(result.html);
						} else {
							jQuery('#template').remove();
							jQuery('#tab-template').remove();
							jQuery('#content-template').remove();
						}
					}
				});
			}
		}, 500); // How long do you want the delay to be (in milliseconds)?
	}); //Swatch click event
	// No Area add to cart process
	noArea = function (widget, form, baseUrl) {
		index = 0;
		var imgUrl = document.getElementById("imageDiv").src;
		if (window.canvasObjects.length == 0) {
			var zcanvas = jQuery('#product-zoom-canvas');
			zcanvas.innerHTML = '';
			var imagearray = [];
			jQuery("#product-zoom-container").empty();
			jQuery('#product-zoom-container').append(zcanvas);
			var zoomCanvas = new fabric.Canvas('product-zoom-canvas');
			zoomCanvas.setHeight(canvas.getHeight());
			zoomCanvas.setWidth(canvas.getWidth());
			zoomCanvas.setBackgroundImage(imgUrl, zoomCanvas.renderAll.bind(zoomCanvas));
			canvas.deactivateAll();
			var originalImage = canvas.toDataURL();
			fabric.Image.fromURL(originalImage, function (oImg) {
				zoomCanvas.add(oImg.set({
					left: 0,
					top: 0
				}));
				zoomCanvas.renderAll();
				zoomCanvas.calcOffset();
				imagearray.push(zoomCanvas.toDataURL("image/png"));
				if (canvas.getObjects().length >= 1) {
					imagearray.push(originalImage);
				}
				// Get the img object.
				jQuery.ajax({
					type: 'POST',
					url: baseUrl,
					data: {
						'imagedata[]': imagearray
					},
					beforeSend: function () {
						jQuery('#product-addtocart-button').find('span').text("Adding...");
						jQuery('#product-addtocart-button').addClass('disabled');
					},
					success: function (result) {
						var imageElement = document.getElementById("image_html"); // Get the img object.
						imageElement.value = result;
						widget.catalogAddToCart('submitForm', jQuery(form));
					}
				}); //Ajax Call
			}); // Image Generation
		} else {
			id = jQuery("#config_id").val();
			var zcanvas = jQuery('#product-zoom-canvas-' + id);
			zcanvas.innerHTML = '';
			var imagearray = [];
			jQuery("#product-zoom-container-" + id).empty();
			jQuery('#product-zoom-container-' + id).append(zcanvas);
			var zoomCanvas = new fabric.Canvas('product-zoom-canvas-' + id);
			zoomCanvas.setBackgroundImage(imgUrl, zoomCanvas.renderAll.bind(zoomCanvas));
			canvas.deactivateAll();
			var originalImage = canvas.toDataURL();
			fabric.Image.fromURL(window.canvasObjects[id].toDataURL(), function (oImg) {
				zoomCanvas.add(oImg.set({
					left: parseInt(document.getElementById("drawingArea-" + id).style.left),
					top: parseInt(document.getElementById("drawingArea-" + id).style.top)
				}));
				zoomCanvas.renderAll();
				zoomCanvas.calcOffset();
				imagearray.push(zoomCanvas.toDataURL("image/png"));
				// Get the img object.
				jQuery.ajax({
					type: 'POST',
					url: baseUrl,
					data: {
						'imagedata[]': imagearray
					},
					beforeSend: function () {
						jQuery('#product-addtocart-button').find('span').text("Adding...");
						jQuery('#product-addtocart-button').addClass('disabled');
					},
					success: function (result) {
						var imageElement = document.getElementById("image_html"); // Get the img object.
						imageElement.value = result;
						widget.catalogAddToCart('submitForm', jQuery(form));
					}
				}); //Ajax Call
			}); // Image Generation
		}
	} //End No Area Add to cart
	jQuery(document).on('keyup', 'input#add_text', function () {
		var isMobile = window.matchMedia("only screen and (max-width: 760px)");
		if (isMobile.matches) {
			if (jQuery('#format_text').length) document.getElementById("format_text").disabled = false;
			if (jQuery('input#add_text').val() != '') {
				if (canvas.getActiveObject()) {
					updatetext();
				} else {
					addtext(true);
				}
			} else {
				deleteObject();
			}
			if (!jQuery('#add_text_button').hasClass("tab-view")) {
				jQuery('.update-text').hide();
				jQuery('#add_text_button').hide();
			}
		}
	}); // Event for text type for popup - app only
	jQuery(document).on('click', 'button.circle', function (e) {
		if (jQuery('section#content-text').css('display') == 'block') {
			if (jQuery('.text_effect_buttons').css('display') == 'block') {
				jQuery('.text_content').show();
				jQuery('.text_effect_buttons').hide();
				return;
			}
			if (jQuery('.font_style_popup').css('display') == 'block') {
				jQuery('.text_effect_buttons').show();
				jQuery('.font_style_popup').hide();
				return;
			}
			if (jQuery('.text_content').css('display') == 'block') {
				resetDefault();
				return;
			}
		}
		resetDefault();
	});
(function() {
    var image1 = document.createElement('img'),
        image2 = document.createElement('img'),
        image3 = document.createElement('img'),
        canvas,
        width = 1000,
        height = 500,
        randomColor = function randomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        };

    fabric.Object.prototype.setControlsVisibility( {
        ml: false,
        mr: false,
        mb: false,
        mt: false,
        mtr: false,
		tl: false,
		br: false,
		bl: false,
	   } );

    fabric.Canvas.prototype.customiseControls({
        tl: {
            action: 'rotate',
            cursor: 'crosshair',
        },
			 tr: {
            action: function(e, target) {
							deleteObject();
						},
            cursor: 'pointer',
        },
        br: {
            action: 'scale',
        },
				bl:{
      			 cursor: 'pointer',
						 action: function( e, target ) {
							 cloneObject();
       			 }
   		  },
    });
    // basic settings
		if (window.source_rotate_icon !== undefined) {
			fabric.Object.prototype.customiseCornerIcons({
					settings: {
							borderColor: '#f95e2e',
							borderType : 'dotted',
							cornerSize: 25,
							cornerShape: 'circle',
							cornerBackgroundColor: '#FFC300',
					},
					tl: {
							icon: source_rotate_icon,
					},
					tr: {
							icon: source_delete_icon,
					},
					br: {
							icon: source_resize_icon,
					},
					bl: {
							icon: source_duplicate_icon,
					}
			}, function() {
					//canvas.renderAll();
			});
		}	
 })();
}); // Required
