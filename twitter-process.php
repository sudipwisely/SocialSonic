<?php /*! Handle Twitter Requests of this Application */

require_once(dirname(__FILE__) . "/config/config.php");

class TwitterProcess {

	public function manageKeywordCategorySearch($prospect_id, $keywords, $category, $influencers, $type) {
		global $helper;
		if ( $prospect_id > 0 ) {
			if ( $type == 'keyword' ) {
				$sql = mysql_query("UPDATE `" . DB_PREFIX . "prospects` SET `Talks_About` = '" . addslashes($keywords) . "' WHERE `Prospect_ID` = $prospect_id AND `Cust_ID` = " . $_SESSION['Cust_ID']);
			} else {
				$sql = mysql_query("UPDATE `" . DB_PREFIX . "prospects` SET `Category` = $category, `Influencers` = '" . addslashes($influencers) . "' WHERE `Prospect_ID` = $prospect_id AND `Cust_ID` = " . $_SESSION['Cust_ID']);
			}
			if ( $sql ) {
				return $prospect_id;
			}
		} else {
			$prospect = $helper->getProspectByCustId($_SESSION['Cust_ID']);
			if ( count($prospect) == 0 ) {
				if ( $type == 'keyword' ) {
					$sql = mysql_query("INSERT INTO `" . DB_PREFIX . "prospects` (`Cust_ID`, `Talks_About`, `SearchOn`) VALUES (" . $_SESSION['Cust_ID'] . ", '" . addslashes($keywords) . "', NOW())");
				} else {
					$sql = mysql_query("INSERT INTO `" . DB_PREFIX . "prospects` (`Cust_ID`, `Category`, `Influencers`, `SearchOn`) VALUES (" . $_SESSION['Cust_ID'] . ", $category, '" . addslashes($influencers) . "', NOW())");
				}
				return mysql_insert_id();
			} else {
				return $prospect['Prospect_ID'];
			}
		}
	}

