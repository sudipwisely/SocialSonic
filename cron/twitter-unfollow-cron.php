<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $mongoDb, $process, $lf;
$lf->write("This twitter-unfollow-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

echo CUR_SERVER;

$initUsr = "SELECT `user_id` FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'oneside_confirm' GROUP BY `user_id`";
$initUsrRS = mysql_query($initUsr);
if ( $initUsrRS ) {
	if ( mysql_num_rows($initUsrRS) > 0 ) {
		while ( $initUsrRS_result = mysql_fetch_assoc($initUsrRS) ) {	
			$user_id = $initUsrRS_result['user_id'];
			echo "User ID: " . $user_id . "\n";
			$customer = $helper->getCustomerById($user_id);
				if ( $customer['Cust_Server'] == CUR_SERVER ) {
					$dayAfterTomrrow = strtotime($customer['Cust_Last_Login_Time']) + 86400;
					$nowTime = strtotime(date('Y-m-d H:i:s'));
					if ( $dayAfterTomrrow > $nowTime ) {
						echo "Active User ID: " . $user_id . "\n";
						$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
						$InSQl = "select `id`,`twitter_user_id`,`twitter_user_name`,`relationship_status`,`relationship_time` FROM `" . DB_PREFIX . "nurtureship` WHERE `relationship_status` = 'oneside_confirm' AND `user_id`='".$user_id."'  ORDER BY `user_id`";
						$InRS = mysql_query($InSQl);
						if ( $InRS ) {
							if ( mysql_num_rows($InRS) > 0 ) {
								while ( $InRS_result = mysql_fetch_assoc($InRS) ) {
									$twitter_id = $InRS_result['twitter_user_id'];
									$twitter_add_time = $InRS_result['relationship_time'];
									$friendships = $twitteroauth->get('friendships/show', array('source_id' => $twitter_id, 'target_id' => $customer['Cust_Twitter_ID']));
									if ( $friendships->relationship->source->following == 1 ) {
										$update_sql = "UPDATE `" . DB_PREFIX . "nurtureship` SET `relationship_status` = 'confirm',`relationship_time`= now() WHERE `twitter_user_id` = '".$twitter_id."' AND `user_id` = '".$user_id."'";
										$update_result = mysql_query($update_sql);
										if ( $update_result ) {
											$prospect_keywords_document=$mongoDb->prospect_keywords->findOne(array("search_user_id"=>$user_id,"user_id"=>$twitter_id));
											if ( isset($prospect_keywords_document) ) {
												$user = array(
													"prospect_id"     =>$prospect_keywords_document['prospect_id'],
													"search_user_id"  =>$prospect_keywords_document['search_user_id'],
													"user_id"         =>$prospect_keywords_document['user_id'],
													"full_name"       =>$prospect_keywords_document['full_name'],
													"screen_name"     =>$prospect_keywords_document['screen_name'],
													"location"        =>$prospect_keywords_document['location'],
													"description"     =>$prospect_keywords_document['description'],
													"website"         =>$prospect_keywords_document['website'],
													"profile_image"   =>$prospect_keywords_document['profile_image'],
													"followers"       =>$prospect_keywords_document['followers'],
													"following"       =>$prospect_keywords_document['following'],
													"tweet_id"        =>$prospect_keywords_document['tweet_id'],
													"tweets"          =>$prospect_keywords_document['tweets'],
													"status"          =>'follow'
												);
												$where = array("search_user_id"=>$user_id,"user_id"=>$twitter_id);
												$mongoDb->prospect_keywords->update($where, $user);
											} else {
												$prospect_influencers_document=$mongoDb->prospect_influencers->findOne(array("search_user_id"=>$user_id,"user_id"=>$twitter_id));
												if ( isset($prospect_influencers_document) ) {
													$user = array(
														"prospect_id"     =>$prospect_influencers_document['prospect_id'],
														"search_user_id"  =>$prospect_influencers_document['search_user_id'],
														"user_id"         =>$prospect_influencers_document['user_id'],
														"full_name"       =>$prospect_influencers_document['full_name'],
														"screen_name"     =>$prospect_influencers_document['screen_name'],
														"location"        =>$prospect_influencers_document['location'],
														"description"     =>$prospect_influencers_document['description'],
														"website"         =>$prospect_influencers_document['website'],
														"profile_image"   =>$prospect_influencers_document['profile_image'],
														"followers"       =>$prospect_influencers_document['followers'],
														"following"       =>$prospect_influencers_document['following'],
														"influncer_id"   =>$prospect_influencers_document['influncer_id'],
														"status"          =>'follow'
													);
													$where = array("search_user_id"=>$user_id,"user_id"=>$twitter_id);
													$mongoDb->prospect_influencers->update($where, $user);
												} 
											}
										}
									} else {
										$newTime = date("Y-m-d H:i:s",strtotime(date($twitter_add_time)." +3 days"));
										if($newTime < date("Y-m-d H:i:s")){
											$update_sql = "UPDATE `" . DB_PREFIX . "nurtureship` SET `relationship_status` = 'unfollow',`relationship_time`= now() WHERE `twitter_user_id` = '".$twitter_id."' AND `user_id` = '".$user_id."'";
											$update_result = mysql_query($update_sql);
										} else {
											echo "Still Three Days not complete\n";
										}
									}
									$user = '';
								}
							} else {
								echo "You do not have any following data\n";
							}
						}
					} else {
						echo "This is a inactive user. \n";
					}
				
				}
		}
	} else {
		echo "No user have any following or followers data\n";
	}
}

$lf->write("This twitter-unfollow-cron.php ends at " . date("Y-m-d H:i:s") . "\n");