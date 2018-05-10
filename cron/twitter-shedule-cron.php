<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $lf;
$lf->write("This twitter-schedule-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

$initUsr = "SELECT `user_id` FROM `" . DB_PREFIX . "schedule_tweet` WHERE `status` = 'pending' GROUP BY `user_id`";
$initUsrRS = mysql_query($initUsr);
if ( $initUsrRS ) {
	if ( mysql_num_rows($initUsrRS) > 0 ) {
		while ( $initUsrRS_result = mysql_fetch_assoc($initUsrRS) ) {
			$user_id = $initUsrRS_result['user_id'];
			echo "User ID: " . $user_id . "\n";
			$customer = $helper->getCustomerById($user_id);
			if ( $customer['Cust_Server'] == CUR_SERVER ) {
				$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
				$InSQl = "SELECT * FROM `" . DB_PREFIX . "schedule_tweet` WHERE `status` = 'pending' AND `user_id` = '" . $user_id . "' ORDER BY `user_id` LIMIT 0, 1";
				$InRS = mysql_query($InSQl);
				if ( $InRS ) {
					if ( mysql_num_rows($InRS) > 0 ) {
						while ( $InRS_result = mysql_fetch_assoc($InRS) ) {
							$tweet = $InRS_result['tweet_text'];
							$screenname = $InRS_result['twitter_user_screen_id'];
							$tweet_msg = '@' . $screenname . ' ' . $tweet;
							echo $customer['Cust_FirstName'] . " " . $customer['Cust_LastName'] . " is trying to Post the tweet = " . $tweet_msg . " to " . $screenname . "\n";
							$tweet = $twitteroauth->post('statuses/update', array('status' => $tweet_msg));
							if ( !isset($tweet->errors) ) {
								if ( $customer['Cust_App_Error'] != '' ) {
									mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error` = '' WHERE `Cust_ID` = " . $customer['Cust_ID']);
								}
								$update = mysql_query("UPDATE `" . DB_PREFIX . "schedule_tweet` SET `status` = 'complete' WHERE `twitter_user_id` = '" . $InRS_result['twitter_user_id'] . "' AND `user_id` = '" . $user_id . "'");
								mysql_query("INSERT INTO `" . DB_PREFIX . "lead_responses`(`Cust_ID`, `Recipient_ID`, `Mention_ID`, `Tweet_ID`, `Tweet_Text`, `From_ID`, `To_ID`, `From_ScreenName`, `To_ScreenName`, `Response_Status`, `Send_Time`, `Insert_Time`) VALUES (" . $customer['Cust_ID'] . ", '" . $tweet->entities->user_mentions[0]->id_str . "', '" . $tweet->id_str . "', '" . $tweet->id_str . "', '" . addslashes($tweet->text) . "', '" . $customer['Cust_Twitter_ID'] . "', '" . $tweet->entities->user_mentions[0]->id_str . "', '" . $customer['Cust_Screen_Name'] . "', '" . $tweet->entities->user_mentions[0]->screen_name . "', 'read', '" . $tweet->created_at . "', NOW())");
								$document_keyword = $mongoDb->prospect_keywords->findOne(array('search_user_id' => $customer['Cust_ID'], 'user_id' => $InRS_result['twitter_user_id'], 'status' => 'scTweet'));
								if ( !empty($document_keyword) ) {
									$where = array('search_user_id' => $customer['Cust_ID'], 'user_id' => $InRS_result['twitter_user_id'], 'status' => 'scTweet');
									$set = array( 
										'prospect_id'      => $document_keyword['prospect_id'],
										'search_user_id'   => $customer['Cust_ID'],
										'user_id'          => $InRS_result['twitter_user_id'],
										'full_name'        => $document_keyword['full_name'],
										'screen_name'      => $document_keyword['screen_name'],
										'location'         => $document_keyword['location'],
										'description'      => $document_keyword['description'],
										'website'          => $document_keyword['website'],
										'profile_image'    => $document_keyword['profile_image'],
										'followers'        => $document_keyword['followers'],
										'following'        => $document_keyword['following'],
										'tweet_id'         => $document_keyword['tweet_id'],
										'tweets'           => $document_keyword['tweets'],
										'status'           => 'scTweetComplete'
									);
									$upk = $mongoDb->prospect_keywords->update($where, $set);
								}
								$document_influencer = $mongoDb->prospect_influencers->findOne(array('search_user_id' => $customer['Cust_ID'], 'user_id' => $InRS_result['twitter_user_id'], 'status' => 'scTweet'));
								if ( !empty($document_influencer )) {
									$where = array('search_user_id' => $customer['Cust_ID'], 'user_id' => $InRS_result['twitter_user_id'], 'status' => 'scTweet');
									$set = array( 
										'prospect_id'     => $document_influencer['prospect_id'],
										'search_user_id'  => $customer['Cust_ID'],
										'user_id'         => $InRS_result['twitter_user_id'],
										'full_name'       => $document_influencer['full_name'],
										'screen_name'     => $document_influencer['screen_name'],
										'location'        => $document_influencer['location'],
										'description'     => $document_influencer['description'],
										'website'         => $document_influencer['website'],
										'profile_image'   => $document_influencer['profile_image'],
										'followers'       => $document_influencer['followers'],
										'following'       => $document_influencer['following'],
										'influncer_id'    => $document_influencer['influncer_id'],
										'status'          => 'scTweetComplete'
									);
									$upinf = $mongoDb->prospect_influencers->update($where, $set);
								}
							} else {
								if ( $customer['Cust_App_Error'] == '' ) {
									if ( $tweet->errors[0]->code != 186 ) {
										mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_App_Error` = '" . $tweet->errors[0]->message . "' WHERE `Cust_ID` = " . $customer['Cust_ID']);
									}
								}
								echo "<pre>"; print_r($tweet); echo "</pre>\n";
							}
						}
					} else {
						echo "You do not have any following data\n";
					}
				}
			}
		}
	} else {
		echo "No user have any following or followers data\n";
	}
}

$lf->write("This twitter-schedule-cron.php ends at " . date("Y-m-d H:i:s") . "\n");