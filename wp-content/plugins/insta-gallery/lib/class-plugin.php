<?php

namespace QuadLayers\IGG;

use QuadLayers\IGG\Controllers\Backend;
use QuadLayers\IGG\Controllers\Frontend;
use QuadLayers\IGG\Controllers\Gutenberg;
use QuadLayers\IGG\Models\Accounts as Models_Accounts;
use QuadLayers\IGG\Models\Settings as Models_Settings;

final class Plugin {

	protected static $instance;

	private function __construct() {
		/**
		 * Load plugin textdomain.
		 */
		load_plugin_textdomain( 'insta-gallery', false, QLIGG_PLUGIN_DIR . '/languages/' );
		/**
		 * Load api classes.
		 */
		Api\Rest\Routes_Library::instance();
		/**
		 * Load plugin classes.ß
		 */
		Frontend::instance();
		Backend::instance();
		Gutenberg::instance();

		do_action( 'qligg_init' );

		// Filter to add 50 days interval to cron_schedules.
		add_filter(
			'cron_schedules',
			function () {
				$schedules['fifty_days'] = array(
					'interval' => DAY_IN_SECONDS * 50,
					'display'  => esc_html__( 'Every fifty days', 'insta-gallery' ),
				);
				return $schedules;
			}
		);

		// Action to auto renew account access_token, if it can be done automatically. Send an email to inform admin about access_token expiration.
		add_action(
			'qligg_cron_account',
			function ( $id ) {
				$account             = Models_Accounts::instance()->get( $id );
				$old_expiration_date = $account['access_token_expiration_date'];

				$is_renewed_account = Models_Accounts::instance()->is_access_token_renewed( $account );
				if ( ! $is_renewed_account ) {
					return false;
				}

				$account_renewed = Models_Accounts::instance()->get( $id );
				$new_expiration  = $account_renewed['access_token_expiration_date'];

				$admin_email = $this->get_admin_email();

				$message = esc_html__( 'Hi! We would like to inform you that the business account token you are using in Social Feed Gallery is about to expire.', 'insta-gallery' );
				$subject = esc_html__( 'Your business account is about to expire.', 'insta-gallery' );

				if ( $old_expiration_date >= $new_expiration ) {
					wp_mail( $admin_email, $subject, $message );
				}

				if ( ! isset( $account_renewed['access_token'] ) ) {
					$message .= esc_html__( 'Please sign in again to keep the plugin functioning.', 'insta-gallery' );
					wp_mail( $admin_email, $subject, $message );
				}
			},
			10,
			1
		);
	}

	protected function get_admin_email() {
		$user_settings = Models_Settings::instance()->get();
		if ( isset( $user_settings['mail_to_alert'] ) ) {
			return $user_settings['mail_to_alert'];
		}
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			return get_site_option( 'admin_email' );
		}
		return get_option( 'admin_email' );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
