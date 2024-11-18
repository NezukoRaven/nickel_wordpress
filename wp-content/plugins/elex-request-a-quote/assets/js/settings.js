 // checkbox hide and show

// email template 
jQuery(".elex-rqst-quote-email-template-container").hide();
jQuery(".elex-rqust-quote-email-template-input").on('change', function(){
   if(jQuery(this).is(":checked")){
    jQuery(".elex-rqst-quote-email-template-container").show();
    jQuery('.email-body').hide();

   } else{
    jQuery(".elex-rqst-quote-email-template-container").hide();
    jQuery('.email-body').show();

   }
});

   // terms and condition toggle
   jQuery(".elex-rqst-terms").hide();
   jQuery(".elex-rqst-terms-toggle").on('change', function(){
      if(jQuery(this).is(":checked")){
        jQuery(".elex-rqst-terms").show();
      } else{
        jQuery(".elex-rqst-terms").hide();
      }
   });


    jQuery(".elex-rqust-quote-check-content").hide();
    jQuery(".elex-rqust-quote-check-sec-input").click(function() {
       let is_enabled = jQuery(this).prop('checked');
       jQuery(this).val(is_enabled);
   
        if (jQuery(this).prop('checked')) {
            jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-limit-product").show(300);
            
        } else {
            jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-limit-product").hide(200);
        }
    });
   
    if( jQuery(".elex-rqust-quote-check-sec-input").prop('checked') ){
       jQuery(".elex-rqust-quote-check-content-limit-product").show(300);
        jQuery("#limit_product_is_enabled").prop('checked',true);
        jQuery("#limit_product_is_enabled").val(true)
   
    }else{
        jQuery(".elex-rqust-quote-check-content-limit-product").hide(200);
        jQuery("#limit_product_is_enabled").prop('checked',false);
        jQuery("#limit_product_is_enabled").val(false);
    }
   
    //exclude prod
   
    jQuery(".elex-rqust-quote-check-sec-input-exclude-product").click(function() {
       let is_enabled = jQuery(this).prop('checked');
       jQuery(this).val(is_enabled);
       if (jQuery(this).prop('checked')) {
           jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-exclude-product").show(300);
       } else {
           jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-exclude-product").hide(200);
       }
   });
   
   
   
   //exclude product
   
   if( jQuery(".elex-rqust-quote-check-sec-input-exclude-product").prop('checked') ){
       jQuery(".elex-rqust-quote-check-content-exclude-product").show(300);
       
       jQuery("#exclude_product_enabled").prop('checked',true);
       jQuery("#exclude_product_enabled").val(true);
   
   
   }else{
       jQuery(".elex-rqust-quote-check-content-exclude-product").hide(200);
       jQuery("#exclude_product_enabled").prop('checked',false);
       jQuery("#exclude_product_enabled").val(false);
   
   }
   
   //role based
   if( jQuery(".elex-rqust-quote-check-sec-input-role-based").prop('checked') ){
       jQuery(".elex-rqust-quote-check-content-role-based").show(300);
       
       jQuery("#role_based_enabled").prop('checked',true);
       jQuery("#role_based_enabled").val(true);
   
   
   }else{
       jQuery(".elex-rqust-quote-check-content-role-based").hide(200);
       jQuery("#role_based_enabled").prop('checked',false);
       jQuery("#role_based_enabled").val(false);
       
   }
   //role based on change
   
   jQuery(".elex-rqust-quote-check-sec-input-role-based").click(function() {
       let is_enabled = jQuery(this).prop('checked');
       jQuery(this).val(is_enabled);
   
        if (jQuery(this).prop('checked')) {
            jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-role-based").show(300);
            
        } else {
            jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-role-based").hide(200);
        }
    })
   
    if(jQuery('.disable_quote_for_guest').prop('checked')){
       jQuery(".disable_quote_for_guest").val(true);
    }
    jQuery(".disable_quote_for_guest").change(function(){
       if(jQuery('.disable_quote_for_guest').prop('checked')){
           jQuery(".disable_quote_for_guest").val(true);
        }
        else{
           jQuery(".disable_quote_for_guest").val(false);
   
        }
    })
   
    let add_quote_button_color = document.querySelector('#add_quote_button_color');
    let add_quote_button_hex = document.querySelector('#add_quote_button_hex');
    if(add_quote_button_color != null)
       {
           add_quote_button_color.addEventListener('input', () => {
           add_quote_button_hex.value =  add_quote_button_color.value;
       });
       }
   
       //Hide add to cart section
       jQuery(".hide_add_cart_button_on_shop").click(function(){
           if( jQuery(".hide_add_cart_button_on_shop").prop('checked')){
            jQuery(".hide_add_cart_button_on_shop").prop('checked',true);
   
            jQuery(".hide_add_cart_button_on_shop").val(true);
           }
           else{
            jQuery(".hide_add_cart_button_on_shop").prop('checked',false);
   
               jQuery(".hide_add_cart_button_on_shop").val(false);
   
           }
       })
       if( jQuery(".hide_add_cart_button_on_shop").prop('checked') ){
            jQuery(".hide_add_cart_button_on_shop").prop('checked',true);
            jQuery(".hide_add_cart_button_on_shop").val(true)
        }else{
            jQuery(".hide_add_cart_button_on_shop").prop('checked',false);
            jQuery(".hide_add_cart_button_on_shop").val(false);
        }
   
   
        jQuery(".hide_add_cart_button_on_product").click(function(){
           if( jQuery(".hide_add_cart_button_on_product").prop('checked')){
            jQuery(".hide_add_cart_button_on_product").prop('checked',true);
   
            jQuery(".hide_add_cart_button_on_product").val(true);
           }
           else{
            jQuery(".hide_add_cart_button_on_product").prop('checked',false);
   
               jQuery(".hide_add_cart_button_on_product").val(false);
   
           }
       })
       if( jQuery(".hide_add_cart_button_on_product").prop('checked') ){
            jQuery(".hide_add_cart_button_on_product").prop('checked',true);
            jQuery(".hide_add_cart_button_on_product").val(true)
        }else{
            jQuery(".hide_add_cart_button_on_product").prop('checked',false);
            jQuery(".hide_add_cart_button_on_product").val(false);
        }
   
   //hide price
        jQuery(".hide_price").click(function(){
           if( jQuery(".hide_price").prop('checked')){
            jQuery(".hide_price").prop('checked',true);
   
            jQuery(".hide_price").val(true);
            enable_hide_add_to_cart()
           }
           else{
            jQuery(".hide_price").prop('checked',false);
   
               jQuery(".hide_price").val(false);
               disable_hide_add_to_cart()
   
           }
       })
       if( jQuery(".hide_price").prop('checked') ){
            jQuery(".hide_price").prop('checked',true);
            jQuery(".hide_price").val(true)
            enable_hide_add_to_cart()
        }else{
            jQuery(".hide_price").prop('checked',false);
            jQuery(".hide_price").val(false);
            disable_hide_add_to_cart()
            
        }
        function disable_hide_add_to_cart(){
            jQuery(".hide_add_cart_button_on_product").prop('disabled',false);
            jQuery(".hide_add_cart_button_on_shop").prop('disabled',false);
        }
        function enable_hide_add_to_cart(){
            jQuery(".hide_add_cart_button_on_product").prop('checked',true);
            jQuery(".hide_add_cart_button_on_product").val(true)
            jQuery(".hide_add_cart_button_on_shop").prop('checked',true);
            jQuery(".hide_add_cart_button_on_shop").val(true)
            jQuery(".hide_add_cart_button_on_product").prop('disabled',true);
            jQuery(".hide_add_cart_button_on_shop").prop('disabled',true);
        }
        
   jQuery('.elex-rqust-quote-check-sec-input-exclude-product').click(function(){
       if (jQuery(this).prop('checked')) {
           jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-hide-cart").show(300);
           
       } else {
           jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-hide-cart").hide(200);
       }
   });


   if( jQuery(".elex-rqust-quote-check-sec-input-exclude-product").prop('checked') ){
    jQuery(".elex-rqust-quote-check-content-hide-cart").show(300);
     jQuery("#hide_add_cart_exclude").prop('checked',true);
     jQuery("#hide_add_cart_exclude").val(true)

 }else{
     jQuery(".elex-rqust-quote-check-content-hide-cart").hide(200);
     jQuery("#hide_add_cart_exclude").prop('checked',false);
     jQuery("#hide_add_cart_exclude").val(false);
 }
  

 if( jQuery(".elex-rqust-quote-check-sec-input-sms").prop('checked') ){
    jQuery(".elex-rqust-quote-check-content-sms").show(300);
     jQuery("#is_sms_enabled").prop('checked',true);
     jQuery("#is_sms_enabled").val(true)

 }else{
     jQuery(".elex-rqust-quote-check-content-sms").hide(200);
     jQuery("#is_sms_enabled").prop('checked',false);
     jQuery("#is_sms_enabled").val(false);
 }

 jQuery('.elex-rqust-quote-check-sec-input-sms').click(function(){
    if (jQuery(this).prop('checked')) {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-sms").show(300);
        
    } else {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-sms").hide(200);
    }
});


