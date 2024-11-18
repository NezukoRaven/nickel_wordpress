jQuery(document).ready(function(){

  jQuery(".menuContainer ul").append("<ul><li>Minicart</li></ul>");

    jQuery(".elex-raq-maximize-btn").click(function(){
      jQuery(".elex-raq-quote-list-popup").removeClass("elex-raq-minimize");
      jQuery(".elex-raq-minimize-btn").removeClass("d-none");
      jQuery(this).addClass("d-none");
    });
  });


  jQuery(document).ready(function(){
    jQuery(".elex-raq-minimize-btn").click(function(){
      jQuery(".elex-raq-quote-list-popup").addClass("elex-raq-minimize");
      jQuery(".elex-raq-maximize-btn ").removeClass("d-none");
      jQuery(this).addClass("d-none");
    });

    
    jQuery(".elex-raq-quote-list-popup-container").hide();
    jQuery(".elex-raq-view-quote-list-open-btn").click(function(){
        jQuery(".elex-raq-quote-list-popup-container").show("slow");
    });
    jQuery(".elex-raq-view-quote-list-close-btn").click(function(){
        jQuery(".elex-raq-quote-list-popup-container").hide("slow");
    })
  });

  jQuery(window).on("load", function () {
    // jQuery(document).ready(function(){
    flag = false;
    attr = [];

    let pid =  jQuery(".component_options_select").val();

    jQuery(".component_options_select").each(function(){
      is_variable = false;
      var pid = jQuery(this).val();
      component_id = jQuery(this).closest('.composite_component').attr('data-item_id')
      item = {
        component_id: component_id,
        attribute_name:'',
        attribute_value:'',
        variation_id:'',
        pid:pid

      }

     
      var existingObj = jQuery.grep(attr, function(obj) {
        return obj.component_id === item.component_id &&
         obj.pid === item.pid;

      });
      if(existingObj < 1){
        attr.push(item);
      }
     
      localStorage.setItem('composite_products' , JSON.stringify(attr));
    

let count = 0;
    jQuery(".attribute_options select").each(function() {
        if(this.value === ''){
            count++
        }
    });
    if (count === 0) {
             jQuery('.add_to_quote').removeClass('disabled');
            jQuery('.add_to_quote').css('opacity','1');
            jQuery('.add_to_quote').attr('disabled', false); 

        // all select boxes has selected option 
    }else{
        jQuery('.add_to_quote').addClass('disabled');
        jQuery('.add_to_quote').css('opacity','0.5');
        jQuery('.add_to_quote').attr('disabled', true); 

    }
    })

   
    jQuery(".component_options_select").change(function(){

      pid = jQuery(this).val();
      component_id = jQuery(this).closest('.composite_component').attr('data-item_id')
      item = {
        component_id: component_id,
        attribute_name:'',
        attribute_value:'',
        variation_id:'',
        pid:pid

      }

        attr.push(item);
     var existingObj = jQuery.grep(attr, function(obj) {
      return obj.component_id === item.component_id;
    });
  
      if (existingObj.length > 0) {
        existingObj[0].pid = item.pid;
      } else {
        // Object does not exist, push the new object to the array
        attr.push(item);
      }

      localStorage.setItem('composite_products' , JSON.stringify(attr));

       jQuery( ".single_variation_wrap" ).on( "show_variation", function ( event, variation ) {
      component_id = jQuery(this).closest('.composite_component').attr('data-item_id')
  
       jQuery(this).closest('.composite_component').find('.attribute_options select').each(function(){
       var pid = jQuery(this).closest('.component_options_select').val();

        attr_val = jQuery(this).val();
         attr_name = jQuery(this).attr('id');
  
        item = {
          component_id: component_id,
          attribute_name:attr_name,
          attribute_value:attr_val,
         variation_id:variation.variation_id,
          pid:pid
  
        }
        
   
       var existingObj = jQuery.grep(attr, function(obj) {
        return obj.component_id === item.component_id && obj.component_id === item.pid;
      });
    
        if (existingObj.length > 0) {
          // Object already exists, override some properties
          existingObj[0].attribute_value = item.attribute_value;
          existingObj[0].attribute_name = item.attribute_name;

        } else {
          // Object does not exist, push the new object to the array
          attr.push(item);
        }
      });
      attr_length = jQuery(".attribute_options select ").length;
      
      localStorage.setItem('composite_products' , JSON.stringify(attr));
      
    });
    });
      jQuery(document).on("show_variation", ".single_variation_wrap", function(event, variation) {
      component_id = jQuery(this).closest('.composite_component').attr('data-item_id')
  
       jQuery(this).closest('.composite_component').find('.attribute_options select').each(function(){
        attr_val = jQuery(this).val();
         attr_name = jQuery(this).attr('id');
  
        item = {
          component_id: component_id,
          attribute_name:attr_name,
          attribute_value:attr_val,
          variation_id:variation.variation_id,
          pid:pid
  
        }
      //  if( attr.length === 0){
          attr.push(item);
      //  }
    
       var existingObj = jQuery.grep(attr, function(obj) {
        return  obj.component_id === item.component_id &&  obj.attribute_name === item.attribute_name ;
      });
    
        if (existingObj.length > 0) {
          // Object already exists, override some properties
          existingObj[0].attribute_value = item.attribute_value;

        } else {
          // Object does not exist, push the new object to the array
          attr.push(item);
        }
      });
      attr_length = jQuery(".attribute_options select ").length;
      localStorage.setItem('composite_products' , JSON.stringify(attr));
      let storeddata = localStorage.getItem('composite_products');
    });
    jQuery( ".single_variation_wrap" ).on( "hide_variation", function ( event, variation ) {
        jQuery('.add_to_quote').addClass('disabled');
        jQuery('.add_to_quote').css('opacity','0.5');
        jQuery('.add_to_quote').attr('disabled', true); 
  
    });
  
    jQuery('.reset_variations').click(function() {
      localStorage.setItem('composite_products' ,'');
  
      jQuery('.add_to_quote').addClass('disabled');
      jQuery('.add_to_quote').css('opacity','0.5');
      jQuery('.add_to_quote').attr('disabled', true); 
    });
  
    jQuery("table.variations select").each(function () {
      if (jQuery(this).val() == "" || jQuery(this).val() == undefined) {
        localStorage.removeItem("currently_selected_variation_id");
        localStorage.removeItem("selected_variation_attributes");
      }
    });

    jQuery(document).ready(function() {
      jQuery(document).on('change', 'select',function(){
        let count = 0;
        jQuery(".attribute_options select").each(function() {
            if(this.value === ''){
                count++
            }
        });
        if (count === 0) {
                 jQuery('.add_to_quote').removeClass('disabled');
                jQuery('.add_to_quote').css('opacity','1');
                jQuery('.add_to_quote').attr('disabled', false); 

            // all select boxes has selected option 
        }else{
            jQuery('.add_to_quote').addClass('disabled');
            jQuery('.add_to_quote').css('opacity','0.5');
            jQuery('.add_to_quote').attr('disabled', true); 

        }


      }).change();
    });
    
  
  });
  
  