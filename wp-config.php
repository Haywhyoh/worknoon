<?php
define( 'WP_CACHE', true );



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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', "worknoon");

/** Database username */
define('DB_USER', "haywhyoh");

/** Database password */
define('DB_PASSWORD', "Mydreams");

/** Database hostname */
define('DB_HOST', "localhost");

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
define('AUTH_KEY', '566afdadf596c6f89709cf6d4de46fbc24cb816ef96de0dea914849422bd11fb');
define('SECURE_AUTH_KEY', '93f69e92803ea3391aa2b81b6efaff0d1b08f29cd02d05c88c4767bf454d077e');
define('LOGGED_IN_KEY', '374ac1c2942d63306fe2615a8a489ba82d9daa4f76eba4d1723fe5474b26407e');
define('NONCE_KEY', '443b5c2e13c33303105fcd3e5509eadef5f81449cdc593fa3d8ec912e55eef3a');
define('AUTH_SALT', '428ce959392d89810a4cad44dcb570ae3cb29b37ea4891b6fdbc07a1f8703aaa');
define('SECURE_AUTH_SALT', '59aa9047c5678894f826f3a8d0fc0ecdc038314eb9ef926c6fd4b34bc7b50dbf');
define('LOGGED_IN_SALT', '85446d8e8d7175c9d37003b6670c746b5d55cd7eaaccc64959de22671ba5661f');
define('NONCE_SALT', '196e9f1f1dbfd2b5d5e8af9bb11e3217f3343e300aa815b76474486211b81af2');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'g2t_';
define( 'WP_CRON_LOCK_TIMEOUT', 120 );
define( 'AUTOSAVE_INTERVAL', 300 );
define( 'WP_POST_REVISIONS', 20 );
define( 'EMPTY_TRASH_DAYS', 7 );
define( 'WP_AUTO_UPDATE_CORE', true );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



define( 'WP_SITEURL', 'http://localhost/worknoon/' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
