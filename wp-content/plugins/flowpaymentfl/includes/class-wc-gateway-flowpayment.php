<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Flow Gateway.
 *
 * @class    WC_Flow_Gateway
 * @version  3.0.8
 */
class WC_Flow_Gateway extends WC_Payment_Gateway
{

    const ERROR_CODE_CURRENCY_NOT_ALLOWED = 1000;

    protected $version;
    protected $friendlyPaymentMediumName;

    /**
     * Constructor for the gateway.
     */
    public function __construct()
    {

        $this->id = 'flowpayment';
        $this->version = '3.0.8';
        $this->friendlyPaymentMediumName = 'Flow WooCommerce Checkout';
        $this->has_fields = false;
        $this->method_title = __( 'Flow', 'woocommerce-gateway-flowpayment' );
        $this->method_description = 'Pasarela de pago Flow'; // will be displayed on the options page

        if (file_exists(PLUGIN_DIR . "images/logo-small.png")) {
            if (!file_exists(PLUGIN_DIR . "images/custom-logo-small.png")) {
                copy(PLUGIN_DIR . "images/logo-small.png", PLUGIN_DIR . "images/custom-logo-small.png");
            }
        }
        $this->icon = WC_GATEWAY_FLOWPAYMENT_URL . '/images/custom-logo-small.png';

        $this->init_form_fields();

        // Load the settings.
        $this->init_settings();
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->enabled = $this->get_option('enabled');

        add_action('woocommerce_api_confirm_' . $this->id, array($this, 'callback_confirm'));
        add_action('woocommerce_api_return_' . $this->id, array($this, 'callback_return'));
        add_action('woocommerce_api_custom_error_' . $this->id, array($this, 'callback_custom_error'));
        add_action('woocommerce_api_coupon_generated_' . $this->id, array($this, 'callback_coupon_generated'));
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'flow_process_admin_options'));

        add_filter('woocommerce_gateway_icon', function ( $icon ) {
			return str_replace('<img ', '<img style="height: 36px;max-height: 36px;" ', $icon);
		  }, 10, 2 );

    }


    /**
     * Called after checkout. Creates the payment and redirects to Flow
     *
     * @param int $order_id
     * @return void
     */
    public function process_payment($order_id)
    {

        $this->log('Entering process_payment', 'info');
        $confirm_url = add_query_arg('wc-api', 'confirm_' . $this->id, home_url('/'));
        $return_url = add_query_arg('wc-api', 'return_' . $this->id, home_url('/'));
        $params = $this->getOrder($order_id, $confirm_url, $return_url);
        try {
            $this->log('Calling flow service order/create from createPayment(): ' . ' with params: ' . json_encode($params));
            $this->processValidJson();

            $flowApi = $this->getFlowApi();
            $response = $flowApi->order($params);
            $this->log('Flow response: ' . json_encode($response));
            if (!isset($response["url_payment"])) {
                throw new \Exception("Ha ocurrido un error inesperado creando el pago en Flow");
            }
            $url_to_redirect = $response['url_payment'];
            return array(
                'result' => 'success',
                'redirect' => $url_to_redirect,
            );

        } catch (Exception $e) {
            $error = $e->getMessage();
            //wc_add_notice( __($error, 'woocommerce-gateway-flowpayment'), 'error' );
            $this->log('Unexpected error: ' . $e->getCode() . ' - ' . $e->getMessage(), 'error');
            throw new \Exception($error);
        }

    }

    /**
     * Redirect by Flow after the payment process is complete.
     *
     * @return void
     */
    public function callback_return()
    {

        $this->log('Entering callback_return', 'info');
        $flowApi = $this->getFlowApi();
        $token = filter_input(INPUT_POST, 'token');
        try {
            $this->log('Calling the flow service: ' . $service . '. Params: ' . json_encode($token));
            $result = $flowApi->getOrderStatus($token);
            $this->log('Flow response: ' . json_encode($result));
            $order_id = $result['commerce_order'];
            $status = $result['status'];

            if ($this->userCanceledPayment($status, $result)) {
                $this->log('User canceled the payment. Redirecting to checkout...', 'info');
                $this->redirectToCheckout();
            }
            $order = new WC_Order($order_id);
            $order_status = $order->get_status();

            $currency = $result["currency"];

            $amountInStore = round(number_format($order->get_total(), 0, '.', ''));
            if ($currency !== 'CLP') {
                $amountInStore = (float) number_format($order->get_total(), 2, '.', '');
            }

            $amountPaidInFlow = $result["amount"];
            $this->log('callback_return__Amount in store : ' . $amountInStore);

            if ($amountPaidInFlow != $amountInStore) {
                throw new Exception('The amount has been altered. Aborting...');
            }
            if ($this->isPendingInFlow($status)) {
                $this->clearCart();
                if ($this->userGeneratedCoupon($status, $result)) {
                    if (!empty($this->get_option('return_url'))) {
                        $this->redirectTo($this->get_option('return_url'));
                    }
                }

                $this->redirectToCouponGenerated($order);
            } elseif ($this->isPaidInFlow($status)) {
                if ($this->isPendingInStore($order_status)) {
                    $this->payOrder($order,$result["id"]);
                }
                $this->redirectToSuccess($order);
            } else {
                if ($this->isRejectedInFlow($status)) {
                    if ($this->isPendingInStore($order_status)) {
                        $this->rejectOrder($order);
                    }
                }
                if ($this->isCancelledInFlow($status)) {
                    if ($this->isPendingInStore($order_status)) {
                        $this->cancelOrder($order);
                    }
                }
                $this->redirectToFailure($order);
            }

        } catch (Exception $e) {
            $this->log('Unexpected error: ' . $e->getCode() . ' - ' . $e->getMessage(), 'error');
            $this->redirectToError();
        }

        wp_die();
    }
    /**
     * Called by flow asynchronously in order to confirm the payment.
     *
     * @return void
     */
    public function callback_confirm()
    {
        $this->log('Entering the confirm callback', 'info');
        $flowApi = $this->getFlowApi();
        $token = filter_input(INPUT_POST, 'token');
        try {
            $this->log('Calling the flow service: ' . $service . '. Params: ' . json_encode($token));
            $result = $flowApi->getOrderStatus($token);
            $this->log('Flow response: ' . json_encode($result));
            $order_id = $result['commerce_order'];
            $status = $result['status'];

            $order = new WC_Order($order_id);
            $order_status = $order->get_status();
            $currency = $result["currency"];

            $amountInStore = round(number_format($order->get_total(), 0, '.', ''));
            if ($currency !== 'CLP') {
                $amountInStore = (float) number_format($order->get_total(), 2, '.', '');
            }

            $amountPaidInFlow = $result["amount"];
            $this->log('callback_confirm__Amount in store : ' . $amountInStore);

            if ($amountPaidInFlow != $amountInStore) {
                throw new Exception('callback_confirm__The amount has been altered . Aborting...');
            }
            if ($this->isPendingInFlow($status)) {
                $this->setOrderAsPending($order);
            } elseif ($this->isPaidInFlow($status)) {
                $this->payOrder($order,$result["id"]);
            } elseif ($this->isCancelledInFlow($status)) {
                $this->cancelOrder($order);
            } else {
                $this->rejectOrder($order);
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            $this->log('Unexpected error: ' . $e->getCode() . ' - ' . $e->getMessage(), 'error');
        }
    }

    /**
     * Displays a page with a generic error in case any exception is thrown in the payment process.
     *
     * @return void
     */
    public function callback_custom_error()
    {
        global $woocommerce;
        $checkout_page_id = wc_get_page_id('checkout');
        //In case the url for the checkout is not found, we redirect to the home page.
        $url_return = ($checkout_page_id) !== -1 ? get_permalink($checkout_page_id) : get_home_url();

        wp_die(wc_get_template('error.php', array('url_return' => $url_return), 'flowpayment/', plugin_dir_path(__FILE__) . 'templates/'));
    }

    /**
     * Displays a page with a message for the user when they generate a coupon in multicaja or servipag.
     *
     * @return void
     */
    public function callback_coupon_generated()
    {
        $url_return = get_home_url();
        wp_die(wc_get_template('coupon-generated.php', array('url_return' => $url_return), 'flowpayment/', plugin_dir_path(__FILE__) . 'templates/'));
    }

    /**
     * Processes and validates the admin options
     *
     * @return void
     */
    public function flow_process_admin_options()
    {

        $hasFile = false;
        $nombrePlugin = basename(__DIR__);
        $idFileInput = "woocommerce_" . $this->id . "_logo-small";
        if (isset($_FILES[$idFileInput])) {
            $file = $_FILES[$idFileInput];
            $hasFile = $file['size'] > 0;
            if ($hasFile) {
                move_uploaded_file($file['tmp_name'], PLUGIN_DIR . "images/custom-logo-small.png");
            }
        }

        $post_data = $this->get_post_data();
        $anyErrors = false;

        if (empty($post_data['woocommerce_' . $this->id . '_api_key'])) {
            update_option('woocommerce_flow_api_key_valid', false);
            $anyErrors = true;
        } else {
            update_option('woocommerce_flow_api_key_valid', true);
        }

        if (!$anyErrors) {
            $this->process_admin_options();
        }

        return;

    }

    public function init_form_fields()
    {

        $this->form_fields = array(

            'enabled' => array(
                'title' => __('Activar/Desactivar', 'woocommerce-gateway-flowpayment'),
                'type' => 'checkbox',
                'label' => __('Activar o Desactivar', 'woocommerce-gateway-flowpayment'),
                'default' => 'no',
            ),
            'mode' => array(
                'title' => __('Selector de plataforma de Flow', 'woocommerce-gateway-flowpayment'),
                'desc' => __('Selector de plataforma de Flow', 'woocommerce-gateway-flowpayment'),
                'id' => 'platform_select',
                'default' => 'all',
                'type' => 'select',
                'class' => 'wc-enhanced-select',
                'css' => 'min-width: 350px;',
                'desc_tip' => true,
                'options' => array(
                    'TEST' => __('Plataforma sandbox Flow', 'woocommerce-gateway-flowpayment'),
                    'PROD' => __('Plataforma de producci&oacute;n Flow', 'woocommerce-gateway-flowpayment'),
                ),

            ),

            'title' => array(
                'title' => __('T&iacute;tulo', 'woocommerce-gateway-flowpayment'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __('Medio de pago utilizado', 'woocommerce-gateway-flowpayment'),
                'placeholder' => __('Medio de pago utilizado', 'woocommerce-gateway-flowpayment'),
                'default' => __('Flow', 'woocommerce-gateway-flowpayment'),
            ),
            'description' => array(
                'title' => __('Descripci&oacute;n', 'woocommerce-gateway-flowpayment'),
                'type' => 'textarea',
                'description' => __('Descripci&oacute;n Medio de Pago.', 'woocommerce-gateway-flowpayment'),
                'placeholder' => __('Descripci&oacute;n Medio de Pago.'),
                'desc_tip' => true,
            ),

            'api_key' => array(
                'title' => __('Api Key', 'woocommerce-gateway-flowpayment'),
                'type' => 'text',
                'desc_tip' => true,
                'description' => __('El Private Key corresponde a una clave de seguridad para asegurar que la información que se está trasmitiendo viene de una fuente confiable', 'woocommerce-gateway-flowpayment'),
                'placeholder' => __('Clave de Seguridad'),
            ),

            'logo-small' => array(
                'title' => __('Logo a mostrar', 'woocommerce-gateway-flowpayment'),
                'type' => 'file',
                'description' => __('Corresponde al logo a mostrar al momento de pagar para este método de pago', 'woocommerce-gateway-flowpayment'),
                'placeholder' => __('Logo'),
                'desc_tip' => true,
            ),
        );

        $this->form_fields['return_url'] = array(
            'title' => __('Return Url', 'woocommerce-gateway-flowpayment'),
            'type' => 'text',
            'description' => __('Ingrese su url de retorno', 'woocommerce-gateway-flowpayment'),
            'desc_tip' => true,
        );

    }

    /**
     * Renders the options for the admin and any errors in case there are any.
     *
     * @return void
     */
    public function admin_options()
    {

        if (!get_option('woocommerce_flow_api_key_valid')) {
            $this->add_error('La llave de api es obligatoria.');
        }

        if ($this->is_valid_for_use()) {
            $this->display_errors();
            parent::admin_options();
        } else {
            ?>
                <div class="inline error">
                    <p>
                        <strong><?php esc_html_e('Gateway disabled', 'woocommerce-gateway-flowpayment');?></strong>: <?php esc_html_e('Flow no soporta el tipo de moneda (' . get_woocommerce_currency() . ').', 'woocommerce-gateway-flowpayment');?>
                    </p>
                </div>
                <?php
}

    }

    private function redirectToSuccess($order)
    {
        $this->log('Redirecting to the success page...', 'info');
        $this->redirectTo($this->get_return_url($order));
    }

    private function redirectToFailure($order)
    {
        
        $checkout_page_id = wc_get_page_id('checkout');
        $url = ($checkout_page_id) !== -1 ? get_permalink($checkout_page_id) : $order->get_cancel_order_url();
        
        $error_message = 'Su pedido ha fallado debido a un error en el pago. Intente nuevamente';        

        $cancel_url = add_query_arg(array(
            'cancel_order' => 'true',
            'result' => 'error',
            'error_message' => $error_message
        ), $url);
        
        $this->redirectTo($cancel_url);

    }

    private function redirectToCouponGenerated()
    {
        $this->log('Redirecting to Coupon Generated...', 'info');
        $url = add_query_arg('wc-api', 'coupon_generated_' . $this->id, home_url('/'));
        $this->redirectTo($url);
    }

    private function redirectToError()
    {
        $this->log('Redirecting to Error...', 'info');
        $url = add_query_arg('wc-api', 'custom_error_' . $this->id, home_url('/'));
        $this->redirectTo($url);
    }

    private function redirectToCheckout()
    {
        $this->log('Redirecting to Checkout...', 'info');
        global $woocommerce;
        $checkout_page_id = wc_get_page_id('checkout');
        //In case the url for the checkout is not found, we redirect to the home page.
        $url = ($checkout_page_id) !== -1 ? get_permalink($checkout_page_id) : get_home_url();
        $this->redirectTo($url);
    }

    /**
     * Checks if the order is paid in Flow
     *
     * @param int $status
     * @return boolean
     */
    private function isPaidInFlow($status)
    {
        return $status == 2;
    }

    /**
     * Checks if the order is rejected in Flow.
     *
     * @param int $status
     * @return boolean
     */
    private function isRejectedInFlow($status)
    {
        return $status == 3;
    }

    /**
     * Checks if the order is canceled in Flow.
     *
     * @param int $status
     * @return boolean
     */
    private function isCancelledInFlow($status)
    {
        return $status == 4;
    }

    /**
     * Checks if the order is pending in Flow.
     *
     * @param int $status
     * @return boolean
     */
    private function isPendingInFlow($status)
    {
        return $status == 1;
    }

    /**
     * Checks if the order in the store has a pending status
     *
     * @param int $orderStatus
     * @return boolean
     */
    private function isPendingInStore($orderStatus)
    {
        return $orderStatus == 'pending';
    }

    /**
     * Checks if the order in the store has a paid status.
     *
     * @param int $orderStatus
     * @return boolean
     */
    private function isPaidInStore($orderStatus)
    {
        return $orderStatus == 'completed';
    }

    /**
     * Checks if the order in the store has a processing status.
     *
     * @param int $orderStatus
     * @return boolean
     */
    private function isProcessingInStore($orderStatus)
    {
        return $orderStatus == 'processing';
    }

    private function clearCart()
    {
        global $woocommerce;
        $woocommerce->cart->empty_cart();
    }

    private function hasReturnUrl()
    {
        //   return !$this->isWebpay() && !$this->isOnepay() && !$this->isMach();
        return true;
    }

    private function redirectTo($url)
    {
        wp_redirect($url);
        die();
    }
    /**
     * Checks if the current plugin is in testing mode (sandbox)
     *
     * @param array $flowData
     * @return boolean
     */
    private function isTesting($flowData)
    {
        return ($this->get_option('mode') === 'TEST'
            && (strtolower($flowData['pending_info']['media']) === 'servipag'
                || strtolower($flowData['pending_info']['media']) === 'multicaja')
        );
    }

    /**
     * Checks if the user canceled the payment (webpay or onepay)
     *
     * @param int $status
     * @param array $flowData
     * @return boolean
     */
    private function userCanceledPayment($status, $flowData)
    {
        return $this->isPendingInFlow($status)
        && empty($flowData['payment']['media'])
        && empty($flowData['pending_info']['media']);
    }

    /**
     * Checks if the user generated a coupon
     *
     * @param int $status
     * @param array $flowData
     * @return boolean
     */
    private function userGeneratedCoupon($status, $flowData)
    {
        return $this->isPendingInFlow($status)
        && !empty($flowData['pending_info']['media']
            && empty($flowData['payment']['media']));
    }

    /**
     * Sets some variables in order to the emulate the production environment (since both environment return different results)
     *
     * @param int $status
     * @param array $flowData
     * @return void
     */
    private function setProductionEnvSimulation(&$status, &$flowData)
    {

        $status = 1;
        $flowData['pending_info']['media'] = $flowData['payment']['media'];
        $flowData['payment']['media'] = '';
    }

    /**
     * Pays the order in the store
     *
     * @param WC_Order $order
     * @return void
     */
    private function payOrder($order,$trxId)
    {
        $this->log('Paying order #' . $order->get_order_number());
        $idTransaction = '';
        if($trxId){
            $idTransaction = ', transacción: '.$trxId;
        }
        $this->addOrderNote($order, 'Pagado con flow'.$idTransaction);
        $order->payment_complete();
    }

    /**
     * Rejects the order in the store.
     *
     * @param WC_Order $order
     * @return void
     */
    private function rejectOrder($order)
    {
        $this->log('Rejecting order #' . $order->get_order_number());
        $this->addOrderNote($order, 'La orden fue rechazada por Flow');
        $order->update_status('failed');
    }

    /**
     * Cancels the order in the store
     *
     * @param WC_Order $order
     * @return boolean
     */
    private function cancelOrder($order)
    {
        $this->log('Cancelling order #' . $order->get_order_number());
        $this->addOrderNote($order, 'La orden fue cancelada por Flow');
        $order->update_status('cancelled');
    }

    /**
     * Adds a pending note to the order, since the pending status is the default
     *
     * @param WC_Order $order
     * @return void
     */
    private function setOrderAsPending($order)
    {
        $this->log('Setting as pending order #' . $order->get_order_number());
        //Since the default status of the order is pending, we only add a note here.
        $this->addOrderNote($order, 'La orden se encuentra pendiente.');
    }

    private function addOrderNote($order, $note)
    {
        $order->add_order_note(__($note, 'woocommerce-gateway-flowpayment'));
    }

    private function is_valid_for_use()
    {
        return true;
    }

    /**
     * Returns an order object. Depending on the version of woocommerce, it will use one method or another.
     *
     * @param int $order_id
     * @return void
     */
    private function getOrder($order_id, $urlConfirm = null, $urlReturn = null)
    {
        if ($this->flow_woocommerce_version_check()) {
            $order = new WC_Order($order_id);
            $email = $order->get_billing_email();
            $customer = new WC_Customer();
            $concept = 'Orden: ' . $order_id . " - " . urldecode(get_bloginfo('name'));
            $currency = $order->get_currency();
            $amount = round(number_format($order->get_total(), 0, '.', ''));
            if ($currency !== 'CLP') {
                $amount = (float) number_format($order->get_total(), 2, '.', '');
            }

            $data = array(
                'payment_method' => 9,
                'commerce_order' => $order_id,
                'amount' => $amount,
                'email' => $email,
                'subject' => $concept,
                'url_confirm' => $urlConfirm,
                'url_return' => $urlReturn,
                'currency' => $currency,
                'payment_currency' => $currency,
                'customer' => [
                    'first_name' => $order->get_billing_first_name(),
                    'last_name' => $order->get_billing_last_name(),
                    'email' => $email,
                    'phone1' => $order->get_billing_phone(),
                ],
            );

            if (!empty($order->get_billing_country()) && !empty($order->get_billing_address_1())) {
                $data['customer']['country'] = $order->get_billing_country() ?? null;

                $data['billing_address'] = [
                    'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'country' => $order->get_billing_country(),
                    'line1' => $order->get_billing_address_1(),
                    'line2' => $order->get_billing_address_2() ?? null,
                    'city' => $order->get_billing_city() ?? null,
                    'zip' => $order->get_billing_postcode() ?? null,
                    'phone' => $order->get_billing_phone() ?? null,
                ];
            }

            if (!empty($order->get_shipping_country()) && !empty($order->get_shipping_address_1())) {

                $data['shipping_address'] = [
                    "name" => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
                    "country" => $order->get_shipping_country(),
                    "line1" => $order->get_shipping_address_1(),
                    "line2" => $order->get_shipping_address_2(),
                    "city" => $order->get_shipping_city(),
                    "zip" => $order->get_shipping_postcode() ?? null,
                ];
            }

            $items = $order->get_items();
            if (!empty($items)) {
                foreach ($items as $item_id => $item) {
                    $product = $item->get_product();
                    $sku = $product->get_sku();
                    if (empty($sku)) {
                        $sku = null;
                    }
                    $price = round(number_format($product->get_price(), 0, '.', ''));
                    $amount = round(number_format($item->get_total(), 0, '.', ''));
                    if ($currency !== 'CLP') {
                        $price = (float) number_format($product->get_price(), 2, '.', '');
                        $amount = (float) number_format($item->get_total(), 2, '.', '');
                    }
                    $desc = $product->get_short_description();
                    $desc = $this->parseItem($desc, "", 255);
                    $nombre = $item->get_name();
                    $nombre = $this->parseItem($nombre, "sku", 100);
                    $it = array(
                        "type" => "sku",
                        "name" => $nombre,
                        "sku" => $sku,
                        "product_code" => $item->get_product_id(),
                        "description" => $this->cleanString($desc, $nombre),
                        "quantity" => $item->get_quantity(),
                        "created_at" => $product->get_date_created()->date_i18n(),
                        "unit_cost" => $price,
                        "amount" => $amount,
                        "currency" => $currency,
                    );
                    $data["items"][] = $it;

                }
            }

            $data["metadata"] = $this->getMetadataComerce(urldecode(get_bloginfo('name')));

            return $data;

        } else {
            throw new \Exception('Verifique versión WooCommerce, debe ser superior a 4.0.0');
        }
    }

    private function cleanString($value, $defaultValue = null)
    {

        $newValue = null;

        if (function_exists('mb_convert_encoding')) {
            $newValue = mb_convert_encoding($value, "UTF-8");
        }

        return $newValue !== null && $newValue !== false && strlen(trim($newValue)) > 0 ? $newValue : $defaultValue;

    }

    private function parseItem($value, $replace = "", $len = 255)
    {
        if ($value == null) {
            $value = $replace;
        }
        if (strlen($value) > $len) {
            $value = substr($value, 0, $len);
        }

        if (function_exists("iconv") && function_exists("mb_detect_encoding")) {
            $value = iconv(mb_detect_encoding($value, mb_detect_order(), true), "UTF-8", $value);
        } else {
            $value = $replace;
        }
        return $value;

    }

    /**
     * Checks if the woocommerce's version is at least 3.0 or more
     *
     * @param string $version
     * @return void
     */
    private function flow_woocommerce_version_check($version = '4.0.0')
    {
        if (class_exists('WooCommerce')) {
            global $woocommerce;
            if (version_compare($woocommerce->version, $version, ">=")) {
                return true;
            }

            return false;
        }
        return false;
    }

    public function getFlowApi()
    {
        $platform = $this->get_option('mode');
        $isTestPlatform = !$platform || $platform == 'TEST';
        $urlApi = $isTestPlatform ? "https://sandbox.flow.cl/api/v2" : "https://www.flow.cl/api/v2";

        $apiKey = $this->get_option('api_key');
        return new FlowApiV2($urlApi, $apiKey);
    }

    private function log($message, $type = 'info')
    {

        //if the version of wocommerce >= 3
        if ($this->flow_woocommerce_version_check()) {
            $logger = wc_get_logger();
            $logger->{$type}($message, array('source' => 'flow_' . $this->version));
        } else {
            $logger = new WC_Logger('flow_' . $this->version);
            $logger->add('flow_' . $this->version, $message);
        }

    }

    private function processValidJson()
    {
        //parser data
        $hasError = true;
        $str = preg_replace_callback(
            "/\\\\u([a-f0-9]{4})/"
            , function ($match) {
                return iconv('UCS-4LE', 'UTF-8', pack('V', hexdec('U' . $match[1])));
            }
            , json_encode($data)
        );

        $error = json_last_error();
        $str = $error . "";
        switch ($error) {
            case JSON_ERROR_NONE:
                $hasError = false;
                break;
            case JSON_ERROR_DEPTH:
                $str .= ' - Excedido tamaño máximo de la pila';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $str .= ' - Desbordamiento de buffer o los modos no coinciden';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $str .= ' - Encontrado carácter de control no esperado';
                break;
            case JSON_ERROR_SYNTAX:
                $str .= ' - Error de sintaxis, JSON mal formado';
                break;
            case JSON_ERROR_UTF8:
                $str .= ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta';
                break;
            default:
                $str .= ' - Error desconocido';
                break;
        }
        if ($hasError) {
            $this->log($str);
        }
        return $hasError;
    }

    /**
     * Return metadata information comerce plugin
     *
     * @return array
     */
    private function getMetadataComerce($shopName)
    {
        $shopName = empty($shopName) ? "" : trim($shopName);
        global $woocommerce;
        global $wp_version;
        $metadata = array();
        $metadata[] = array("key" => 'ecommerce_name', "value" => "WooCommerce", "visible" => false);
        $metadata[] = array("key" => 'ecommerce_version', "value" => $woocommerce->version, "visible" => false);
        $metadata[] = array("key" => 'plugin_name', "value" => $this->friendlyPaymentMediumName, "visible" => false);
        $metadata[] = array("key" => 'plugin_version', "value" => $this->version, "visible" => false);
        $metadata[] = array("key" => 'wordpress_version', "value" => $wp_version, "visible" => false);
        if (strlen($shopName) > 0) {
            $metadata[] = array("key" => 'shop_name', "value" => $shopName, "visible" => false);
        }
        return $metadata;
    }
}