/* global ajaxurl, WCCorreiosIntegrationAdminParams */
jQuery(function ($) {
  /**
   * Admin class.
   *
   * @type {Object}
   */
  var WCCorreiosIntegrationAdmin = {
    /**
     * Initialize actions.
     */
    init: function () {
      this.toggleGoogleKeyInput();
      this.toggleDevInput();
      $(document.body).on(
        "click",
        "#woocommerce_correios-integration_autofill_empty_database",
        this.empty_database
      );
      $("#woocommerce_correios-integration_pudoEnable").on(
        "change",
        this.toggleGoogleKeyInput.bind(this)
      );
      $("#woocommerce_correios-integration_devOptions").on(
        "change",
        this.toggleDevInput.bind(this)
      );
    },

    /**
     * Toggle Google Key input visibility based on checkbox.
     */
    toggleGoogleKeyInput: function () {
      var isChecked = $("#woocommerce_correios-integration_pudoEnable").is(
        ":checked"
      );
      $("#woocommerce_correios-integration_googleKey")
        .closest("tr")
        .toggle(isChecked);
    },
    /**
     * Toggle Dev Key input visibility based on checkbox.
     */
    toggleDevInput: function () {
      var isChecked = $("#woocommerce_correios-integration_devOptions").is(
        ":checked"
      );
      $("#woocommerce_correios-integration_alternativeBasePath")
        .closest("tr")
        .toggle(isChecked);
    },

    /**
     * Empty database.
     *
     * @return {String}
     */
    empty_database: function () {
      if (
        !window.confirm(WCCorreiosIntegrationAdminParams.i18n_confirm_message)
      ) {
        return;
      }

      $("#mainform").block({
        message: null,
        overlayCSS: {
          background: "#fff",
          opacity: 0.6,
        },
      });

      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "correios_autofill_addresses_empty_database",
          nonce: WCCorreiosIntegrationAdminParams.empty_database_nonce,
        },
        success: function (response) {
          window.alert(response.data.message);
          $("#mainform").unblock();
        },
      });
    },
  };

  WCCorreiosIntegrationAdmin.init();
});
