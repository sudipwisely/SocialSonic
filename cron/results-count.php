<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php"); 

global $mongoDb;
$Cust_IDs = explode(",", $_GET['Cust_ID']);

foreach ( $Cust_IDs as $Cust_ID ) {
	$keyword_count = $mongoDb->prospect_keywords->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'pending'));
	if ( $keyword_count->count() > 0 ) {
		$countCheckSql = mysql_query("SELECT * FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $Cust_ID . " LIMIT 0, 1");	
		if ( $countCheckSql ) {
			if ( mysql_num_rows($countCheckSql) == 0 ) {
				mysql_query("INSERT INTO `" . DB_PREFIX . "card_count`(`Cust_ID`, `Keyword_Pipeline`, `Date_Time`) VALUES (" . $Cust_ID . ", " . $keyword_count->count() . ", NOW())");
				echo $keyword_count->count() . " INSERTED\n";
			} else {
				mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = " . $keyword_count->count() . ", `Date_Time` = NOW() WHERE `Cust_ID` = " . $Cust_ID);
				echo $keyword_count->count() . " UPDATED\n";
			}
		} else {
			echo "DB ERROR\n";
		}
	} else {
		echo "NO RESULTS\n";
	}
}