<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $mongoDb, $process, $lf;
$lf->write("This influencer-follower-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

$influencer_data = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Influencers` != '' GROUP BY `Cust_ID` ORDER BY `Prospect_ID` DESC");
if ( $influencer_data ) {
	if ( mysql_num_rows($influencer_data) > 0 ) {
		while ( $influencer_data_result = mysql_fetch_assoc($influencer_data) ) {
			$user_id = $influencer_data_result['Cust_ID'];
			$customer = $helper->getCustomerById($user_id);
			if ( $customer['Cust_Server'] == CUR_SERVER ) {
				$dayAfterTomrrow = strtotime($customer['Cust_Last_Login_Time']) + 86400;
				$nowTime = strtotime(date('Y-m-d H:i:s'));
				if ( $dayAfterTomrrow > $nowTime ) {
					echo "Active User ID: " . $user_id . "\n";
					$prospect_id = $influencer_data_result['Prospect_ID'];
					$sql_prospect = "SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Prospect_ID` = $prospect_id";
					$result_prospect = mysql_query($sql_prospect);
					$row_prospect = mysql_fetch_assoc($result_prospect);
					$influencer_string = $row_prospect['Influencers'];
					$ary_influencer_string = explode(",", $influencer_string);
					$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
					foreach ( $ary_influencer_string as $influencer_id ) {
						$cursorSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers_cursor` WHERE `Cust_ID` = ".$user_id." AND `Influencer_ID` = '" . $influencer_id . "'");
						if ( $cursorSQL ) {
							if ( mysql_num_rows($cursorSQL) > 0 ) {
								$cursor_result = mysql_fetch_assoc($cursorSQL);
								$cursor = $cursor_result['Cursor_Point'];
							}
						}
						$followers = $twitteroauth->get('followers/list', array('user_id' => $influencer_id, 'cursor' => $cursor));
						if ( !isset($followers->errors) ) {
							foreach ( $followers->users as $follower ) {
								$CheckDup = $mongoDb->prospect_influencers->findOne(array('search_user_id' => $user_id, 'user_id' => $follower->id_str));
								if ( empty($CheckDup) ) {
									if ( !empty($follower->description) ) {
										$insertData = array(
											'prospect_id'    => $prospect_id,
											'search_user_id' => $user_id,
											'user_id'   	 => $follower->id_str,
											'full_name'   	 => $follower->name,
											'screen_name'    => $follower->screen_name,
											'location'       => $follower->location,
											'description'    => $follower->description,
											'website'        => $follower->url,
											'profile_image'  => $follower->profile_image_url,
											'followers'      => $follower->followers_count,
											'following'      => $follower->friends_count,
											'influncer_id'   => $influencer_id,
											'status'   		 => 'pending'
										);
										$mongoDb->prospect_influencers->insert($insertData);
									}
								}
							}
							if ( $followers->next_cursor_str == 0 ) {
								$newcursor = -1;
							} else {
								$newcursor = $followers->next_cursor_str;
							}
							$sql_influncer_update = "UPDATE `" . DB_PREFIX . "influencers_cursor` SET `Cursor_Point` = '" . $newcursor . "' WHERE `Cust_ID` = $user_id AND `Influencer_ID` = '" . $influencer_id . "'";
							$sql_influncer_update_result = mysql_query($sql_influncer_update);
							$cursor = '';
							$newcursor = '';
						}
					}
				} else {
					echo "Inactive User ID: " . $user_id . "\n";
					echo "This user is not login within last 48hours.\n";
				}
			}
		}
	} else {
		echo "No Data found\n";
	}
} else {
	echo "DB ERROR\n";
}

$lf->write("This influencer-follower-cron.php ends at " . date("Y-m-d H:i:s") . "\n");