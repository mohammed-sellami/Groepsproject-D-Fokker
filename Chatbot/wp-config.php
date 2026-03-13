<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache


if (!empty($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] === "https") {
    $_SERVER["HTTPS"] = "on";
}

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
define( 'DB_NAME', 'admin_uwmatrasnieuw' );

/** Database username */
define( 'DB_USER', 'admin_uwmatrasnieuw' );

/** Database password */
define( 'DB_PASSWORD', 'img$H4218' );

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
define( 'AUTH_KEY',          '6Ea,N} P~t&#qwVVbCR1hxyP$%JaDIut0[,C=Jj6ck||(1^mjPrU>KQ ;*,@L`k}' );
define( 'SECURE_AUTH_KEY',   'igN*y.rDq=O1b|6,wt :W3YKPKs+&!o4p,exQRC{bo?Wzqmx6B>q]BrsLos5- nl' );
define( 'LOGGED_IN_KEY',     'Z@Jq:2ou$kSjL68HY#G%wVn^%E]OpeLS}=~@U-8}FnXrxFg1P/*>[u/WG*kgfw#-' );
define( 'NONCE_KEY',         '((r{re_(EYs+zanh#l0u.K<x]{>(cBA[yVWvw(*2zNP8}BCrqD0S-hRMc!LCkdo7' );
define( 'AUTH_SALT',         'wg==?;UaL?,?zB?V4n}&]L^wbVkQ-qnc(4(9uk=UENATQdZi)q8=s%)gAwcK<7<n' );
define( 'SECURE_AUTH_SALT',  '3$^6|*pEBWqvoE<^RZPGl.57E7`ih}T`|^~UD!!u/ap_Fe][Ak~V+#80d}M*Qo8I' );
define( 'LOGGED_IN_SALT',    'GZibz*~I5QA4gsM4lq?I/&+,xnk84hr?9zsG(=gE+rM*;v~8DX4q>Gl]0g$S+nT=' );
define( 'NONCE_SALT',        'X>IG5GCW))orRz:/<buDE ck[C2=$IX.Yl:LOUMD!kRWguK_=<0gSD#4hs-,v[He' );
define( 'WP_CACHE_KEY_SALT', 'autorijschoolvleuten.nl:' );


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
define('GEMINI_API_KEY', '**************************');
define( 'WP_REDIS_HOST', '10.9.52.50' );
define( 'WP_REDIS_PORT', 6379 );
define( 'WP_REDIS_PASSWORD', 'SyhPRtN7QTka4oDkuBRtjEhqijCYKoAi' );
define( 'WP_REDIS_DATABASE', 0 );
define( 'DISALLOW_FILE_EDIT', false );
define( 'FS_METHOD', 'direct' );
define( 'DISABLE_WP_CRON', true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'FORCE_SSL_ADMIN', true );
define( 'ADVMO_CLOUDFLARE_R2_KEY', 'de082209310c9259018d49db1c4ac98e' );
define( 'ADVMO_CLOUDFLARE_R2_SECRET', 'b77e68f6e784b1dca9282365502fb1df26eb68e0cec7f2b6fd59162808c2471f' );
define( 'ADVMO_CLOUDFLARE_R2_BUCKET', 'autorijschoolvleuten' );
define( 'ADVMO_CLOUDFLARE_R2_DOMAIN', 'https://media.autorijschoolvleuten.nl' );
define( 'ADVMO_CLOUDFLARE_R2_ENDPOINT', 'https://a07afad8b13a6a83c7affdd1a898d24a.eu.r2.cloudflarestorage.com' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
