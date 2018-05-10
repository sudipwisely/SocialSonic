<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $mongoDb, $process, $lf;
$lf->write("This customer-followers-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

$followerSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "followers_cursor` GROUP BY `User_ID` ORDER BY `Row_ID` DESC");
if ( $followerSQL ) {
	if ( mysql_num_rows($followerSQL) > 0 ) {
		while ( $AutoRS_result = mysql_fetch_assoc($followerSQL) ) {
			$user_id = $AutoRS_result['User_ID'];
			echo "User ID: " . $user_id . "\n";
			$customer = $helper->getCustomerById($user_id);
			if ( $customer['Cust_Server'] == CUR_SERVER ) {
				$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
				$users = array();
				$user_document = $mongoDb->user_followers->find(array("user_id" => $customer['Cust_ID']));
				if ( $user_document->count() > 0 ) {
					foreach ( $user_document as $document ) {
						$users[] = $document;
					}
				}
				$followers = $twitteroauth->get('followers/list', array('user_id' => $customer['Cust_Twitter_ID'], 'cursor' => $AutoRS_result['Cursor_ID']));
				if ( !isset($followers->errors) ) {
					foreach ( $followers->users as $follower ) {
						$CheckDup = $mongoDb->user_followers->findOne(array('id_str' => $follower->id_str, 'user_id' => $user_id ));
						if ( empty($CheckDup) ) {
							$users		= array(
								"user_id"          => $customer['Cust_ID'],
								"user_screen_name" => $customer['Cust_Screen_Name'],
								"id_str"  		   => $follower->id_str, 
								"name"   		   => $follower->name, 
								"screen_name" 	   => $follower->screen_name, 
								"location"  	   => $follower->location, 
								"description" 	   => $follower->description, 
								"website"  		   => $follower->url, 
								"profile_pic" 	   => $follower->profile_image_url,
								"followers"        => $follower->followers_count, 
								"following"        => $follower->friends_count,
							);
							$insertExtFllwers = $mongoDb->user_followers->insert($users);
							echo "<pre>"; print_r($users); echo "</pre>\n";
						}	
						$users = '';
					}
					if ( $followers->next_cursor_str == 0 ) {
						$newcursor = -1;
					} else {
						$newcursor = $followers->next_cursor_str;
					}
					$cursorSQL = mysql_query("UPDATE `" . DB_PREFIX . "followers_cursor` SET `Cursor_ID` = '" . $newcursor . "' WHERE `Row_ID` = " . $AutoRS_result['Row_ID']);
					$newcursor = '';
				} else {
					echo "<pre>"; print_r($followers); echo "</pre>\n";
				}
			}
		}
	} else {
		echo "No User Exist\n";
	}
}

$lf->write("This customer-followers-cron.php ends at " . date("Y-m-d H:i:s") . "\n");