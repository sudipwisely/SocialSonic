<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $lf;
$already_dm_user = array();
$comment_arr = array();
$lf->write("This dm-message-stop-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

$FollowUsr = mysql_query("SELECT `user_id` FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'confirm' GROUP BY `user_id`");
if ( $FollowUsr ) {
	if ( mysql_num_rows($FollowUsr) > 0 ) {
		while ( $FollowUsr_result = mysql_fetch_assoc($FollowUsr) ) {
			$user_id = $FollowUsr_result['user_id'];
			echo "User ID: " . $user_id . "\n";
			$customer = $helper->getCustomerById($user_id);
			if ( $customer['Cust_Server'] == CUR_SERVER ) {
				$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
				$get_dm_message_user = $twitteroauth->get('direct_messages', array('since_id' => 240136858829479935)); 
				if ( !isset($get_dm_message_user->errors) ) {
					for($i = 0; $i<count($get_dm_message_user); $i++){
						$already_dm_user[] = $get_dm_message_user[$i]->sender->id_str;
					}
				}
				$InSQl = "SELECT * FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'confirm' AND `user_id`='" . $user_id . "' AND `dm_send_status` = '0' ORDER BY `user_id` LIMIT 0, 6"; 
				$InRS = mysql_query($InSQl);
				if ( $InRS ) {
					if ( mysql_num_rows($InRS) > 0 ) {
						while ( $InRS_result = mysql_fetch_assoc($InRS) ) {
							$follower_name = $InRS_result['twitter_user_name'];
							$follower_id = $InRS_result['twitter_user_id'];
							if ( !isset($get_dm_message_user->errors) ) {
								if (in_array($follower_id, $already_dm_user)) {
									$sql1 = "UPDATE `" . DB_PREFIX . "nurtureship` SET `dm_send_status`= '1' WHERE `user_id` = '" . $user_id . "' AND `twitter_user_name` = '" . $follower_name . "'";
									mysql_query($sql1);
									echo $follower_name . " dm status updated\n";
								}
							} else {
								echo "<pre>"; print_r($get_dm_message_user); echo "</pre>\n";	
							}
						}
					}
				}
			}
			echo "-------------------------------------------------------------------------\n";
		}
	}
}

$lf->write("This dm-message-stop-cron.php ends at " . date("Y-m-d H:i:s") . "\n");