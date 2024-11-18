<?php

namespace Elex\RequestAQuote;

class Migrate {

	const MIGRATION_BATCH_KEY = 'REQUEST_A_QUOTE_MIGRATION_BATCH';

	const TABLE_QUOTE_LIST     = 'elex_quote_list';
	const TABLE_QUOTE_PRODUCTS = 'elex_quote_products';


	private $current_batch = 1;

	private $batch;

	public static function run() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$self = new self();

		if ( is_multisite() === false ) {
			$self->up();
			return;
		}

		// Get all blogs in the network and activate plugin on each one
		global $wpdb;
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

		foreach ( $blog_ids as $blog_id ) {
			switch_to_blog( $blog_id );
			$self->up();
			restore_current_blog();
		}
	}

	public function up() {
		$this->batch = get_option( self::MIGRATION_BATCH_KEY, 0 );

		while ( $this->batch < $this->current_batch ) {
			$this->batch++;
			$method = 'upgrade_' . $this->batch;

			if ( method_exists( $this, $method ) ) {
				$this->{$method}();
			}
			update_option( self::MIGRATION_BATCH_KEY, $this->current_batch );

		}

	}

	public function upgrade_1() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table1 = $wpdb->prefix . self::TABLE_QUOTE_LIST;
		$query  = "CREATE TABLE IF NOT EXISTS  $table1
		        (   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
		            `session_key` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
		            `user_id` BIGINT  NULL DEFAULT NULL,
		            `status_id` INT  NOT NULL DEFAULT 1,
					`created_at` TIMESTAMP NOT NULL,
					`updated_at` TIMESTAMP NOT NULL,
		            PRIMARY KEY (`id`)
		        ) $charset_collate;";
		dbDelta( $query );
	  
		$table_name = $wpdb->prefix . self::TABLE_QUOTE_PRODUCTS;
		$query      = "CREATE TABLE IF NOT EXISTS  $table_name
                (   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
                   `quote_list_id` BIGINT UNSIGNED NOT NULL,
                    `product_id` INT  NOT NULL,
					`quantity` INT  NOT NULL,
					`variation_id` INT DEFAULT NULL,
                    PRIMARY KEY (`id`),
					FOREIGN KEY (`quote_list_id`) REFERENCES $table1 (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
                ) $charset_collate;";
		dbDelta( $query );
	
	}
}
