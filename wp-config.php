<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ohrganic');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '9]-TY(}|b[Vr6q-oh:Fo8hQ~PC>$2`vO)R6-=,F/d]+{N@oeGceO/.uMr$+Zu(W2');
define('SECURE_AUTH_KEY',  '}Vg[ZH(wt[CM[QA2%T`ko#H1Q-fFa@:`C2P+aSb45*oSpC=-R=GLW.<-x{sHuU>h');
define('LOGGED_IN_KEY',    '0^g$MW&!wU0+yg`m~PyqIs)-G~|;uR,EZ{?X$#eS/m+mV:nQH_#guwh-G)9aLo@&');
define('NONCE_KEY',        'D|`mvW8N7j)]F?Cx>sW[}q[`NcPN *gl>Y f#%2&!+O c^Y4ibpHo|KmX2&`/-^e');
define('AUTH_SALT',        'J#`oPpJj6N{hwkU@1KAL5UyCq9<:fe]7v<]Oqk#eE}6Z5J`~/5>QA+^VRC-QDshF');
define('SECURE_AUTH_SALT', '!-/vQX$}iF`|rg7s`|KV(-@4veS@QDH]L+FrdZ6k*AqJ88`|5EpE[+Y~S4KOif.J');
define('LOGGED_IN_SALT',   '1& -//RGN[$@fkK!;sw`{Kq/ZT~2d+8Z47m|)%dNl;uer0<V|1Fd-ZGL#8At{A+z');
define('NONCE_SALT',       'RIi4~+7v@gR{?eQvmJ9TxNCQm~iY+n}}U)kx<H#dWPCxBP*@/FHe28c,StahVKXz');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