if( jQuery(".elex-rqust-quote-check-sec-input-chat").prop('checked') ){
    jQuery(".elex-rqust-quote-check-content-chat").show(300);
     jQuery("#is_chat_enabled").prop('checked',true);
     jQuery("#is_chat_enabled").val(true)

 }else{
     jQuery(".elex-rqust-quote-check-content-chat").hide(200);
     jQuery("#is_chat_enabled").prop('checked',false);
     jQuery("#is_chat_enabled").val(false);
 }

 jQuery('.elex-rqust-quote-check-sec-input-chat').click(function(){
    if (jQuery(this).prop('checked')) {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-chat").show(300);
        
    } else {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-chat").hide(200);
    }
});
 

jQuery('.debug_enabled').click(function(){
if( jQuery(this).prop('checked') ){
     jQuery("#debug_enabled").val(true)

 }else{
     jQuery("#debug_enabled").prop('checked',false);
     jQuery("#debug_enabled").val(false);
 }
});



jQuery('.elex-rqust-quote-email-template-input').click(function(){

    if( jQuery(this).prop('checked') ){
         jQuery("#elex-rqust-quote-email-template-input").val(true)
     }else{
         jQuery("#elex-rqust-quote-email-template-input").prop('checked',false);
         jQuery("#elex-rqust-quote-email-template-input").val(false);
     }
    });
    
    if( jQuery('.elex-rqust-quote-email-template-input').prop('checked') ){
        jQuery("#elex-rqust-quote-email-template-input").val(true)
    jQuery(".elex-rqst-quote-email-template-container").show();
    jQuery('.email-body').hide();


    }else{
        jQuery("#elex-rqust-quote-email-template-input").prop('checked',false);
        jQuery("#elex-rqust-quote-email-template-input").val(false);
    jQuery(".elex-rqst-quote-email-template-container").hide();
    jQuery('.email-body').show();


    }

      // rest api check box
    // jQuery(".elex-reqst-rest-api").hide();
    jQuery(".elex-reqst-rest-api-check").click(function(){
        if (jQuery(this).is(":checked")) {
        jQuery("#api_key").prop('required',true);
        jQuery(".elex-reqst-rest-api").show(300);
        } else {
        jQuery("#api_key").prop('required',false);
        jQuery(".elex-reqst-rest-api").hide(200);
        }
    })

    if (jQuery(".elex-reqst-rest-api-check").is(":checked")) {

        jQuery("#api_key").prop('required',true);
        jQuery(".elex-reqst-rest-api").show(300);
    } else {
        jQuery("#api_key").prop('required',false);
        jQuery(".elex-reqst-rest-api").hide(200);
    }