	public function storeOwnCategories($myInfluencers, $Prospect_ID, $myXInfluencers) {
		global $mongoDb, $helper;

		$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
		$Customer = $helper->getCustomerById($Cust_ID);
		$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

		$myPrevInfluencers = array();
		$nextCheckCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = 0 AND `user_id` = $Cust_ID");
		if ( $nextCheckCatSQL ) {
			if ( mysql_num_rows($nextCheckCatSQL) > 0 ) {
				while ( $nextCheckCatData = mysql_fetch_assoc($nextCheckCatSQL) ) {
					$myPrevInfluencers[$nextCheckCatData['influncer_twitter_id']] = $nextCheckCatData['influncer_twitter_screenname'];
				}
			} else {
				mysql_query("DELETE FROM `" . DB_PREFIX . "influencers_cursor` WHERE `Influencer_ID` NOT IN ($myXInfluencers) AND `Cust_ID` = $Cust_ID");
			}
		}

		$screen_name_array = array();
		foreach ( $myInfluencers as $influencer ) {
			$influencer = trim($influencer, '@');
			$screen_name_array[] = $influencer;

			$user_details 	= $twitteroauth->get('users/show', array("screen_name" => $influencer));
			if ( !isset($user_details->errors) ) {
				$checkCatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influncer_twitter_screenname` = '$influencer'");
				if ( $checkCatSQL ) {
					if ( mysql_num_rows($checkCatSQL) == 0 ) {
						mysql_query("INSERT INTO `" . DB_PREFIX . "influencers`(`user_id`, `influence_category_id`, `influncer_twitter_id`, `influncer_twitter_screenname`, `influncer_twitter_bio`, `influencer_website`, `influencer_profile_pic`) VALUES($Cust_ID, 0, '" . $user_details->id_str . "', '" . $user_details->screen_name . "', '" . addslashes($user_details->description) . "', '" . addslashes($user_details->url) . "', '" . addslashes($user_details->profile_image_url) . "')");
						$cursor = -1;
						$followers = $twitteroauth->get('followers/list', array('screen_name' => $user_details->screen_name, 'cursor' => $cursor));
						if ( !isset($followers->errors) ) {
							foreach ( $followers->users as $follower ) {
								$CheckDup = $mongoDb->prospect_influencers->findOne(array('search_user_id' =>  $Cust_ID, 'user_id' => $follower->id_str));
								if ( empty($CheckDup) ) {
									if ( !empty($follower->description) ) {
										if ( @getimagesize($follower->profile_image_url) ) {
											$insertData = array(
												'prospect_id'    => $Prospect_ID,
												'search_user_id' => $Cust_ID,
												'user_id'   	 => $follower->id_str,
												'full_name'   	 => $follower->name,
												'screen_name'    => $follower->screen_name,
												'location'       => $follower->location,
												'description'    => $follower->description,
												'website'        => $follower->url,
												'profile_image'  => $follower->profile_image_url,
												'followers'      => $follower->followers_count,
												'following'      => $follower->friends_count,
												'influncer_id'   => $user_details->id_str,
												'status'   		 => 'pending'
											);
											$mongoDb->prospect_influencers->insert($insertData);
										}
									}
								}
							}
							mysql_query("INSERT INTO `" . DB_PREFIX . "influencers_cursor`(`Cust_ID`, `Influencer_ID`, `Cursor_Point`) VALUES ($Cust_ID, '" . $user_details->id_str . "', '" . $followers->next_cursor_str . "')");
							$cursor = '';
							unset($_SESSION['api_error']);
						} else {
							$_SESSION['api_error'] = 'API Error';
						}
					} else {
						$checkUsrSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers` WHERE `influncer_twitter_screenname` = '$influencer' AND `user_id` = $Cust_ID");
						if ( mysql_num_rows($checkUsrSQL) == 0 ) {
							mysql_query("INSERT INTO `" . DB_PREFIX . "influencers`(`user_id`, `influence_category_id`, `influncer_twitter_id`, `influncer_twitter_screenname`, `influncer_twitter_bio`, `influencer_website`, `influencer_profile_pic`) VALUES($Cust_ID, 0, '" . $user_details->id_str . "', '" . $user_details->screen_name . "', '" . addslashes($user_details->description) . "', '" . addslashes($user_details->url) . "', '" . addslashes($user_details->profile_image_url) . "')");
						}
						$checkCatData = mysql_fetch_assoc($checkCatSQL);
						$CursorflSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "influencers_cursor` WHERE `Cust_ID` = $Cust_ID AND `Influencer_ID` = '" . $checkCatData['influncer_twitter_id'] . "'");
						if ( $CursorflSQL ) {
							$CursorflRows = mysql_num_rows($CursorflSQL);
							if ( $CursorflRows > 0 ) {
								$CursorflData = mysql_fetch_assoc($CursorflSQL);
								$cursor = $CursorflData['Cursor_Point'];
							} else {
								$cursor = -1;
							}
						} else {
							$cursor = -1;
							$CursorflRows = 0;
						}
						$followers = $twitteroauth->get('followers/list', array('screen_name' => $checkCatData['influncer_twitter_screenname'], 'cursor' => $cursor));
						if ( !isset($followers->errors) ) {
							foreach ( $followers->users as $follower ) {
								$CheckDup = $mongoDb->prospect_influencers->findOne(array('search_user_id' =>  $Cust_ID, 'user_id' => $follower->id_str));
								if ( empty($CheckDup) ) {
									if ( !empty($follower->description) ) {
										if ( @getimagesize($follower->profile_image_url) ) {
											$insertData = array(
												'prospect_id'    => $Prospect_ID,
												'search_user_id' => $Cust_ID,
												'user_id'   	 => $follower->id_str,
												'full_name'   	 => $follower->name,
												'screen_name'    => $follower->screen_name,
												'location'       => $follower->location,
												'description'    => $follower->description,
												'website'        => $follower->url,
												'profile_image'  => $follower->profile_image_url,
												'followers'      => $follower->followers_count,
												'following'      => $follower->friends_count,
												'influncer_id'   => $checkCatData['influncer_twitter_id'],
												'status'   		 => 'pending'
											);
											$mongoDb->prospect_influencers->insert($insertData);
										}
									}
								}
							}
							if ( $CursorflRows > 0 ) {
								mysql_query("UPDATE `" . DB_PREFIX . "influencers_cursor` SET `Cursor_Point` = '" . $followers->next_cursor_str . "' WHERE `Cust_ID` = $Cust_ID AND `Influencer_ID` = '" . $checkCatData['influncer_twitter_id'] . "'");
							} else {
								mysql_query("INSERT INTO `" . DB_PREFIX . "influencers_cursor`(`Cust_ID`, `Influencer_ID`, `Cursor_Point`) VALUES ($Cust_ID, '" . $checkCatData['influncer_twitter_id'] . "', '" . $followers->next_cursor_str . "')");
							}
							$cursor = '';
							unset($_SESSION['api_error']);
						} else {
							$_SESSION['api_error'] = 'API Error';
						}
					}
				}
			}
		}
		if ( count($myPrevInfluencers) > 0 ) {
			foreach ($myPrevInfluencers as $key => $value) {
				if ( ! in_array($value, $screen_name_array) ) {
					mysql_query("DELETE FROM `" . DB_PREFIX . "influencers` WHERE `influncer_twitter_id` = '$key' AND `user_id` = $Cust_ID");
					mysql_query("DELETE FROM `" . DB_PREFIX . "influencers_cursor` WHERE `Influencer_ID` = '$key' AND `Cust_ID` = $Cust_ID");
				}
			}
		}
		
	}

	public function getKeywordCRMResult() {
 		global $mongoDb, $helper;

 		$html_str = '';

 		$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
 		$Customer = $helper->getCustomerById($Cust_ID);

 		$Prospect = $helper->getProspectByCustId($Cust_ID);
 		$Prospect_Id = isset($Prospect['Prospect_ID']) ? $Prospect['Prospect_ID'] : '';

 		$talk_about_tags_limit = 10;
 		$talk_about_tags = isset($Prospect['Talks_About']) ? $Prospect['Talks_About'] : '';
 		$ary_talk_about_tags = explode(",", strtolower($talk_about_tags));

 		$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
 		$search_result = array("statuses" => array());

 		$twitter_search_limit = 80;
 		$result_limit = 50;
 		if ( count($ary_talk_about_tags) > $talk_about_tags_limit ) {
 			return $search_result['statuses'];
 		} else {
			foreach ( $ary_talk_about_tags as $key => $talk_tag ) {
 				$html_str .= '"' . $talk_tag . '" OR ';
 			} 
 			$html_str = substr($html_str, 0, -4);

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
 		} 

 		try {
 			$resultCount = 0;
 			$users = array();
 			foreach ( $search_result["statuses"] as $statuses ) {
 				$users[] = array(
 					'prospect_id' => $Prospect_Id,
 					'search_user_id'=> $Cust_ID,
 					'user_id'   => $statuses->user->id_str,
 					'full_name'  => $statuses->user->name,
 					'screen_name'  => $statuses->user->screen_name,
 					'location'   => $statuses->user->location,
 					'description'  => $statuses->user->description,
 					'website'   => $statuses->user->url,
 					'profile_image' => $statuses->user->profile_image_url,
 					'followers'     => $statuses->user->followers_count,
 					'following'     => $statuses->user->friends_count,
 					'tweet_id'  => $statuses->id_str,
 					'tweets'     => $statuses->text,
 					'status'   => 'pending'
 				);
 			}

 			$result_users = array(); 
 			$tweet_exist = false;
 			$prospect_document = $mongoDb->prospect_keywords->find(array("prospect_id"=>$Prospect_Id,"search_user_id"=>  $Cust_ID));
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
						if ( @getimagesize($user['profile_image']) ) {
							$searchTweet = strtolower($user['tweets']);
							foreach ( $ary_talk_about_tags as $myKeyword ) {
							    if ( strpos($searchTweet, $myKeyword) !== FALSE ) {
							    	if ( ! preg_match("/^RT +@[^ :]+:? */ui", $searchTweet) ) {
							    		$resultCount++;
										$mongoDb->prospect_keywords->insert($user);
									}
								}
							}
						}
					}	
 				}
 			} else {
 				foreach ( $users as $user ) {
					if ( $user['description'] != '' ) {
						if ( @getimagesize($user['profile_image']) ) {
							$searchTweet = strtolower($user['tweets']);
							foreach ( $ary_talk_about_tags as $myKeyword ) {
							    if ( strpos($searchTweet, $myKeyword) !== FALSE ) {
							    	if ( ! preg_match("/^RT +@[^ :]+:? */ui", $searchTweet) ) {
							    		$resultCount++;
										$mongoDb->prospect_keywords->insert($user);
									}
								}
							}
						}
					}
 				}
 			}
			mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = `Keyword_Pipeline` + $resultCount, `Date_Time` = NOW() WHERE `Cust_ID` = " . $Cust_ID);
 		} catch (Exception $e) {
 			return array();
 		}
 		$html_str = '';
 	}

	public function ShowExistingFollowersData() {
		global $mongoDb, $helper;
		
		$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
		$Customer = $helper->getCustomerById($Cust_ID);

		$findFollowers = $mongoDb->user_followers->findOne(array('user_id' => $Cust_ID));
		if ( empty($findFollowers) ) {
			$users = array();
			$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
			$followers = $twitteroauth->get('followers/list', array('user_id' => $Customer['Cust_Twitter_ID'], 'cursor' => -1));
			if ( !isset($followers->errors) ) {
				foreach ( $followers->users as $follower ) {
					$findone = $mongoDb->user_followers->findOne(array('id_str' => $follower->id_str));
					if ( empty($findone) ) {
						$users = array(
							"user_id"          => $Customer['Cust_ID'],
							"user_screen_name" => $Customer['Cust_Screen_Name'],
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
					}
					$users = '';
				}
				$selsql = mysql_query("SELECT * FROM `" . DB_PREFIX . "followers_cursor` WHERE `User_ID` = '" . $_SESSION['Cust_ID'] . "'");
				if ( $selsql ) {
					if ( mysql_num_rows($selsql) == 0 ) {
						$cursorSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "followers_cursor`(`Cursor_ID`, `User_ID`) VALUES ('" . $followers->next_cursor_str . "', '" . $_SESSION['Cust_ID'] . "')");
					}
				}
			}
		}
	}

	public function manageCardCounts($Cust_ID) {
		global $mongoDb;

		$keyword_count = $mongoDb->prospect_keywords->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'pending'));
		$category_count = $mongoDb->prospect_influencers->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'pending'));

		$keyword1_count = $mongoDb->prospect_keywords->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'complete'));
		$category1_count = $mongoDb->prospect_influencers->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'complete'));

		$keyword2_count = $mongoDb->prospect_keywords->find(array("search_user_id" => $Cust_ID, 'website' => array('$ne' => null), 'status' => 'complete'));
		$category2_count = $mongoDb->prospect_influencers->find(array("search_user_id" => $Cust_ID, 'website' => array('$ne' => null), 'status' => 'complete'));

		$keyword3_count = $mongoDb->prospect_keywords->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'unfollow'));
		$category3_count = $mongoDb->prospect_influencers->find(array("search_user_id" => $Cust_ID, 'description' => array('$ne' => ''), 'status' => 'unfollow'));

		$followers_count = $mongoDb->user_followers->find(array("user_id" => $Cust_ID));

		$countCheckSql = mysql_query("SELECT * FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $Cust_ID . " LIMIT 0, 1");
		if ( $countCheckSql ) {
			if ( mysql_num_rows($countCheckSql) == 0 ) {
				mysql_query("INSERT INTO `" . DB_PREFIX . "card_count`(`Cust_ID`, `Keyword_Pipeline`, `Category_Pipeline`, `Prospects`, `Followers`, `Show_Websites`, `Unfollow`, `Date_Time`) VALUES (" . $Cust_ID . ", " . $keyword_count->count() . ", " . $category_count->count() . ", " . ($keyword1_count->count() + $category1_count->count()) . ", " . $followers_count->count() . ", " . ($keyword2_count->count() + $category2_count->count()) . ", " . ($keyword3_count->count() + $category3_count->count()) . ", NOW())");
			} else {
				mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = " . $keyword_count->count() . ", `Category_Pipeline` = " . $category_count->count() . ", `Prospects` = " . ($keyword1_count->count() + $category1_count->count()) . ", `Followers` = " . $followers_count->count() . ", `Show_Websites` = " . ($keyword2_count->count() + $category2_count->count()) . ", `Unfollow` = " . ($keyword3_count->count() + $category3_count->count()) . ", `Date_Time` = NOW() WHERE `Cust_ID` = " . $Cust_ID);
			}
		}
	}

}
$process = new TwitterProcess();