<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

/** MySQL settings */

$is_prod = getenv('WP_ENV') === 'production';

if ($is_prod) {
	/** Retrieve from GCP's Secret Manager */
	require_once __DIR__.'/vendor/autoload.php';

	$client = new Google\Cloud\SecretManager\V1beta1\SecretManagerServiceClient();
	$projectId = getenv('GCP_PROJECT_ID');
	$versionName = $client->secretVersionName($projectId, 'prod-wp-website', 'latest');
	$response = $client->accessSecretVersion($versionName);
	
	$dbSetup = json_decode($response->getPayload()->getData());
	define('DB_HOST', $dbSetup->dbHost);
	define('DB_NAME', $dbSetup->dbName);
	define('DB_USER', $dbSetup->dbUser);
	define('DB_PASSWORD', $dbSetup->dbPassword);
} else {
	define('WP_HOME', "http://127.0.0.1:".getenv('PORT'));
	define('WP_SITEURL', "http://127.0.0.1:".getenv('PORT'));
	define('DB_HOST', getenv('WP_DB_HOST'));
	define('DB_NAME', getenv('WP_DB_NAME'));
	define('DB_USER', getenv('WP_DB_USER'));
	define('DB_PASSWORD', getenv('WP_DB_PASSWORD'));
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/**
 * Reverse proxy configuration
 * 
 * @see https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
 */
if($is_prod) {
	define('FORCE_SSL_ADMIN', true);
	// in some setups HTTP_X_FORWARDED_PROTO might contain 
	// a comma-separated list e.g. http,https
	// so check for https existence
	if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
	$_SERVER['HTTPS']='on';
}

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
/** Last changed in November 2020 - by sergio.garcia */
define('AUTH_KEY',         'z qO4Zfk ojhn*3ze+8Y/i~xD@6RGj[6<k|P}C+qx|I|;0dgB??%Xe5};~JH-S]b');
define('SECURE_AUTH_KEY',  'vu]h$c@Ra{JLZ1e!,x-7KP^qk+HPhO=J42Ft30R,?q Den<D7b.ZR]MNm]e*&go^');
define('LOGGED_IN_KEY',    'nADC2{W_Ei#yF.MMWP(~Nv=iBYKX9nE6!aWH{mQYB=yc*b}]:x2A6s|xEZY]V+8w');
define('NONCE_KEY',        '2i737-:Nw~~K[u81)UDs%><249;Mj(<(|dDEyeM;(}N_nbG8Rex{;km1Ax<g)H|P');
define('AUTH_SALT',        '/vhfv;eQN~FX}u=B13~8^-Z$_1DpibbW#j&!Lglw*b;dsjCAU+H[72O-2FSdDOtt');
define('SECURE_AUTH_SALT', 'i~W,w-)/96/-cbmkS|-;<m$M}s)O|nWHT=?2sZ#Swm;Z7}8283J|v0=$6]F%]F&`');
define('LOGGED_IN_SALT',   'eVNGW8NtF!dY@!Mo@-KdNC}|+jd=8A-n>=cg}?YLjP(4.A+x}1V&Z,&~|jRyDN+P');
define('NONCE_SALT',       'h8HT#OSL[qdC$}=}Jb=FxeoMN25{[WttGa(871r5PT23|Y]{i>Z!h9w_e}M{>}+c');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'aiesec_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
/** Debugging enabled for non-production environments */
define('WP_DEBUG', getenv('WP_DEBUG') === 'true');
define('WP_DEBUG_LOG', '/dev/stdout');

/** Enable W3 Total Cache */
define('WP_CACHE', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
