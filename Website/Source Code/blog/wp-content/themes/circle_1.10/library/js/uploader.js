function register_upload_button_event(obj){    
    var parent = jQuery(obj).parent();
    formfield = parent.find( jQuery('.kp_input_box') ).attr('name');  
    tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=554');   
    window.send_to_editor = function(html) {
        imgurl = jQuery('img',html).attr('src');        
        parent.find( jQuery('.kp_input_box') ).val(imgurl);
        parent.find( jQuery('.kp_image_box') ).attr('src', imgurl).show();            
        tb_remove();
    }
}

function register_remove_button_event(obj){    
    var parent = jQuery(obj).parent();    
    var txt =  parent.find( jQuery('.kp_input_box') ).first();     
    txt.val('');    
}

jQuery(document).ready(function() { 
    jQuery('.upload_image_button').click(function() {
        var clickedID = jQuery(this).attr('alt');	
        formfield = jQuery('#'+clickedID).attr('name');
        tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=554');
        return false;
    });
    jQuery('.upload_image_button').click(function() {
        var clickedID = jQuery(this).attr('alt');	
        window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#'+clickedID).val(imgurl);           
            tb_remove();
        }
    });
});

jQuery(document).ready(function() { 
   var image_field;
    jQuery(function(){
      jQuery(document).on('click', '.widget_upload_image_button', function(evt){
        image_field = jQuery(this).siblings('.kopa_adv_upload_image');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
      });
      window.send_to_editor = function(html) {
        imgurl = jQuery('img', html).attr('src');
        image_field.val(imgurl);
        tb_remove();
      }
    });
    
});