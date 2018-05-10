<?php 
require_once(dirname(dirname(__FILE__)) . "/config/config.php");

global $helper, $mongoDb, $process, $lf;
$html_str = '';
$talk_about_tags_limit = 10;
$twitter_search_limit = 100;
$result_limit = 30;
$lf->write("This keyword-cron.php starts at " . date("Y-m-d H:i:s") . "\n");

$AutoSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` as `pro` LEFT JOIN `" . DB_PREFIX . "customers` as `cus` ON `pro`.`Cust_ID` = `cus`.`Cust_ID` WHERE (`cus`.`Cust_Payment_Type` = 'normal') GROUP BY `pro`.`Cust_ID` ORDER BY `pro`.`Prospect_ID` DESC");
if ( $AutoSQL ) {
	if ( mysql_num_rows($AutoSQL) > 0 ) {
		while ( $AutoRS_result = mysql_fetch_assoc($AutoSQL) ) {
			$user_id = $AutoRS_result['Cust_ID'];
			$customer = $helper->getCustomerById($user_id);
			if ( $customer['Cust_Server'] == CUR_SERVER ) {
				$dayAfterTomrrow = strtotime($customer['Cust_Last_Login_Time']) + 86400;
				$nowTime = strtotime(date('Y-m-d H:i:s'));
				if ( $dayAfterTomrrow > $nowTime ) {
					echo "Active User ID: " . $user_id . "\n";
					$prospect_id = $AutoRS_result['Prospect_ID'];
					$html_str = '';
					$sql_prospect = "SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Prospect_ID` = " . $prospect_id;
					$result_prospect = mysql_query($sql_prospect);
					if ( mysql_num_rows($result_prospect) == 0 ) {
						return array();
					}
					$row_prospect = mysql_fetch_assoc($result_prospect);
					$talk_about_tags = $row_prospect['Talks_About'];
					$ary_talk_about_tags = explode(",", $talk_about_tags);
					$twitteroauth = new TwitterOAuth($customer['Cust_API_Key'], $customer['Cust_API_Secret'], $customer['Cust_Access_Token'], $customer['Cust_Token_Secret']);
					
					$search_result = array("statuses" => array());
					if ( count($ary_talk_about_tags) > $talk_about_tags_limit ) {
						return $search_result['statuses'];
					} else {
						foreach ( $ary_talk_about_tags as $key => $talk_tag ) {
							$html_str .= '"' . $talk_tag . '" OR ';
						} 
						$html_str = substr($html_str, 0, -4); 
					}
					$search_details = $twitteroauth->get('search/tweets', array("count" => $twitter_search_limit, "q" => $html_str, "result_type" => 'mixed'));
					if ( isset($search_details->statuses) ) {
						foreach ( $search_details->statuses as $tweet ) {
							$twit_exist = false;
							foreach ( $search_result["statuses"] as $key => $new_tweet ) {
								if ( $new_tweet->user->id_str == $tweet->user->id_str ) {
									$twit_exist = true;
									break;
								}
							}
							if ( !$twit_exist ) {
								array_push($search_result["statuses"], $tweet);
							} 
							if ( count($search_result["statuses"]) >= $result_limit ) { break; }
						}
					}
					try {
						$resultCount = 0;
						$users = array();
						foreach ( $search_result["statuses"] as $statuses ) {
							$users[] = array(
								'prospect_id'    => $prospect_id,
								'search_user_id' => $user_id,
								'user_id'        => $statuses->user->id_str,
								'full_name'      => $statuses->user->name,
								'screen_name'    => $statuses->user->screen_name,
								'location'       => $statuses->user->location,
								'description'    => $statuses->user->description,
								'website'        => $statuses->user->url,
								'profile_image'  => $statuses->user->profile_image_url,
								'followers'      => $statuses->user->followers_count,
								'following'      => $statuses->user->friends_count,
								'tweet_id'       => $statuses->id_str,
								'tweets'         => $statuses->text,
								'status'         => 'pending'
							);
						}
						$result_users = array(); 
						$tweet_exist = false;
						$prospect_document = $mongoDb->prospect_keywords->find(array("prospect_id"=>$prospect_id,"search_user_id"=> $user_id));
						if ( $prospect_document->count() > 0 ) {
							foreach ( $users as $new_user ) {
								foreach ( $prospect_document as $pd ) {
									unset($pd['_id']);
									if ( $pd['user_id'] == $new_user['user_id'] ) {
										$tweet_exist = true;
										break;
									}
								}
								if ( !$tweet_exist ) {
									$result_users[] = $new_user;
								} else {
									$tweet_exist = false;
								}
							}
							foreach ( $result_users as $user ) {
								if ( $user['description'] != '' ) {
									$searchTweet = strtolower($user['tweets']);
									foreach ( $ary_talk_about_tags as $myKeyword ) {
										if ( strpos($searchTweet, $myKeyword) !== FALSE ) {
											if ( ! preg_match("/^RT +@[^ :]+:? */ui", $searchTweet) ) {
												$resultCount++;
												$mongoDb->prospect_keywords->insert($user);
												echo "<pre>"; print_r($user); echo "</pre>\n";
											}
										}
									}
								}
							}
						} else {
							foreach ( $users as $user ) {
								if ( $user['description'] != '' ) {
									$searchTweet = strtolower($user['tweets']);
									foreach ( $ary_talk_about_tags as $myKeyword ) {
										if ( strpos($searchTweet, $myKeyword) !== FALSE ) {
											if ( ! preg_match("/^RT +@[^ :]+:? */ui", $searchTweet) ) {
												$resultCount++;
												$mongoDb->prospect_keywords->insert($user);
												echo "<pre>"; print_r($user); echo "</pre>\n";
											}
										}
									}
								}
							}
						}
						mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = `Keyword_Pipeline` + $resultCount, `Date_Time` = NOW() WHERE `Cust_ID` = " . $user_id);
					} catch (Exception $e) {
						return array();
					}
				} else {
					echo "Inactive User ID: " . $user_id . "\n";
					echo "This user is not login within last 48hours.\n";
				}
			}
		}
	} else {
		echo "No Prospect Enlisted\n";
	}
} else {
	echo "DB Error\n";
}

$lf->write("This keyword-cron.php ends at " . date("Y-m-d H:i:s") . "\n");