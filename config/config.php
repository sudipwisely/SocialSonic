<?php 

// Configuration file of the App */

ini_set("display_errors", 0);
$server = $_SERVER['HTTP_HOST'];
switch ($server) {
	case 'localhost':
		$env = 'development';
		break;
	default:
		$env = 'production';
		break;
}

define('ENVIRONMENT', $env);
require_once(dirname(__FILE__) . '/' . ENVIRONMENT . "/declare.php");

header('Access-Control-Allow-Origin: *');

require_once(dirname(__FILE__) . "/sessions.php");
require_once(dirname(__FILE__) . "/mail-process.php");
require_once(dirname(__FILE__) . "/socialsonic.php");
require_once(dirname(__FILE__) . "/twitter/twitteroauth.php");
require_once(dirname(__FILE__) . "/mongodb.php");
require_once(dirname(__FILE__) . "/logfile.php");

date_default_timezone_set('UTC');

$scon = mysql_connect(DB_SERVER_NAME, DB_USER_NAME, DB_PASSWORD) or die('DB Error');
$sdb = mysql_select_db(DB_NAME);

if ( isset($_SESSION['Cust_ID']) ) {
	require_once(dirname(dirname(__FILE__)) . "/twitter-process.php");
	require_once(dirname(__FILE__) . "/acTwitterConversation.php");
	$twConv = new acTwitterConversation;
}

if ( defined('ADMIN') ) {
	require_once(dirname(dirname(__FILE__)) . "/admin/admin-process.php");
}
