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

/** MySQL settings (via GCP's Secret Manager) */
require_once __DIR__.'/vendor/autoload.php';
use Google\Cloud\SecretManager\V1beta1\SecretManagerServiceClient;

$client = new SecretManagerServiceClient();
$projectId = getenv('GCP_PROJECT_ID');
$versionName = $client->secretVersionName($projectId, 'WP_DB_CREDENTIALS', 'latest');
$response = $client->accessSecretVersion($versionName);

$dbSetup = json_decode($response->getPayload()->getData());
define('DB_HOST', $dbSetup->DB_HOST);
define('DB_NAME', $dbSetup->DB_NAME);
define('DB_USER', $dbSetup->DB_USER);
define('DB_PASSWORD', $dbSetup->DB_PASSWORD);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');
/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Reverse proxy configuration
 * 
 * @see https://wordpress.org/support/article/administration-over-ssl/#using-a-reverse-proxy
 */
define('FORCE_SSL_ADMIN', true);
// in some setups HTTP_X_FORWARDED_PROTO might contain 
// a comma-separated list e.g. http,https
// so check for https existence
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false)
$_SERVER['HTTPS']='on';

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
/** Last changed in March 2020 - by sergio.garcia */
define('AUTH_KEY',         '-ACo7y%v5ta4=G5#kIOSV;7Z+v&ub!EddXq.jWf;Ib|.,3:^L<8jh,I|G;Oz)k)6');
define('SECURE_AUTH_KEY',  'DumXU/-d g(I tI-rL-0Q@>c-{Z|E+FqxC /5Dg/|,#bZ{4lu}v-{]Hjl3e% DBZ');
define('LOGGED_IN_KEY',    '-Y|>qsv0CVsn}):_)%w-%w/EAtM+N8L>EoG8lrBJ|%u-x8l~d6i|+QMf#6eBr:@A');
define('NONCE_KEY',        'c%<[-~S=wk7AAiqS(Fb<6.nn-5%i;U `-?+b+J}c:n#F=]2Z;^FE1Y6J598lDF0=');
define('AUTH_SALT',        'T-sc9vox(?O=MZ`&l&&{vlx(y{mMO4A*ce%8@b.%;2@ePkEdpf|NH5 bu]eM}1){');
define('SECURE_AUTH_SALT', 'DnqwFU:L-/[<pn.PFT?!_U(|_WiysP_Y-Mjqc+pPl7G(hDi!V[+}mb[N5b;YY{-^');
define('LOGGED_IN_SALT',   '8{E:cg_G2e.S2)GL?mY;j7x+q#?`iE^tSU%whp:,Xpy<xc8t_dLx9x-1B Dx$m$V');
define('NONCE_SALT',       'k,lU|_O+7$!=X6X%U>z-z%lz5i^a.cKwp%|jVA;[_d~pO[i(;mIWCv+(bA Jh+PG');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'test_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
/** Debugging enabled for non-production environments */
define('WP_DEBUG', getenv('WP_ENV') !== 'PROD');
define('WP_DEBUG_LOG', '/dev/stdout');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
