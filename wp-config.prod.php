<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'alunathe_psh');

/** MySQL database username */
define('DB_USER', 'alunathe_psh');

/** MySQL database password */
define('DB_PASSWORD', ')rO}X3Ft3x?m');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'S3f,^AC1ddzj8HJ,fL1J~s-}H+AMQNd|9H))dG,LcH-!-(:)7~VPiv{1wH=u/z$I');
define('SECURE_AUTH_KEY',  '4w-5{49Zq6J%~)}*]THo9n#+aF%YN!^FECJ0E!9wE+Mj:+,0^=+AMe.Wxw06OI:1');
define('LOGGED_IN_KEY',    'PvRM9y}|K}VY~R[C`A{-}^d1M,g`klJs{huO3~z$j{WmZ-f=~nz4o:zPu)S`=Fn]');
define('NONCE_KEY',        '>xac|`*Q-:s-ug,C~!8Yn*f tk&lk3-&l/cSdPZqr3@|=<LPdF 3]{/-u$w,D|W(');
define('AUTH_SALT',        'D+^n,wN0TjRj=7XJ7DPDhi({[SA7]y=TD@@-Ps+Hcn6]r2Qf %d84Ceyg=TN}h`X');
define('SECURE_AUTH_SALT', 's8qgS4|@p;S{*{4H&zVOCuy;ji`-}O$e0QIGbn-.p%%dOkX.<{</]%!W2;+OVDe3');
define('LOGGED_IN_SALT',   '=E)Y9B6I-Fl13xBiE/G1{jR|Q,8qP}`<kQ9?zk7m/`b8%=X>}H<g^.,Zx|(6(2Qd');
define('NONCE_SALT',       'Tx[<pvFn%UmA)g+rtC]7+G6/ +n32lWq1^[wb]x+Px-^@ 9J<%udwh4C<7{V+QP9');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
