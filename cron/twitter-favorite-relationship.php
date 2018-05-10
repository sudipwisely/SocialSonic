<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $lf;
$lf->write("This twitter-favorite-relationship.php starts at " . date("Y-m-d H:i:s") . "\n");

function tweet_favourite_cron() {
	global $helper;
	$FavUsr = mysql_query("select `user_id` FROM `" . DB_PREFIX . "favourite` WHERE `fav_status` = 'pending' group by `user_id`");
	if ( $FavUsr ) {
		if ( mysql_num_rows($FavUsr) > 0 ) {
			while ( $FabUsrRS_result = mysql_fetch_assoc($FavUsr) ) {
				$user_id = $FabUsrRS_result['user_id'];
				echo "User ID: " . $user_id . "\n";
				$customer = $helper->getCustomerById($user_id);
				if ( $customer['Cust_Server'] == CUR_SERVER ) {
					$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
					$FavSQl = "select `id`,`tweet_id`,`tweet_screen_name`,`fav_status` FROM `" . DB_PREFIX . "favourite` WHERE `fav_status` = 'pending' AND `user_id`='".$user_id."' ORDER BY `user_id` LIMIT 0,1";
					$FavRS = mysql_query($FavSQl);
					if ( $FavRS ) {
						if ( mysql_num_rows($FavRS) > 0 ) {
							while ( $FabRS_result = mysql_fetch_assoc($FavRS) ) {
								$fav_tab_id = $FabRS_result['id'];
								$tweet_id = $FabRS_result['tweet_id'];
								$tweet_screen_name = $FabRS_result['tweet_screen_name'];
								$tweet_fav_status = $FabRS_result['fav_status'];
								$tweet_fav_action = $twitteroauth->post('favorites/create', array('id' => $tweet_id));
								if ( !isset($tweet_fav_action->errors) ) {
									$fav_status_update = mysql_query("UPDATE `" . DB_PREFIX . "favourite` SET `fav_status`='complete' WHERE `id` = " . $fav_tab_id);
									echo "Favourite successfully!\n";
								} else {
									if ($tweet_fav_action->errors[0]->code == 142 || $tweet_fav_action->errors[0]->code == 50 || $tweet_fav_action->errors[0]->code == 326 || $tweet_fav_action->errors[0]->code == 158 || $tweet_fav_action->errors[0]->code == 160 || $tweet_fav_action->errors[0]->code == 136 || $tweet_fav_action->errors[0]->code == 139 || $tweet_fav_action->errors[0]->code == 144 ) {
										$fav_status_update = mysql_query("UPDATE `" . DB_PREFIX . "favourite` SET `fav_status`='complete' WHERE `id` = " . $fav_tab_id);
									}
									echo "<pre>"; print_r($tweet_fav_action); echo "</pre>\n";
								}
							}
						} else {
							echo "Favourite Data Complete\n";
						}
					} else {
						echo "No favourite Data Exist\n";
					}
				}
			}
		} else {
			echo "No User EXIST\n";
		}
	}
}

function initiate_nurter_cron() {
	global $helper;
	$initUsr = "SELECT `user_id` FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'pending' GROUP BY `user_id`";
	$initUsrRS = mysql_query($initUsr);
	if ( $initUsrRS ) {
		if ( mysql_num_rows($initUsrRS) > 0 ) {
			while ( $initUsrRS_result = mysql_fetch_assoc($initUsrRS) ) {
				$user_id = $initUsrRS_result['user_id'];
				echo "User ID: " . $user_id . "\n";
				$customer = $helper->getCustomerById($user_id);
				if ( $customer['Cust_Server'] != 3 ) {
					$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
					$InSQl = "select `id`,`twitter_user_id`,`twitter_user_name`,`relationship_status` FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'pending' AND `user_id`='".$user_id."' ORDER BY `user_id` LIMIT 0,1";
					$InRS = mysql_query($InSQl);
					if ( $InRS ) {
						if ( mysql_num_rows($InRS) > 0 ) {
							while ( $InRS_result = mysql_fetch_assoc($InRS) ) {
									$ret = $twitteroauth->post('friendships/create', array('user_id' => $InRS_result['twitter_user_id']));
									if (!isset($ret->errors) ) {
										$update_sql = "UPDATE `" . DB_PREFIX . "nurtureship` SET `relationship_status` = 'oneside_confirm' WHERE `twitter_user_id` = '" . $InRS_result['twitter_user_id'] . "'";
										$update_result = mysql_query($update_sql);
										echo "Following successfully\n";
									} else {
										if($ret->errors[0]->code == 108 || $ret->errors[0]->code == 162 || $ret->errors[0]->code == 142 || $ret->errors[0]->code == 50 || $ret->errors[0]->code == 326 || $ret->errors[0]->code == 158 || $ret->errors[0]->code == 160){
											$update_sql = "UPDATE `" . DB_PREFIX . "nurtureship` SET `relationship_status` = 'error' WHERE `twitter_user_id` = '" . $InRS_result['twitter_user_id'] . "'";
										$update_result = mysql_query($update_sql);
											
										}
										echo "<pre>"; print_r($ret); echo "</pre>";
									}
								}
						} else {
							echo "All Data Initially Nurtured\n";
						}
					}
				}
			}
		} else {
			echo "Favourite Data Complete\n";
		}
	} else {
		echo "No User EXIST\n";
	}
}

tweet_favourite_cron();
echo "------------------------------------------------------------------------------------------------\n";
initiate_nurter_cron();

$lf->write("This twitter-favorite-relationship.php ends at " . date("Y-m-d H:i:s") . "\n");