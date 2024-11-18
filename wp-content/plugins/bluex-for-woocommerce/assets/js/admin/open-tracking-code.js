jQuery(function ($) {
  /**
   * Admin class.
   *
   * @type {Object}
   */
  var WCCorreiosOpenTrackingCode = {
    /**
     * Initialize actions.
     */
    init: function () {
      $(document.body).on(
        "click",
        ".correios-tracking-code a.tracking-code-link",
        this.openTrackingLink
      );
    },

    /**
     * Open tracking link into Blue's tracking URL.
     *
     * @param {Object} evt Current event.
     */
    openTrackingLink: function (evt) {
      evt.preventDefault();

      var code = $(this).text().trim();

      var trackingUrl =
        "https://www.blue.cl/seguimiento/?n_seguimiento=" +
        encodeURIComponent(code);
      window.open(trackingUrl, "_blank", "noopener,noreferrer");
    },
  };

  WCCorreiosOpenTrackingCode.init();
});