//widget
if(jQuery('select[name="quote_list_icon_position"]').val() === 'float'){
    jQuery('.elex-button-label-quote-icon').hide();
}else{
    jQuery('.elex-button-label-quote-icon').show(); 
}
if( jQuery(".elex-rqust-quote-check-sec-input-widget").prop('checked') ){
    jQuery(".elex-rqust-quote-check-content-widget").show(300);
     jQuery("#show_widget").prop('checked',true);
     jQuery("#show_widget").val(true)

 }else{
     jQuery(".elex-rqust-quote-check-content-widget").hide(200);
     jQuery("#show_widget").prop('checked',false);
     jQuery("#show_widget").val(false);
 }
 jQuery('select[name="quote_list_icon_position"]').click(function(){
    if(jQuery('select[name="quote_list_icon_position"]').val() === 'float'){
        jQuery('.elex-button-label-quote-icon').hide();
    }else{
        jQuery('.elex-button-label-quote-icon').show(); 
    }
 });
 jQuery('#show_widget').click(function(){
    if (jQuery(this).prop('checked')) {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-widget").show(300);
        
    } else {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-widget").hide(200);
    }
    });

    jQuery('#show_list_popup_on_hover').click(function(){

    if(jQuery('#show_list_popup_on_hover').prop('checked')){
        jQuery(this).prop('checked',true);
        jQuery(this).val(true);
    }else{
        jQuery(this).prop('checked',false);
        jQuery(this).val(false);
    }})

    let widget_color = document.querySelector('#widget_button_color');
    let widget_hex = document.querySelector('#widget_hex');
    if(widget_color != null)
       {
        widget_color.addEventListener('input', () => {
            widget_hex.value =  widget_color.value;
       });
       }
