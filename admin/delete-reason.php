<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");
if ( !isset($_REQUEST['Res']) ) {
	header('location:' . SITE_URL . 'admin/cancellation-reason/');
	return false;
}

$RES_ID = $_REQUEST['Res'];
$RemoveSQL = mysql_query("DELETE FROM `" . DB_PREFIX . "unsubscription_reasons` WHERE `Reason_ID` = $RES_ID");
$_SESSION['delCat'] = 'Reason is successfully deleted.';
header('location:' . SITE_URL . 'admin/cancellation-reason/');
?>