require(["jquery"], function(jQuery) {
   // jQuery Solution for the checking checked radio for solving update issue - Bhupendra
  jQuery(document).on('change', '[type=checkbox]', function() {
  
    var duration = 500;
     setTimeout(function() {
        processHiddenValueIds('=cG9zaXRpb249');
     }, duration);
     
  }); 
  function processHiddenValueIds(links){
       hiddenString='';
       count=0;
       jQuery.each(jQuery("input[type='checkbox']:checked"), function(){            
            if(count==0){
             hiddenString+=jQuery(this).val()+links;
            }else{
              hiddenString+='&'+jQuery(this).val()+links;
            }
            count++;
      });
      jQuery("input[name='links[images]']").val(hiddenString);
   }
});