jQuery(document).ready(function ($) {
  // Añade la opción "Cambiar el estado a Listo para enviar" en ambos selectores de acción masiva
  var optionHtml =
    '<option value="mark_shipping-progress">Cambiar el estado a Listo para enviar</option>';
  $('select[name="action"]').prepend(optionHtml);
  $('select[name="action2"]').prepend(optionHtml);
});
