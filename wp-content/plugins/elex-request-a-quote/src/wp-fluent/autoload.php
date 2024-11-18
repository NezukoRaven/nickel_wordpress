<?php

// Autoload Service Container.
require 'libs/viocon/autoload.php';

spl_autoload_register(function ( $class) {

	$namespace = 'WpFluent';

	if (substr($class, 0, strlen($namespace)) !== $namespace) {
		return;
	}

	$className = str_replace(
		array('\\', $namespace, strtolower($namespace)),
		array('/', 'src', ''),
		$class
	);

	$basePath = plugin_dir_path(__FILE__);

	$file = $basePath . trim($className, '/') . '.php';

	if (is_readable($file)) {
		include $file;
	}
});


if (! function_exists('wpFluent')) {
	/**
	 * @return \WpFluent\QueryBuilder\QueryBuilderHandler
	 */
	function wpFluent() {
		static $wpFluent;

		if (! $wpFluent) {
			global $wpdb;

			$connection = new WpFluent\Connection($wpdb, ['prefix' => $wpdb->prefix], 'DB');

			$wpFluent = new \WpFluent\QueryBuilder\QueryBuilderHandler($connection);
		}

		return $wpFluent;
	}
}
