jQuery(window).on("load", function () {

    jQuery('form.variations_form').on('show_variation', function(event, data){
        localStorage.setItem('currently_selected_variation_id', data.variation_id);
        var item = new Array();
        var variation_form = jQuery( this ).closest( '.variations_form' );
        var variations = variation_form.find( 'select[name^=attribute]' );
        if ( !variations.length) {
            variations = variation_form.find( '[name^=attribute]:checked' );
        }
        if ( !variations.length) {
            variations = variation_form.find( 'input[name^=attribute]' );
        }
    
        variations.each( function() {
            var tthis = jQuery( this );
                var attributeName = tthis.attr( 'name' );
                var attributevalue = tthis.val();
                var attributeName_final=attributeName.replace('attribute_','');
                item.push({
                attribute_value:attributevalue,
                attribute_name:attributeName_final,
                });
        } );
        localStorage.setItem('selected_variation_attributes', JSON.stringify(item));
       if( ( variation_js_obj.settings.general.include_exclude_based_on_stock === 'show_for_all_products') || 
      ( ( !data.is_in_stock) && variation_js_obj.settings.general.include_exclude_based_on_stock === 'show_for_out_of_stock_only'  )
            ||
            ( ( data.is_in_stock == true) && variation_js_obj.settings.general.include_exclude_based_on_stock === 'hide_for_out_of_stock')
    ){
            //Enable quote button when variation is selected
            jQuery('.add_to_quote' ).removeClass('disabled');
            jQuery('.add_to_quote' ).css('opacity','');
            jQuery('.add_to_quote' ).removeAttr('disabled');	
       }
      							
    });
    //Disable quote button when reset variation is triggered
    jQuery('.reset_variations').click(function() {
        jQuery('.add_to_quote').addClass('disabled');
        jQuery('.add_to_quote').css('opacity','0.5');
        jQuery('.add_to_quote').attr('disabled', true); 
    });
    });