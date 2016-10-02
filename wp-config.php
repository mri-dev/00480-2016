<?php


// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', '02vbvnfny6mz');

/** MySQL database username */
define('DB_USER', '02vbvnfny6mz');

/** MySQL database password */
define('DB_PASSWORD', 'srt7kigwopgk');

/** MySQL hostname */
define('DB_HOST', 'mariadb55.websupport.sk:3310');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('AUTH_KEY',         '1G.F)&PHYu*%xvBK^W[pH,3e M2Z3uYi=,)k|-^I5%|E(O(-^f ?=)X8MQ^9,..7');
define('SECURE_AUTH_KEY',  'O^!xT8ceMSc|w<IKhdDS~g|+{O-Hq[H|!|_%j6]>e]y@TR<pLXxp}>:eb3Q!-Rub');
define('LOGGED_IN_KEY',    ')X+eDd|x:f)w;dH)DBs|74R+h%VpX~Q+_LqsTH6UJw,ajy<x9dnd5$6oiT1lg/5y');
define('NONCE_KEY',        'RL3.::E:11.1XvvV.@g~/2uko?R#;2O#e,WAPaDp,QpG4WbqbqC.>JTD3s#N)]R[');
define('AUTH_SALT',        'L~l|q)^NqK+(-r_*VPG+M}+HHYXM-~NN2f/n~$G{jE:Jgp!gl<x+MwBk-,^(J8||');
define('SECURE_AUTH_SALT', '?y|3 !C|_)9k,@@Y(+Tqb6|)#>|yKBxx;.?u+v>PlV5QS|1XhlrW]t%Kjn}`-O2<');
define('LOGGED_IN_SALT',   'lf{X3-|jJ#j*hw&MOc8||h0z@-9ca1;-V0gIlyNkBH=0_q&`jnwi#{ %-vl>fAf{');
define('NONCE_SALT',       'hrM<J]CuVn,Wz}V|#~<^2UiB2c<@8rRRxC|e`dgGBQlhWk^-81{aFoYG|c0933o,');


$table_prefix = 'xhjohn';



define('WP_SITEURL','http://' . ($_SERVER['HTTP_X_WP_TEMPORARY'] ? $_SERVER['HTTP_X_WP_TEMPORARY'] : 'viasaletravel.ideafontana.eu'));
define('WP_HOME', 'http://' . ($_SERVER['HTTP_X_WP_TEMPORARY'] ? $_SERVER['HTTP_X_WP_TEMPORARY'] : 'viasaletravel.ideafontana.eu'));
define('WP_MEMORY_LIMIT', '256M');
define('WPLANG', 'hu_HU');



/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
