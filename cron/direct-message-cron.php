<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $lf;
$already_dm_user = array();
$comment_arr = array();
$lf->write("This direct-message-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

$FollowUsr = mysql_query("SELECT `user_id` FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'confirm' GROUP BY `user_id`");
if ( $FollowUsr ) {
	if ( mysql_num_rows($FollowUsr) > 0 ) {
		while ( $FollowUsr_result = mysql_fetch_assoc($FollowUsr) ) {
			$user_id = $FollowUsr_result['user_id'];
			echo "User ID: " . $user_id . "\n";
			$customer = $helper->getCustomerById($user_id);
			if ( $customer['Cust_Server'] == CUR_SERVER ) {
				$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
				$InSQl = "SELECT * FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'confirm' AND `user_id`='" . $user_id . "' AND `dm_send_status` = '0' ORDER BY `user_id` LIMIT 0, 6"; 
				$InRS = mysql_query($InSQl);
				if ( $InRS ) {
					if ( mysql_num_rows($InRS) > 0 ) {
						while ( $InRS_result = mysql_fetch_assoc($InRS) ) {
							$follower_name = $InRS_result['twitter_user_name'];
							$follower_id = $InRS_result['twitter_user_id'];
							$follower_added_time = $InRS_result['relationship_time'];
							$prospect_id = $InRS_result['prospect_id'];
							$comment_query = "SELECT * from `" . DB_PREFIX . "direct_message` where `prospect_id` = '" . $InRS_result['prospect_id'] . "'";
							$comment_result = mysql_query($comment_query);
							$comment_count = mysql_num_rows($comment_result);
							if ( $comment_count > 0 ) {
								while ( $comment = mysql_fetch_assoc($comment_result) ) {
									$comment_arr[] = $comment['direct_message'];
								}
							}
							echo $customer['Cust_Screen_Name'] . " need to send DM to " . $follower_name . "\n";
							$message_status_sql = mysql_query("SELECT * FROM `" . DB_PREFIX . "dm_status` WHERE `prospect_id` = '" . $prospect_id . "' AND `user_id` = '" . $user_id . "' AND `twitter_screen_id` = '" . $follower_name . "'");
							if ( $message_status_sql ) {
								if ( mysql_num_rows($message_status_sql) > 0 ) {
									$message_status = mysql_fetch_assoc($message_status_sql);
									$threeDaysTime   = date("Y-m-d H:i:s", strtotime($follower_added_time . " +1 days"));
									$sevenDaysTime   = date("Y-m-d H:i:s", strtotime($follower_added_time . " +2 days"));
									$fifteenDaysTime = date("Y-m-d H:i:s", strtotime($follower_added_time . " +3 days"));
									if ( $threeDaysTime < date("Y-m-d H:i:s") ) {
										if ( $message_status['message_status'] == 1 ) {
											if ( isset($comment_arr[1]) ) {
												echo "Message No. 2\n";
												$direct_message_post = $twitteroauth->post('direct_messages/new', array("user_id" => $follower_id, "text" => $comment_arr[1]));
												if ( !isset($direct_message_post->errors) ) {
													echo "DM 2 Posted Successfully to " . $follower_name . "\n";
													if ( $customer['Cust_App_Error'] != '' ) {
														mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error` = '' WHERE `Cust_ID` = " . $customer['Cust_ID']);
													}
													$sql1 = "UPDATE `" . DB_PREFIX . "dm_status` SET `message_status` = 2,`message_created_time` = NOW() WHERE `prospect_id` = '" . $prospect_id . "' AND `user_id` = '" . $user_id . "' AND `twitter_screen_id` = '" . $follower_name . "'";
													mysql_query($sql1);
												} else {
													if ( $direct_message_post->errors[0]->code != 186 && $direct_message_post->errors[0]->code != 150 && $direct_message_post->errors[0]->code != 349 ) {
														mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='".$direct_message_post->errors[0]->message."' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
													}
													if ( $direct_message_post->errors[0]->code == 150 || $direct_message_post->errors[0]->code == 151 || $direct_message_post->errors[0]->code == 349 ) {
														$sql1 = "UPDATE `" . DB_PREFIX . "nurtureship` SET `dm_send_status`= '1' WHERE `user_id` = '" . $user_id . "' AND `twitter_user_name` = '" . $follower_name . "'";
														mysql_query($sql1);
													}
													echo "<pre>"; print_r($direct_message_post->errors); echo "</pre>\n";
												}
											} else {
												echo "2nd Direct Message not set by this user\n";
											}
										}
									} else {
										echo "1 day is still remain to post the second direct message which is created by " . $customer['Cust_Screen_Name'] . " and will post on " . $follower_name . " twitter account\n";
									}
									if ( $sevenDaysTime < date("Y-m-d H:i:s") ) {
										if ( $message_status['message_status'] == 2 ) {
											if ( isset($comment_arr[2]) ) {
												echo "Message No. 3\n";
												$direct_message_post = $twitteroauth->post('direct_messages/new', array("user_id" => $follower_id, "text" => $comment_arr[2]));
												if ( !isset($direct_message_post->errors) ) {
													echo "DM 3 Posted Successfully to " . $follower_name . "\n";
													if ( $customer['Cust_App_Error'] != '' ) {
														mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
													}
													$sql1 = "UPDATE `" . DB_PREFIX . "dm_status` SET `message_status`= 3,`message_created_time`= now() WHERE `prospect_id` = '" . $prospect_id . "' AND `user_id` = '" . $user_id . "' AND `twitter_screen_id` = '" . $follower_name . "'";
													mysql_query($sql1);
												} else {
													if ( $direct_message_post->errors[0]->code != 186 && $direct_message_post->errors[0]->code != 150 && $direct_message_post->errors[0]->code != 349 ) {
														mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='".$direct_message_post->errors[0]->message."' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
													}
													if ( $direct_message_post->errors[0]->code == 150 || $direct_message_post->errors[0]->code == 151 || $direct_message_post->errors[0]->code == 349 ) {
														$sql1 = "UPDATE `" . DB_PREFIX . "nurtureship` SET `dm_send_status`= '1' WHERE `user_id` = '" . $user_id . "' AND `twitter_user_name` = '" . $follower_name . "'";
														mysql_query($sql1);
													}
													echo "<pre>"; print_r($direct_message_post->errors); echo "</pre>\n";
												}
											} else {
												echo "3rd Direct Message not set by this user\n";
											}
										}
									} else {
										echo "2 days is still remain to post the third direct message which is created by " . $customer['Cust_Screen_Name'] . " and will post on " . $follower_name . " twitter account\n";
									}
									if ( $fifteenDaysTime < date("Y-m-d H:i:s") ) {
										if ( $message_status['message_status'] == 3 ) {
											if ( isset($comment_arr[3]) ) {
												echo "Message No. 4\n";
												$direct_message_post = $twitteroauth->post('direct_messages/new', array("user_id" => $follower_id, "text" => $comment_arr[3]));
												if ( !isset($direct_message_post->errors) ) {
													echo "DM 4 Posted Successfully to " . $follower_name . "\n";
													if ( $customer['Cust_App_Error'] != '' ) {
														mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
													}
													$sql1 = "UPDATE `" . DB_PREFIX . "dm_status` SET `message_status`= 4,`message_created_time`= now() WHERE `prospect_id` = '" . $prospect_id . "' AND `user_id` = '" . $user_id . "' AND `twitter_screen_id` = '" . $follower_name . "'";
													mysql_query($sql1);
												} else {
													if ( $direct_message_post->errors[0]->code != 186 && $direct_message_post->errors[0]->code != 150 && $direct_message_post->errors[0]->code != 349 ) {
														mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='".$direct_message_post->errors[0]->message."' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
													}
													if ( $direct_message_post->errors[0]->code == 150 || $direct_message_post->errors[0]->code == 151 || $direct_message_post->errors[0]->code == 349 ) {
														$sql1 = "UPDATE `" . DB_PREFIX . "nurtureship` SET `dm_send_status`= '1' WHERE `user_id` = '" . $user_id . "' AND `twitter_user_name` = '" . $follower_name . "'";
														mysql_query($sql1);
													}
													echo "<pre>"; print_r($direct_message_post->errors); echo "</pre>\n";
												}
											} else {
												echo "4th Direct Message not set by this user\n";
											}
										}
									} else {
										echo "3 days is still remain to post the fourth direct message which is created by " . $customer['Cust_Screen_Name'] . " and will post on " . $follower_name . " twitter account\n";
									}
								} else {
									$newTime = date("Y-m-d H:i:s", strtotime($follower_added_time . " +15 minutes"));
									if ( $newTime < date("Y-m-d H:i:s") ) {
										if ( isset($comment_arr[0]) ) {
											echo "Message No. 1\n";
											$direct_message_post = $twitteroauth->post('direct_messages/new', array("user_id" => $follower_id, "text" => $comment_arr[0]));
											if ( !isset($direct_message_post->errors) ) {
												echo "DM 1 Posted Successfully to " . $follower_name . "\n";
												mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
												$insert_comment_sql = mysql_query("INSERT INTO `" . DB_PREFIX . "dm_status` (`prospect_id`, `user_id`, `twitter_screen_id`, `message_status`, `message_created_time`) VALUES ('" . $prospect_id . "', '" . $user_id . "', '" . $follower_name . "', 1, NOW())");
											} else {
												if ( $direct_message_post->errors[0]->code != 186 && $direct_message_post->errors[0]->code != 150 && $direct_message_post->errors[0]->code != 349 ) {
													mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error`='".$direct_message_post->errors[0]->message."' WHERE `Cust_ID` = ".$customer['Cust_ID']."");
												}
												if ( $direct_message_post->errors[0]->code == 150 || $direct_message_post->errors[0]->code == 151 || $direct_message_post->errors[0]->code == 349 ) {
													$sql1 = "UPDATE `" . DB_PREFIX . "nurtureship` SET `dm_send_status`= '1' WHERE `user_id` = '" . $user_id . "' AND `twitter_user_name` = '" . $follower_name . "'";
													mysql_query($sql1);
												}
												echo "<pre>"; print_r($direct_message_post->errors); echo "</pre>\n";
											}
										} else {
											echo "No Direct message set by this user " . $customer['Cust_Screen_Name'] . "\n";
										}
									} else {
										echo "15 minutes is still remain to post the first direct message which is created by " . $customer['Cust_Screen_Name'] . " and will post on " . $follower_name . " twitter account\n";
									}
								}
							} else {
								echo "DB ERROR\n";
							}
							$message_status_sql1 = mysql_query("SELECT * FROM `" . DB_PREFIX . "dm_status` WHERE `prospect_id` = '" . $prospect_id . "' AND `user_id` = '" . $user_id . "' AND `twitter_screen_id` = '" . $follower_name . "'");
							if ( $message_status_sql1 ) {
								if ( mysql_num_rows($message_status_sql1) > 0 ) {
									$message_status1 = mysql_fetch_assoc($message_status_sql1);
									if ( $message_status1['message_status'] == $comment_count ) {
										$sql1 = "UPDATE `" . DB_PREFIX . "nurtureship` SET `dm_send_status`= '1' WHERE `user_id` = '" . $user_id . "' AND `twitter_user_name` = '" . $follower_name . "'";
										mysql_query($sql1);
									}
								}
							} else {
								echo "DB ERROR\n";
							}
							unset($comment_arr);
							$comment_arr = array();
							echo "***************************************************************\n";
						}
					} else {
						echo "No Nurtureship found for the user " . $customer['Cust_Screen_Name'] . "\n";
					}
				} else {
					echo "DB ERROR\n";
				}
				unset($already_dm_user);
				$already_dm_user = array();
				echo "-------------------------------------------------------------------------\n";
			}
		}
	} else {
		echo "No User Found\n";
	}
} else {
	echo "DB ERROR\n";
}

$lf->write("This direct-message-cron.php ends at " . date("Y-m-d H:i:s") . "\n");