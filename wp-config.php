<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'mutual-eventos');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'cron');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'TRf.akODvb{aEq*2yVt#1H9v%p%W@_z{:pl]}%=JVN<$f>7ke*S%aCjS{r+G|b_7');
define('SECURE_AUTH_KEY', 'qesLww$s?j.tB89J0UEs>zC tZ9hf2m-qqCwuDf_mWG}i:?*r$*hGji;;/1NMW+g');
define('LOGGED_IN_KEY', ';7_T_N1# .lG&xSH;yu %4X+lz^QTO?N%0T?,(;{P1fJ{F.(!@MD[IE`icyh0r1 ');
define('NONCE_KEY', 'VL:VaeGA^q=-~a,msrjF[y^-LRD X7[M{2[I|h;L2K+j}g_<p~nEeO!rE+,hO59>');
define('AUTH_SALT', ':ABWmX_{0cq{Q1Lz,p=@}la*tJN:1fCEaYLV~jPIan.PC^}nY/s+t|-]X;[S%w>2');
define('SECURE_AUTH_SALT', '<rm&E</LFC]wE{+ah4F*6%p3PE_?R8)F!((!ym9Qz($1+HB:Pc.jO_zs2=l!8DJR');
define('LOGGED_IN_SALT', 'xJ87hCn%vvFG>Q-[Y_mp73.LQhey[[kg420xX~xoF:9~e7M}&M9N{thh{+462)f]');
define('NONCE_SALT', '-R9zO1CD[}4o0NLIQr#RZ3,eQ{IBEI/`(lh;uGH_[=op)Q1-xfEdpLF>cSY|ySmL');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', true);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

