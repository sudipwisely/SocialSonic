<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $mongoDb;

if ( !isset($_REQUEST['Cat']) ) {
	header('location:' . SITE_URL . 'admin/influencer-categories/');
	return false;
}

$Cat_ID = $_REQUEST['Cat'];
$InflunceSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = $Cat_ID");
if ( $InflunceSQL ) {
	while ( $InfluenceData = mysql_fetch_assoc($InflunceSQL) ) {
		$influncer_twitter_id = $InfluenceData['influncer_twitter_id'];
		$mongoDb->influence_followers->remove(array( 'influncer_id' => $influncer_twitter_id));
	}
}

$RemoveSQL = mysql_query("DELETE FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = $Cat_ID");
$InfluencerSQL = mysql_query("DELETE FROM `" . DB_PREFIX . "influencer_categories` WHERE `twscrapp_category_id` = $Cat_ID");
$_SESSION['delCat'] = 'Category is successfully deleted.';
header('location:' . SITE_URL . 'admin/influencer-categories/');