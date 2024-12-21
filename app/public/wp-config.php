<?php
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
define( 'AUTH_KEY',          '$2&4B,]M8xS^mh4:I+NlDt_)yHjib3^;siMYtGGT(Rvde89=w;Ir^m`$oD,Nw$Gk' );
define( 'SECURE_AUTH_KEY',   '*qZ|< ]R6tVm,y9/VcY9{n=+!1>bh6!i4B&([h5mO$c.n-EluDKsGnAT@trU%fp)' );
define( 'LOGGED_IN_KEY',     'I1Hbw!xK#PN6 u_[UW@1<RR&W89C9zXATKWn`%dG9lY,QEXv,Tv9ZKvvl:4Q{Oq`' );
define( 'NONCE_KEY',         'g+QlWp@%E7|&q+{2?=dUTXJd`>$2`5,7:2ykp=Ch|D<w9Pj>|Q.V ]Ks9h]3n1%v' );
define( 'AUTH_SALT',         '#haM7azDiwh/$k.JPrFg^m&quC]%vT{WF;VFaw6 q<P@:o.oKiU<>X/6&{>ZR0wG' );
define( 'SECURE_AUTH_SALT',  'zRg4a1tlG=5uTMsDSeC8yp#W&ivg~t9>HY=VCp#DkGv`<@j=OIwccm4-+rn9y6/E' );
define( 'LOGGED_IN_SALT',    'sDWk6Vjl8(aEl0xw{=Q|2fjUr>Dp_6LR4psC4SE]x`H5}H9IY#=i/>:w2r`{SkG_' );
define( 'NONCE_SALT',        'RFTGJ2eR0$^;z&g`Cy-pmPDef1idz^UKJ0p^Z?(>%n7FKVr7]),vV;y@moH}o+!e' );
define( 'WP_CACHE_KEY_SALT', '5)U0a.9w{X<|uw_!q),w+JbuCPI^>;~Q]i,z.9TU2,L-Qd[e C/W8{j~T2~{UN[B' );


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
