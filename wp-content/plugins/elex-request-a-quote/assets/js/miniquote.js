jQuery(document).ready(function () {
  if(jQuery("header:first").length > 0 && jQuery('.sticky-header-on').length == 0 ){
    jQuery(
      `<div id="elex-rqst-float-minicart-icon" class="elex-rqst-quote-front-wrap"><div class="container" id="mini_quote_list"></div></div>`
    )
      .insertAfter("header:first")
      .first();
  }
  else if( jQuery("nav.woocommerce-breadcrumb").length > 0 ){
    jQuery(
      `<div id="elex-rqst-float-minicart-icon" class="elex-rqst-quote-front-wrap"><div class="container" id="mini_quote_list"></div></div>`
    )
      .insertBefore("nav.woocommerce-breadcrumb");
  }else if( jQuery("header:first").length > 0 && jQuery('.sticky-header-on').length > 0 ){

    jQuery(
      `<div id="elex-rqst-float-minicart-icon" class="elex-rqst-quote-front-wrap"><div class="container" id="mini_quote_list"></div></div>`
    )
      .insertAfter(".sticky-header-on");

  }

  jQuery.ajax({
    type: "post",
    url: quote_list_ajax_obj.ajax_url,
    data: {
      action: "get_the_quote_list",
      ajax_raq_nonce: jQuery("#ajax_raq_nonce").val(),
    },
    success: function (data) {
      const iconPosition = data?.data?.widget?.quote_list_icon_position
        ? data?.data.widget.quote_list_icon_position
        : "float";

        if ( iconPosition === "float" && true === data.data.widget.show_widget_icon ) {
        jQuery("#elex-rqst-float-minicart-icon").addClass(
          "elex-rqst-mini-qote-list-wrap"
        );
      }
    },
  });
});
