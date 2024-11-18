<?php
define('WP_CACHE', true); // WP-Optimize Cache
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );
/** Database username */
define( 'DB_USER', 'root' );
/** Database password */
define( 'DB_PASSWORD', 'root' );
/** Database hostname */
define( 'DB_HOST', 'localhost' );
/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );
/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'BS|b*O-a:1$ge.6d5{scwkGnB:NCcga?O{`l-pS?&7QIWzS#!QR~YBsE:G]= rk~' );
define( 'SECURE_AUTH_KEY',   '8u8:yW4/% x:XPP1^9xDyATmJfq+IM.ofh.1;TB^0z/b?G D%L-49UtsVJ>.oSfv' );
define( 'LOGGED_IN_KEY',     'JSqN4M;4&._QnIBfg>Jh5}fs%T&q/wH]`]e[d#]cUgH>6gp91KT;FdI+2j+.SS*G' );
define( 'NONCE_KEY',         'uH4)dd30AmTeLNrc@:4lSsmbUY*0x:9U}`TUBk|aKtn*&V`b8<_8d}ZR|pXb,$xY' );
define( 'AUTH_SALT',         'E$vM#^45RD2%8(M^r$P9Bs_c9wcaA)28]/HD)r9VNwX~q~4~sVa@ L9.N^u~3).p' );
define( 'SECURE_AUTH_SALT',  'M3 &9m42ti[T}E<8r&8DKotNIMZG!v2gZycjJ}fzHIOmO+%q2u@>_l!Z,_rK<3:`' );
define( 'LOGGED_IN_SALT',    'x-,`dfb.3!1ent]S[T+LgyaD^2luI6YEnWY+u@_Sd@#5Y^EMA |V}k9?qa>0+u7?' );
define( 'NONCE_SALT',        'ua,i)oP3Te$32+D|[7d^%@#~[Lb`gzv?&:mpMQ!8F6i^,@D]8IK6f ]mlg=LD}fH' );
define( 'WP_CACHE_KEY_SALT', '~e6<C6RD8>#4)_&>:;mE}}:,<!s>t3([O5[-Ae|1j543Mz([pmHi!2Z:?0}u?F Z' );
/**#@-*/
/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';
/* Add any custom values between this line and the "stop editing" line. */
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}
define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';