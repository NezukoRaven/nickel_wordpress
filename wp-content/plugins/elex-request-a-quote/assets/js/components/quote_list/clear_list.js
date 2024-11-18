export const ClearList = () => {

    jQuery.ajax({
        type: 'post',
        url: request_a_quote_ajax_obj.ajax_url,
        data: {
          action: 'elex_raq_clear_list',
          ajax_raq_nonce: request_a_quote_ajax_obj.nonce
        },
        success: function success(data) {
          if (data.success === true) {
          jQuery(window).trigger("clear_list_event", data.data.quote_list_data);
            
          }
        }
      });

    
}