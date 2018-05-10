<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php"); 

$custsql = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers`");
$i = 1;
while ( $custdata = mysql_fetch_assoc($custsql) ) {
	mysql_query("INSERT INTO `" . DB_PREFIX . "card_count`(`Cust_ID`, `Keyword_Pipeline`, `Category_Pipeline`, `Prospects`, `Followers`, `Show_Websites`, `Unfollow`, `Date_Time`) VALUES (" . $custdata['Cust_ID'] . ", 0, 0, 0, 0, 0, 0, NOW())"); echo $i . ". INSERTED<br />";
	$i++;
}