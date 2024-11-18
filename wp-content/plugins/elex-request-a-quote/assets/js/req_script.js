jQuery(function() {
   
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, { container: ".elex-rqst-quote-wrap" });
    });
    //start off by hiding it
    jQuery("#elex-rqst-quote-light-box-input").hide();

    // when the Change EVENT fires - 
    jQuery("#elex-rqst-quote-form-select").change(function(event)

        {
            if (jQuery(this).val() == "light_box")
                jQuery("#elex-rqst-quote-light-box-input").show();
            else
                jQuery("#elex-rqst-quote-light-box-input").hide();
        });
});

jQuery(document).ready(function() {

    jQuery('.copy_api_key').click( function(e) {
        var api_key = jQuery('#api_key').val();
        copyToClipboard(api_key);
        jQuery('.rest_api_key').find('[data-bs-toggle="tooltip"]').tooltip({
            placement: "bottom",
            title:'New'
        });

        if(api_key.length > 0){
            jQuery("#rest_api_key_message").addClass("show");
        }
        setTimeout(function() {
            jQuery("#rest_api_key_message").removeClass("show");

        }, 3000);
    });

    function copyToClipboard(text) {
        if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
          navigator.clipboard.writeText(text)
            .then(() => {
              // Success callback
              jQuery("#rest_api_key_message").addClass("show");
              setTimeout(function() {
                jQuery("#rest_api_key_message").removeClass("show");
            }, 3000);
            })
            .catch((error) => {
              // Error callback
              jQuery("#rest_api_key_error_message").addClass("show");
              setTimeout(function() {
                jQuery("#rest_api_key_error_message").removeClass("show");
    
            }, 3000);
            });
        } else {
          // Fallback for older browsers
          const textarea = document.createElement('textarea');
          textarea.value = text;
          textarea.style.position = 'fixed';
          document.body.appendChild(textarea);
          textarea.focus();
          textarea.select();
      
          try {
            const successful = document.execCommand('copy');
            jQuery("#rest_api_key_message").addClass("show");
              setTimeout(function() {
                jQuery("#rest_api_key_message").removeClass("show");
    
            }, 3000);

          } catch (error) {
            jQuery("#rest_api_key_error_message").addClass("show");
              setTimeout(function() {
                jQuery("#rest_api_key_error_message").removeClass("show");
    
            }, 3000);
          }
      
          document.body.removeChild(textarea);
        }
      }

    jQuery('.products_by_name').select2({
        minimumInputLength: 3,
        ajax:{
            url:request_a_quote_ajax_obj.ajax_url,
            type: 'POST',
            
            
            data: function (params) {
                var query = {
                    search: params.term,
                    action: 'search_products_by_name',
                    req_a_quote_nonce : request_a_quote_ajax_obj.nonce
                }
               return query;
            },
            processResults: function (data) {
                return {
                    results: data.data,
                };
            }
  
        }
      
    });

    jQuery('.products_by_cat').select2({
        minimumInputLength: 3,
        ajax:{
            url:request_a_quote_ajax_obj.ajax_url,
            type: 'POST',
            
            data: function (params) {
                var query = {
                    search: params.term,
                    action: 'search_products_by_category',
                    req_a_quote_nonce : request_a_quote_ajax_obj.nonce
                }
               return query;
            },
            processResults: function (data) {
                return {
                    results: data.data,
                };
            }
  
        }
      
    });

    jQuery('.products_by_tag').select2({
        minimumInputLength: 3,
        ajax:{
            url:request_a_quote_ajax_obj.ajax_url,
            type: 'POST',
            
            data: function (params) {
                var query = {
                    search: params.term,
                    action: 'search_products_by_tag',
                    req_a_quote_nonce : request_a_quote_ajax_obj.nonce

                }
               return query;
            },
            processResults: function (data) {
                return {
                    results: data.data,
                };
            }
  
        }
      
    });

    jQuery('.include_roles , .exclude_roles , .req_order_status , #add_more_item_btn_redirection , #selected_page ').select2({
    });

    // widget page button label sub sub checkbox
    jQuery(".elex-rqust-quote-widget-button-label-content").hide();
    jQuery(".elex-rqust-quote-widget-button-label-check").change(function() {
        if (jQuery(this).is(":checked")) {
            jQuery(".elex-rqust-quote-widget-button-label-content").show(300);
        } else {
            jQuery(".elex-rqust-quote-widget-button-label-content").hide(200);
        }
    });
});



// Example starter JavaScript for disabling form submissions if there are invalid fields

(function() {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})()

jQuery(document).ready(function() {
    // Get the reference to the div with the class "my-div"
    var myDiv = jQuery('#quote_list');
    
    // var dyDiv = jQuery('.elex-raq-quote-details-container')
    // Function to add class based on div width
    function addClassBasedOnWidth() {
        var windowWidth = jQuery(window).width();
        
        if(windowWidth > 990){
            // Get the current width of the div
            var divWidth = myDiv.width();
            // Add the class "wide-div" if the width is greater than 400 pixels
            if (divWidth < 1100) {
                myDiv.addClass('elex-raq-quote-hide-names');
            } else {
                // Remove the class "wide-div" if the width is less than or equal to 400 pixels
                myDiv.removeClass('elex-raq-quote-hide-names');
            }
        }else{

            myDiv.removeClass('elex-raq-quote-hide-names');
        }
      
    }
    // Call the function on page load
  addClassBasedOnWidth();
  // Call the function on window resize to update the class if the div width changes
  jQuery(window).resize(function() {
    addClassBasedOnWidth();
  });
});