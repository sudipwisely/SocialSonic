<?php require(__DIR__ . '/config/config.php'); 
global $mongoDb;


$users = array();
$keyword_document = $mongoDb->prospect_keywords->find(array("search_user_id" => $_SESSION['Cust_ID']));
foreach($keyword_document as $document){
	$user[] = $document['user_id']; 
}

$keyword_document = $mongoDb->prospect_influencers->find(array("search_user_id" => $_SESSION['Cust_ID']));
foreach($keyword_document as $document){
	$user[] = $document['user_id']; 
}

$i = 0;
$unfollowSqlLimit = mysql_query("SELECT * FROM `" . DB_PREFIX . "nurtureship` WHERE `user_id` = '" . $_SESSION['Cust_ID'] . "'");
if($unfollowSqlLimit){
	if(mysql_num_rows($unfollowSqlLimit) > 0){
		while($resultset = mysql_fetch_assoc($unfollowSqlLimit)){
			if (!in_array($resultset['twitter_user_id'], $user))
			{
			  $deleteQuery = mysql_query("DELETE FROM `" . DB_PREFIX . "nurtureship` WHERE `user_id` = '" . $_SESSION['Cust_ID'] . "' AND `twitter_user_id` = '".$resultset['twitter_user_id']."'");
			}
		}
		
	}
}