jQuery(document).ready(function(){
   
    if( jQuery(".elex-rqust-quote-widget-button-label-check").prop('checked') ){
        jQuery(".elex-rqust-quote-widget-button-label-content").show(300);
    
    }else{
        jQuery(".elex-rqust-quote-widget-button-label-content").hide(200);
    }

        if(jQuery(".elex-rqst-terms-toggle").is(":checked")){
          jQuery(".elex-rqst-terms").show();
            jQuery(".elex-rqst-terms-toggle").prop('checked',true);

        } else{
          jQuery(".elex-rqst-terms").hide();
          jQuery(".elex-rqst-terms-toggle").prop('checked',false);

        }
    
})

//add more items
if( jQuery(".elex-rqust-quote-check-sec-input-add-more-items").prop('checked') ){
    jQuery(".elex-rqust-quote-check-content-add-more-items").show(300);
     jQuery("#add_more_items").prop('checked',true);
     jQuery("#add_more_items").val(true);



 }else{
     jQuery(".elex-rqust-quote-check-content-add-more-items").hide(200);
     jQuery("#add_more_items").prop('checked',false);
     jQuery("#add_more_items").val(false);

 }

 jQuery('#add_more_items').click(function(){
    if (jQuery(this).prop('checked')) {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-add-more-items").show(300);
        
    } else {
        jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-add-more-items").hide(200);
    }
    });

