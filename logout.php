<?php /*! Logout of this Application */

require_once(dirname(__FILE__) . '/config/config.php');

$cookie_time = (3600 * 24 * 30);
setcookie('__ssUn', '', time() - $cookie_time, '/');
setcookie('__ssPs', '', time() - $cookie_time, '/');

session_unset();

if ( SITE_URL == SERVER1_URL ) {
	header('location:' . SERVER1_URL . "welcome/");
} else {
	header('location:' . SERVER1_URL . "logout/");
}

exit;