jQuery('.general_setting_save_chages').click(function(){
    if( jQuery('#exclude_product_enabled').prop('checked') ) {
         if(jQuery('.exclude_prod_by_cat').select2('data').length === 0 &&
        jQuery('.exclude_prod_by_name').select2('data').length === 0 &&
        jQuery('.exclude_prod_by_tag').select2('data').length === 0 ){
            alert("Choose any options for exclude category or tag or name");
            return false;
        }
        
    }
    if( jQuery('#limit_product_is_enabled').prop('checked') ) {
        if(jQuery('.include_prod_by_cat').select2('data').length === 0 &&
       jQuery('.include_prod_by_name').select2('data').length === 0 &&
       jQuery('.include_prod_by_tag').select2('data').length === 0 ){
           alert("Choose any options for include category or tag or name");
           return false;
       }
       
   }
   

   if( jQuery('#role_based_enabled').prop('checked') ) {
    if(jQuery('.include_roles').select2('data').length === 0 &&
   jQuery('.exclude_roles').select2('data').length === 0 ){
       alert("Choose any options for user role.");
       return false;
   }
   
}

if( jQuery('#hide_add_cart_exclude').prop('checked') ) {
    if(jQuery('.hidecart_exclude_prod_by_cat').select2('data').length === 0 &&
   jQuery('.hidecart_exclude_prod_by_name').select2('data').length === 0 &&
   jQuery('.hidecart_exclude_prod_by_tag').select2('data').length === 0 ){
       alert("Choose any options for exclude category or tag or name");
       return false;
   }
  
}


});
jQuery('.hidecart_setting_save_chages').click(function(){
 //button_on_product_page
 if( jQuery(".button_on_product_page").prop('checked') ){
    jQuery(".inline_below_add_to_cart").removeClass('d-none');
 }else{
     jQuery(".inline_below_add_to_cart").addClass('d-none');
 }
  //button_on_product_page
  jQuery('.button_on_product_page').click(function(){
  if( jQuery(this).prop('checked') ){
    jQuery(".inline_below_add_to_cart").removeClass('d-none');
 }else{
     jQuery(".inline_below_add_to_cart").addClass('d-none');
 }
})
if( jQuery('#hide_cart_exclude_role').prop('checked') ) {
    if(jQuery('.hide_cart_exclude_role').select2('data').length === 0  ){
       alert("Choose any options for exclude role");
       return false;
   }
  
}
});

jQuery(".elex-rqust-quote-check-sec-input-hidecart-role").click(function() {
    let is_enabled = jQuery(this).prop('checked');
    jQuery(this).val(is_enabled);

     if (jQuery(this).prop('checked')) {
         jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-hidecart").show(300);
         
     } else {
         jQuery(this).parentsUntil(".elex-rqust-quote-check-sec").find(".elex-rqust-quote-check-content-hidecart").hide(200);
     }
 });
 if( jQuery(".elex-rqust-quote-check-sec-input-hidecart-role").prop('checked') ){
    jQuery(".elex-rqust-quote-check-content-hidecart").show(300);
     jQuery("#hide_cart_exclude_role").prop('checked',true);
     jQuery("#hide_cart_exclude_role").val(true)

 }else{
     jQuery(".elex-rqust-quote-check-content-hidecart").hide(200);
     jQuery("#hide_cart_exclude_role").prop('checked',false);
     jQuery("#hide_cart_exclude_role").val(false);
 }

// });
function generateRandomString(length) {
    let result = '';
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < length; i++) {
      result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
  }

jQuery("#generate_api_key").click(function(e){
    e.preventDefault();
    const inputElement = jQuery('#api_key');
    const randomString = generateRandomString(20);
    jQuery('#api_key').val(randomString);
})
jQuery(".upload_company_logo").click(function(e){
    jQuery('.company_logo').click();
})
 //button_on_product_page
 if( jQuery(".button_on_product_page").prop('checked') ){
    jQuery(".inline_below_add_to_cart").removeClass('d-none');
 }else{
     jQuery(".inline_below_add_to_cart").addClass('d-none');
 }
  //button_on_product_page
  jQuery('.button_on_product_page').click(function(){
  if( jQuery(this).prop('checked') ){
    jQuery(".inline_below_add_to_cart").removeClass('d-none');
 }else{
     jQuery(".inline_below_add_to_cart").addClass('d-none');
 }
})