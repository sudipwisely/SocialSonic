<?php /*! Handle Ajax Requests of this Application */

require_once(dirname(__FILE__) . "/config/config.php");

$case = isset($_REQUEST['case']) ? $_REQUEST['case'] : '';
switch($case) {
	case 'GetInfluencersByCatId':
		GetInfluencersByCatId();
		break;
	case 'UnfollowProspectFunc':
		UnfollowProspectFunc();
		break;
	case 'RemoveProspectsPipeline':
		RemoveProspectsPipeline();
		break;
	case 'InitiateNurturingFunc':
		InitiateNurturingFunc();
		break;
	case 'SingleDirectMessage':
		SingleDirectMessage();
		break;
	case 'SaveDirectMessage':
		SaveDirectMessage();
		break;
	case 'SingleTweetMessage':
		SingleTweetMessage();
		break;
	case 'LoadingExtraKeywords':
		LoadingExtraKeywords();
		break;
	case 'LoadingExtraCategories':
		LoadingExtraCategories();
		break;
	case 'LoadingExtraFollowers':
		LoadingExtraFollowers();
		break;
	case 'LoadingExtraProspects':
		LoadingExtraProspects();
		break;
	case 'LoadingExtraWebsites':
		LoadingExtraWebsites();
		break;
	case 'LoadingExtraUnfollow':
		LoadingExtraUnfollow();
		break;
	case 'LoadingSavedFilters':
		LoadingSavedFilters();
		break;
	case 'LoadingExtraResponses':
		LoadingExtraResponses();
		break;
	case 'LoadingSingleReplies':
		LoadingSingleReplies();
		break;
	case 'LoadingSingleConversation':
		LoadingSingleConversation();
		break;
	case 'SubmitLeadReplies':
		SubmitLeadReplies();
		break;
	case 'DownloadWebsitesCSV':
		DownloadWebsitesCSV();
		break;		
	case 'SubmitRetweet':
		SubmitRetweet();
		break;
	case 'CheckUsersDataExist' :
		CheckUsersDataExist();
		break;
	case 'CheckTwitterAuthenticData':
		CheckTwitterAuthenticData();
		break;
	case 'ForgotPasswordProcess':
		ForgotPasswordProcess();
		break;
	case 'PostPaymentFormSubmit':
		PostPaymentFormSubmit();
		break;
	case 'CustomerLoginProcess':
		CustomerLoginProcess();
		break;
	case 'ChangeAndSkipPassword':
		ChangeAndSkipPassword();
		break;
	case 'CheckCurrentPassword':
		CheckCurrentPassword();
		break;
	case 'CheckValidScreenName':
		CheckValidScreenName();
		break;
	case 'MemberSignupForm':
		MemberSignupForm();
		break;
	case 'ResendRegURI':
		ResendRegURI();
		break;
	case 'UpdateCustomerAccount':
		UpdateCustomerAccount();
		break;
	case 'SaveFilterSearch':
		SaveFilterSearch();
		break;
	case 'GetSingleFilter':
		GetSingleFilter();
		break;
	case 'GetAllCustomers':
		GetAllCustomers();
		break;
	case 'CustomerDataDelete':
		CustomerDataDelete();
		break;
	case 'CheckCustomerSession':
		CheckCustomerSession();
		break;
	case 'GetProspectsLast10Tweets':
		GetProspectsLast10Tweets();
		break;
	case 'UnsubscribeCustomer' :
		UnsubscribeCustomer();
		break;
	case 'testCase':
		testCase();
		break;
	default:
		echo '404 not found!';
		break;
}

function GetInfluencersByCatId() {
	$catId = $_REQUEST['catId'];
	$influencers = '';
	if ( $catId == 0 ) {
		$infSQL = mysql_query("SELECT `influncer_twitter_id`, `influncer_twitter_screenname` FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = $catId AND `user_id` = " . $_SESSION['Cust_ID']);
	} else {
		$infSQL = mysql_query("SELECT `influncer_twitter_id`, `influncer_twitter_screenname` FROM `" . DB_PREFIX . "influencers` WHERE `influence_category_id` = $catId");
	}
	if ( $infSQL ) {
		$infRows = mysql_num_rows($infSQL);
	} else {
		$infRows = 0;
	}
	if ( $infRows > 0 ) {
		while ( $infData = mysql_fetch_assoc($infSQL) ) {
			$influencers .= $infData['influncer_twitter_id'] . '@' . $infData['influncer_twitter_screenname'] . ',';
		}
		$influencers = substr($influencers, 0, -1);
	} else {
		$influencers = '';
	}
	echo $influencers;
}

function UnfollowProspectFunc() {
	global $mongoDb, $helper;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
	
	$prospect_id = $_REQUEST['prospect_id'];
	$userid		 = $_REQUEST['userid'];
	$screenname	 = $_REQUEST['screenname'];
	
	$DeleteFav = mysql_query("DELECT FROM `" . DB_PREFIX . "favourite` WHERE `user_id` = '$Cust_ID' AND `tweet_screen_name` = '$screenname'");
	$DeleteNur = mysql_query("DELETE FROM `" . DB_PREFIX . "nurtureship` WHERE `user_id` = '$Cust_ID' AND `twitter_user_id` = '$userid'");
	if ( $DeleteNur ) {
		$keyword = $mongoDb->prospect_keywords->remove(array("search_user_id" =>  $Cust_ID, "user_id" => $userid));
		$influencer = $mongoDb->prospect_influencers->remove(array("search_user_id" =>  $Cust_ID, "user_id" => $userid));
		$unfollow = $twitteroauth->post('friendships/destroy', array('user_id' => $userid));
		echo 1;
	} else {
		echo 0;
	}
}

function RemoveProspectsPipeline() {
	global $mongoDb;

	$userid	 =  $_REQUEST['userid'];
	$custid	 =  $_REQUEST['custid'];

	$keyword = $mongoDb->prospect_keywords->findOne(array('search_user_id' => $custid, 'user_id' => $userid, 'status' => 'pending'));
	if ( !empty($keyword) ) {
		$where = array('search_user_id' => $custid, 'user_id' => $userid, 'status' => 'pending');
		$set = array( 
			'prospect_id'   => $keyword['prospect_id'],
			'search_user_id'=> $keyword['search_user_id'],
			'user_id'       => $keyword['user_id'],
			'full_name'     => $keyword['full_name'],
			'screen_name'   => $keyword['screen_name'],
			'location'      => $keyword['location'],
			'description'   => $keyword['description'],
			'website'       => $keyword['website'],
			'profile_image' => $keyword['profile_image'],
			'followers'     => $keyword['followers'],
			'following'     => $keyword['following'],
			'tweet_id'      => $keyword['tweet_id'],
			'tweets'        => $keyword['tweets'],
			'status'        => 'remove'
		);
		$upK = $mongoDb->prospect_keywords->update($where, $set);
		mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = `Keyword_Pipeline` - 1 WHERE `Cust_ID` = $custid");
	}

	$influencer = $mongoDb->prospect_influencers->findOne(array('search_user_id' => $custid, 'user_id' => $userid, 'status' => 'pending'));
	if ( !empty($influencer) ) {
		$where = array('search_user_id' => $custid, 'user_id' => $userid, 'status' => 'pending');
		$set = array( 
			'prospect_id'   => $influencer['prospect_id'],
			'search_user_id'=> $influencer['search_user_id'],
			'user_id'   	=> $influencer['user_id'],
			'full_name'  	=> $influencer['full_name'],
			'screen_name'  	=> $influencer['screen_name'],
			'location'   	=> $influencer['location'],
			'description'  	=> $influencer['description'],
			'website'   	=> $influencer['website'],
			'profile_image' => $influencer['profile_image'],
			'followers'     => $influencer['followers'],
			'following'     => $influencer['following'],
			'influncer_id'  => $influencer['influncer_id'],
			'status'   		=> 'remove'
		);
		$upI = $mongoDb->prospect_influencers->update($where, $set);
	}

	if ( $upK || $upI ) {
		echo 1;
	} else {
		echo 0;
	}
}

function InitiateNurturingFunc() {
	global $mongoDb;
	
	$prospect_id = $_REQUEST['prospect_id'];
	$userid		 = $_REQUEST['userid'];
	$screenname	 = $_REQUEST['screenname'];
	$tweetid 	 = $_REQUEST['tweetid'];
	
	if ( $tweetid == 'influence' ) {
		$favSQL = true;
	} else {
		$existing_fav_sql = mysql_query("SELECT * FROM `" . DB_PREFIX . "favourite` WHERE `user_id` = '" . $_SESSION['Cust_ID'] . "' AND `tweet_screen_name` = '" . $screenname . "'");
		if ( $existing_fav_sql ) {
			if ( mysql_num_rows($existing_fav_sql) == 0 ) {
				$favSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "favourite`(`user_id`, `tweet_id`, `tweet_screen_name`, `fav_status`, `tweet_favourite_time`) VALUES ('" . $_SESSION['Cust_ID'] . "', '$tweetid', '$screenname', 'pending', NOW())");
			} else {
				$favSQL = true;
			}
		}
	}
	if ( $favSQL ) {
		$existing_relationship_sql = mysql_query("SELECT * FROM `" . DB_PREFIX . "nurtureship` WHERE `user_id` = '" . $_SESSION['Cust_ID'] . "' AND `twitter_user_id` = '" . $userid . "'");
		if ( $existing_relationship_sql ) {
			if ( mysql_num_rows($existing_relationship_sql) == 0 ) {
				$relSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "nurtureship`(`prospect_id`, `twitter_user_id`, `twitter_user_name`, `user_id`, `relationship_status`, `relationship_time`) VALUES ('$prospect_id', '$userid', '$screenname', '" . $_SESSION['Cust_ID'] . "', 'pending', NOW())");
			} else {
				$relSQL = true;
			}
		}
		if ( $relSQL ) {
			$users = array();
			if ( $tweetid == 'influence' ) {
				$influencer = $mongoDb->prospect_influencers->findOne(array("search_user_id" =>  $_SESSION['Cust_ID'], "user_id" => $userid));
				$where = array("search_user_id" =>  $_SESSION['Cust_ID'], "user_id" => $userid);
				$set = array( 
					'prospect_id'   => $prospect_id,
					'search_user_id'=> $_SESSION['Cust_ID'],
					'user_id'   	=> $influencer['user_id'],
					'full_name'  	=> $influencer['full_name'],
					'screen_name'  	=> $influencer['screen_name'],
					'location'   	=> $influencer['location'],
					'description'  	=> $influencer['description'],
					'website'   	=> $influencer['website'],
					'profile_image' => $influencer['profile_image'],
					'followers'     => $influencer['followers'],
					'following'     => $influencer['following'],
					'influncer_id'  => $influencer['influncer_id'],
					'status'   		=> 'complete'
				);
				$updateMDB = $mongoDb->prospect_influencers->update($where, $set);
			} else {
				$keyword = $mongoDb->prospect_keywords->findOne(array("search_user_id" =>  $_SESSION['Cust_ID'], "user_id" => $userid));
				$where = array("search_user_id" =>  $_SESSION['Cust_ID'], "user_id" => $userid);
				$set = array( 
					'prospect_id'   => $prospect_id,
					'search_user_id'=> $_SESSION['Cust_ID'],
					'user_id'       => $keyword['user_id'],
					'full_name'     => $keyword['full_name'],
					'screen_name'   => $keyword['screen_name'],
					'location'      => $keyword['location'],
					'description'   => $keyword['description'],
					'website'       => $keyword['website'],
					'profile_image' => $keyword['profile_image'],
					'followers'     => $keyword['followers'],
					'following'     => $keyword['following'],
					'tweet_id'      => $keyword['tweet_id'],
					'tweets'        => $keyword['tweets'],
					'status'        => 'complete'
				);
				$updateMDB = $mongoDb->prospect_keywords->update($where, $set);
				mysql_query("UPDATE `" . DB_PREFIX . "card_count` SET `Keyword_Pipeline` = `Keyword_Pipeline` - 1 WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
			}
			if ( $updateMDB ) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}
}

function SaveDirectMessage() {
	$delete_sql = "DELETE FROM `" . DB_PREFIX . "direct_message` WHERE `prospect_id`  = '" . $_POST['prospect_id'] . "'";
	$delete_result = mysql_query($delete_sql);
		
	for ( $i = 0; $i < count($_POST['direct_messages']); $i++ ) {
		$comment_sql = "INSERT INTO `" . DB_PREFIX . "direct_message` (`prospect_id`, `user_id`, `direct_message`) VALUES ('" . $_POST['prospect_id'] . "', '" . $_SESSION['Cust_ID'] . "', '" . addslashes($_POST['direct_messages'][$i]) . "')";
		$comment_result = mysql_query($comment_sql);
	}
}

function SingleDirectMessage() {
	global $helper;

	$userids = $_REQUEST['userids'];
	$screennames = $_REQUEST['screennames'];
	$message = $_REQUEST['message'];
	
	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	if ( $Customer ) {
		$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
		foreach	( $userids as $userid ) {
			$friendships = $twitteroauth->get('friendships/show', array('source_id' => $userid, 'target_id' => $Customer['Cust_Twitter_ID']));
			if ( $friendships->relationship->source->following == 1 ) {
				$direct_message_post = $twitteroauth->post('direct_messages/new', array("user_id" => $userid, "text" => $message));
				if ( $direct_message_post ) {
					$output = 1;
				} else {
					$output = 0;
				}
			} else {
				$output = 0;
			}
		}
		echo $output;
	}
}

function SingleTweetMessage() {
	global $helper,$mongoDb;

	$role = $_REQUEST['role'];
	$userids = $_REQUEST['userids'];
	$screennames = $_REQUEST['screennames'];
	$tweetids = $_REQUEST['tweetids'];
	$tweetmsgs = $_REQUEST['tweetmsgs'];
	if ( $role == 'multiple' ) {
		$n = 0;
		$msgCount = count($tweetmsgs);
		if ( count($userids) == 1 ) {
			$loop = $msgCount;
		} else {
			$loop = count($userids);
		}
		for ( $i = 0; $i < $loop; $i++ ) {
			if ( count($userids) == 1 ) {
				$userid = $userids[0];
				$screenname = $screennames[0];
			} else {
				$userid = $userids[$i];
				$screenname = $screennames[$i];
			}
			$sql = mysql_query("SELECT * FROM `" . DB_PREFIX . "schedule_tweet` WHERE `twitter_user_id` = '" . $userid . "' AND `tweet_text` = '" . addslashes($tweetmsgs[$n]) . "' AND `user_id` = '" . $_SESSION['Cust_ID'] . "'");
			if ( $sql ) {
				if ( mysql_num_rows($sql) == 0 ) {
					$insertSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "schedule_tweet`(`twitter_user_id`, `twitter_user_screen_id`, `tweet_text`, `user_id`) VALUES ('" . $userid . "', '" . $screenname . "', '" . addslashes($tweetmsgs[$n]) . "', '" . $_SESSION['Cust_ID'] . "')");
					$document_keyword = $mongoDb->prospect_keywords->findOne(array('search_user_id' => $_SESSION['Cust_ID'], 'user_id' => $userid, 'status' => 'complete'));
					if ( !empty($document_keyword) ) {
						$where = array('search_user_id' => $_SESSION['Cust_ID'], 'user_id' => $userid, 'status' => 'complete');
						$set = array( 
							'prospect_id'      => $document_keyword['prospect_id'],
							'search_user_id'   => $_SESSION['Cust_ID'],
							'user_id'          => $userid,
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
							'status'           => 'scTweet'
						);
						$upk = $mongoDb->prospect_keywords->update($where, $set);
					}
					$document_influencer = $mongoDb->prospect_influencers->findOne(array('search_user_id' => $_SESSION['Cust_ID'], 'user_id' => $userid, 'status' => 'complete'));
					if ( !empty($document_influencer )) {
						$where = array('search_user_id' => $_SESSION['Cust_ID'], 'user_id' => $userid, 'status' => 'complete');
						$set = array( 
							'prospect_id'     => $document_influencer['prospect_id'],
							'search_user_id'  => $_SESSION['Cust_ID'],
							'user_id'         => $userid,
							'full_name'       => $document_influencer['full_name'],
							'screen_name'     => $document_influencer['screen_name'],
							'location'        => $document_influencer['location'],
							'description'     => $document_influencer['description'],
							'website'         => $document_influencer['website'],
							'profile_image'   => $document_influencer['profile_image'],
							'followers'       => $document_influencer['followers'],
							'following'       => $document_influencer['following'],
							'influncer_id'    => $document_influencer['influncer_id'],
							'status'          => 'scTweet'
						);
						$upinf = $mongoDb->prospect_influencers->update($where, $set);
					}
					$n++;
					if ( $n == $msgCount ) {
						$n = 0;
					}
				} else {
					$insertSQL = true;
				}
			}
		}
		if ( $insertSQL ) {
			echo 2;
		} else {
			echo 0;
		}
	} else {
		$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
		$Customer = $helper->getCustomerById($Cust_ID);
		if ( $Customer ) {
			$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
			foreach	( $screennames as $screenname ) {
				$message = '@' . $screenname . ' ' . $tweetmsgs[0];
				$tweet = $twitteroauth->post('statuses/update', array('status' => $message));
				mysql_query("INSERT INTO `" . DB_PREFIX . "lead_responses`(`Cust_ID`, `Recipient_ID`, `Mention_ID`, `Tweet_ID`, `Tweet_Text`, `From_ID`, `To_ID`, `From_ScreenName`, `To_ScreenName`, `Response_Status`, `Send_Time`, `Insert_Time`) VALUES ($Cust_ID, '" . $tweet->entities->user_mentions[0]->id_str . "', '" . $tweet->id_str . "', '" . $tweet->id_str . "', '" . addslashes($tweet->text) . "', '" . $Customer['Cust_Twitter_ID'] . "', '" . $tweet->entities->user_mentions[0]->id_str . "', '" . $Customer['Cust_Screen_Name'] . "', '" . $tweet->entities->user_mentions[0]->screen_name . "', 'read', '" . $tweet->created_at . "', NOW())");
			}
			if ( $tweet ) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}
}

function LoadingExtraKeywords() {
	global $mongoDb;

	$html['html'] = '';
	
	$bioTweets 	 = $_REQUEST['bioTweets'];
	$limit 		 = (intval($_GET['limit']) != 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) != 0 ) 	? $_GET['offset'] 	: 0;

	$bio_field 	 = isset($_REQUEST['bio_field']) 	? $_REQUEST['bio_field'] 	: '';
	$talks_about = isset($_REQUEST['talks_about']) 	? $_REQUEST['talks_about'] 	: '';
	$followers 	 = isset($_REQUEST['followers']) 	? $_REQUEST['followers'] 	: '';
	$following 	 = isset($_REQUEST['following']) 	? $_REQUEST['following'] 	: '';
	$location 	 = isset($_REQUEST['location']) 	? $_REQUEST['location'] 	: '';

	$filter_array["search_user_id"] = $_SESSION['Cust_ID'];
	if ( !empty($bio_field) ) {
		$filter_array['description'] = new MongoRegex("/.*{$bio_field}.*/i");
	} else {
		$filter_array['description'] = array('$ne' => '');
	}
	if ( !empty($talks_about) ) {
		$filter_array['tweets'] = new MongoRegex("/.*{$talks_about}.*/i");
	}
	if ( !empty($followers) ) {
		$followers = explode(',', $followers);
		$filter_array['followers'] = array('$gte' => (int)$followers[0], '$lte' => (int)$followers[1]);
	}
	if ( !empty($following) ) {
		$following = explode(',', $following);
		$filter_array['following'] = array('$gte' => (int)$following[0], '$lte' => (int)$following[1]);
	}
	if ( !empty($location) ) {
		$filter_array['location'] = new MongoRegex("/.*{$location}.*/i");
	}
	$filter_array["status"] = 'pending';

	$CheckProspectSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $CheckProspectSQL ) {
		$ProspectRows = mysql_num_rows($CheckProspectSQL);
		if ( $ProspectRows > 0 ) {
			$ProspectData = mysql_fetch_assoc($CheckProspectSQL);
		} else {
			$ProspectData = array('Talks_About' => '');
		}
	}
	$users = array();
	$document = $mongoDb->prospect_keywords->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);

	foreach ( $document as $doc ) {
		$users[] = $doc;
	}

	/*$countSQL = mysql_query("SELECT `Keyword_Pipeline` FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $countSQL ) {
		$countData = mysql_fetch_assoc($countSQL);
		$html['resultCount'] = $countData['Keyword_Pipeline'];
	} else {
		$html['resultCount'] = 0;
	}*/

	if ( !empty($ProspectData['Talks_About']) && (count($users) + $offset) == 0 ) {
		$html['html'] = 'noProspect';
	} else {
		if ( count($users) > 0 ) {
			$i = $offset;
			foreach ( $users as $user ) {
				$html['html'][$i]['prospect_id'] 	= $user['prospect_id'];
				$html['html'][$i]['search_user_id'] = $user['search_user_id'];
				$html['html'][$i]['user_id'] 		= $user['user_id'];
				$html['html'][$i]['full_name'] 		= $user['full_name'];
				$html['html'][$i]['screen_name'] 	= $user['screen_name'];
				$html['html'][$i]['location'] 		= $user['location'];
				$html['html'][$i]['description'] 	= $user['description'];
				$html['html'][$i]['website'] 		= $user['website'];
				$html['html'][$i]['profile_image'] 	= $user['profile_image'];
				$html['html'][$i]['followers'] 		= $user['followers'];
				$html['html'][$i]['following'] 		= $user['following'];
				$html['html'][$i]['tweet_id'] 		= $user['tweet_id'];
				$html['html'][$i]['tweets'] 		= $user['tweets'];
				$html['html'][$i]['status'] 		= $user['status'];
				$i++;
			}
		} elseif ( $offset >= (count($users) + $offset) && $offset != 0 ) {
			$html['html'] = 'noMore';
		} elseif ( $offset <= (count($users) + $offset) && $offset != 0 ) {
			$html['html'] = 'noMore';
		} elseif ( (count($users) + $offset) != 0 && count($users) == 0 ) {
			$html['html'] = 'noFilter';
		} else {
			$html['html'] = 'noResult';
		}
	}
	echo json_encode($html);
}

function LoadingExtraCategories() {
	global $mongoDb;

	$html['html'] = '';
	
	$limit 		 = (intval($_GET['limit']) > 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) > 0 ) 	? $_GET['offset'] 	: 0;

	$bio_field 	 = isset($_REQUEST['bio_field']) 	? $_REQUEST['bio_field'] 	: '';
	$followers 	 = isset($_REQUEST['followers']) 	? $_REQUEST['followers'] 	: '';
	$following 	 = isset($_REQUEST['following']) 	? $_REQUEST['following'] 	: '';
	$follows 	 = isset($_REQUEST['follows']) 		? $_REQUEST['follows'] 		: '';
	$location 	 = isset($_REQUEST['location']) 	? $_REQUEST['location'] 	: '';

	$filter_array["search_user_id"] = $_SESSION['Cust_ID'];
	if ( !empty($bio_field) ) {
		$filter_array['description'] = new MongoRegex("/.*{$bio_field}.*/i");
	} else {
		$filter_array['description'] = array('$ne' => '');
	}
	if ( !empty($followers) ) {
		$followers = explode(',', $followers);
		$filter_array['followers'] = array('$gte' => (int)$followers[0], '$lte' => (int)$followers[1]);
	}
	if ( !empty($following) ) {
		$following = explode(',', $following);
		$filter_array['following'] = array('$gte' => (int)$following[0], '$lte' => (int)$following[1]);
	}
	if ( !empty($follows) ) {
		$filter_array['influncer_id'] = $follows;
	}
	if ( !empty($location) ) {
		$filter_array['location'] = new MongoRegex("/.*{$location}.*/i");
	}
	$filter_array["status"] = 'pending';

	$CheckProspectSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $CheckProspectSQL ) {
		$ProspectRows = mysql_num_rows($CheckProspectSQL);
		if ( $ProspectRows > 0 ) {
			$ProspectData = mysql_fetch_assoc($CheckProspectSQL);
		} else {
			$ProspectData = array('Influencers' => '');
		}
	}
	$users = array();
	$document = $mongoDb->prospect_influencers->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);

	foreach ( $document as $doc ) {
		$users[] = $doc;
	}

	/*$countSQL = mysql_query("SELECT `Category_Pipeline` FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $countSQL ) {
		$countData = mysql_fetch_assoc($countSQL);
		$html['resultCount'] = $countData['Category_Pipeline'];
	} else {
		$html['resultCount'] = 0;
	}*/

	if ( !empty($ProspectData['Influencers']) && (count($users) + $offset) == 0 ) {
		if ( isset($_SESSION['api_error']) ) {
			$html['html'] = 'apiError';
		} else {
			$html['html'] = 'noProspect';
		}
	} else {
		if ( count($users) > 0 ) {
			$i = $offset;
			foreach ( $users as $user ) {
				$html['html'][$i]['prospect_id'] 	= $user['prospect_id'];
				$html['html'][$i]['search_user_id'] = $user['search_user_id'];
				$html['html'][$i]['user_id'] 		= $user['user_id'];
				$html['html'][$i]['full_name'] 		= $user['full_name'];
				$html['html'][$i]['screen_name'] 	= $user['screen_name'];
				$html['html'][$i]['location'] 		= $user['location'];
				$html['html'][$i]['description'] 	= $user['description'];
				$html['html'][$i]['website'] 		= $user['website'];
				$html['html'][$i]['profile_image'] 	= $user['profile_image'];
				$html['html'][$i]['followers'] 		= $user['followers'];
				$html['html'][$i]['following'] 		= $user['following'];
				$html['html'][$i]['influncer_id'] 	= $user['influncer_id'];
				$html['html'][$i]['status'] 		= $user['status'];
				$i++;
			}
		} elseif ( $offset >= (count($users) + $offset) && $offset != 0 ) {
			$html['html'] = 'noMore';
		} elseif ( $offset <= (count($users) + $offset) && $offset != 0 ) {
			$html['html'] = 'noMore';
		} elseif ( (count($users) + $offset) != 0 && count($users) == 0 ) {
			$html['html'] = 'noFilter';
		} else {
			$html['html'] = 'noResult';
		}
	}
	echo json_encode($html);
}

function LoadingExtraProspects() {
	global $mongoDb;

	$html['html'] = '';
	
	$limit 		 = (intval($_GET['limit']) != 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) != 0 ) 	? $_GET['offset'] 	: 0;

	$bio_field 	 = isset($_REQUEST['bio_field']) 	? $_REQUEST['bio_field'] 	: '';
	$talks_about = isset($_REQUEST['talks_about']) 	? $_REQUEST['talks_about'] 	: '';
	$followers 	 = isset($_REQUEST['followers']) 	? $_REQUEST['followers'] 	: '';
	$following 	 = isset($_REQUEST['following']) 	? $_REQUEST['following'] 	: '';
	$follows 	 = isset($_REQUEST['follows']) 		? $_REQUEST['follows'] 		: '';
	$location 	 = isset($_REQUEST['location']) 	? $_REQUEST['location'] 	: '';

	$filter_array["search_user_id"] = $_SESSION['Cust_ID'];
	if ( !empty($bio_field) ) {
		$filter_array['description'] = new MongoRegex("/.*{$bio_field}.*/i");
	}
	if ( !empty($talks_about) ) {
		$filter_array['tweets'] = new MongoRegex("/.*{$talks_about}.*/i");
	}
	if ( !empty($followers) ) {
		$followers = explode(',', $followers);
		$filter_array['followers'] = array('$gte' => (int)$followers[0], '$lte' => (int)$followers[1]);
	}
	if ( !empty($following) ) {
		$following = explode(',', $following);
		$filter_array['following'] = array('$gte' => (int)$following[0], '$lte' => (int)$following[1]);
	}
	if ( !empty($follows) ) {
		$filter_array['influncer_id'] = $follows;
	}
	if ( !empty($location) ) {
		$filter_array['location'] = new MongoRegex("/.*{$location}.*/i");
	}
	$filter_array["status"] = array('$in' => array('complete', 'scTweet'));

	$users = array();
	$document = $mongoDb->prospect_keywords->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);
	foreach ( $document as $doc ) {
		$users[] = $doc;
	}

	$document = $mongoDb->prospect_influencers->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);
	foreach ( $document as $doc ) {
		$users[] = $doc;
	}

	/*$countSQL = mysql_query("SELECT `Prospects` FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $countSQL ) {
		$countData = mysql_fetch_assoc($countSQL);
		$html['resultCount'] = $countData['Prospects'];
	} else {
		$html['resultCount'] = 0;
	}*/

	if ( count($users) > 0 ) {
		$i = $offset;
		foreach ( $users as $user ) {
			$html['html'][$i]['prospect_id'] 	= $user['prospect_id'];
			$html['html'][$i]['search_user_id'] = $user['search_user_id'];
			$html['html'][$i]['user_id'] 		= $user['user_id'];
			$html['html'][$i]['full_name'] 		= $user['full_name'];
			$html['html'][$i]['screen_name'] 	= $user['screen_name'];
			$html['html'][$i]['location'] 		= $user['location'];
			$html['html'][$i]['description'] 	= $user['description'];
			$html['html'][$i]['website'] 		= $user['website'];
			$html['html'][$i]['profile_image'] 	= $user['profile_image'];
			$html['html'][$i]['followers'] 		= $user['followers'];
			$html['html'][$i]['following'] 		= $user['following'];
			if ( isset($user['tweet_id']) ) {
				$html['html'][$i]['tweet_id'] 	= $user['tweet_id'];
			}
			if ( isset($user['tweets']) ) {
				$html['html'][$i]['tweets'] 	= $user['tweets'];
			}
			if ( isset($user['influncer_id']) ) {
				$html['html'][$i]['influncer_id'] 	= $user['influncer_id'];
			}
			$html['html'][$i]['status'] 		= $user['status'];
			$i++;
		}
	} elseif ( $offset >= (count($users) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} elseif ( $offset <= (count($users) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} else {
		$html['html'] = 'noResult';
	}
	echo json_encode($html);
}

function LoadingExtraFollowers() {
	global $mongoDb, $helper, $process;

	$html['html'] = '';

	$process->ShowExistingFollowersData();
	
	$limit 		 = (intval($_GET['limit']) != 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) != 0 ) 	? $_GET['offset'] 	: 0;

	$bio_field 	 = isset($_REQUEST['bio_field']) 	? $_REQUEST['bio_field'] 	: '';
	$followers 	 = isset($_REQUEST['followers']) 	? $_REQUEST['followers'] 	: '';
	$following 	 = isset($_REQUEST['following']) 	? $_REQUEST['following'] 	: '';
	$location 	 = isset($_REQUEST['location']) 	? $_REQUEST['location'] 	: '';

	$filter_array["user_id"] = $_SESSION['Cust_ID'];
	if ( !empty($bio_field) ) {
		$filter_array['description'] = new MongoRegex("/.*{$bio_field}.*/i");
	}
	if ( !empty($followers) ) {
		$followers = explode(',', $followers);
		$filter_array['followers'] = array('$gte' => (int)$followers[0], '$lte' => (int)$followers[1]);
	}
	if ( !empty($following) ) {
		$following = explode(',', $following);
		$filter_array['following'] = array('$gte' => (int)$following[0], '$lte' => (int)$following[1]);
	}
	if ( !empty($location) ) {
		$filter_array['location'] = new MongoRegex("/.*{$location}.*/i");
	}

	$users = array();
	$user_document = $mongoDb->user_followers->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);
	foreach ( $user_document as $document ) {
		$users[] = $document;
	}

	/*$countSQL = mysql_query("SELECT `Followers` FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $countSQL ) {
		$countData = mysql_fetch_assoc($countSQL);
		$html['resultCount'] = $countData['Followers'];
	} else {
		$html['resultCount'] = 0;
	}*/

	if ( count($users) > 0 ) {
		$i = $offset;
		foreach ( $users as $user ) {
			$html['html'][$i]['user_id'] 	 	  = $user['user_id'];
			$html['html'][$i]['user_screen_name'] = $user['user_screen_name'];
			$html['html'][$i]['id_str'] 		  = $user['id_str'];
			$html['html'][$i]['name'] 			  = $user['name'];
			$html['html'][$i]['screen_name'] 	  = $user['screen_name'];
			$html['html'][$i]['location'] 		  = $user['location'];
			$html['html'][$i]['description'] 	  = $user['description'];
			$html['html'][$i]['website'] 		  = $user['website'];
			$html['html'][$i]['profile_pic'] 	  = $user['profile_pic'];
			$html['html'][$i]['followers'] 		  = $user['followers'];
			$html['html'][$i]['following'] 		  = $user['following'];
			$i++;
		}
	} elseif ( $offset >= (count($users) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} elseif ( $offset <= (count($users) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} else {
		$html['html'] = 'noResult';
	}
	echo json_encode($html);
}

function LoadingExtraWebsites() {
	global $mongoDb;

	$html['html'] = '';

	$limit 		 = (intval($_GET['limit']) != 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) != 0 ) 	? $_GET['offset'] 	: 0;

	$filter_array["search_user_id"] = $_SESSION['Cust_ID'];
	$filter_array["website"] = array('$ne' => null);
	$filter_array["status"] = 'complete';

	$websites = array();
	$keyword_document = $mongoDb->prospect_keywords->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);
	foreach ( $keyword_document as $document ) {
		if ( !empty($document['website']) ) {
			$websites[] = $document;
		}
	}

	$category_document = $mongoDb->prospect_influencers->find($filter_array)->sort(array('_id' => -1))->skip($offset)->limit($limit);
	foreach ( $category_document as $document ) {
		if ( !empty($document['website']) ) {
			$websites[] = $document;
		}
	}

	/*$countSQL = mysql_query("SELECT `Show_Websites` FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $countSQL ) {
		$countData = mysql_fetch_assoc($countSQL);
		$html['resultCount'] = $countData['Show_Websites'];
	} else {
		$html['resultCount'] = 0;
	}*/

	if ( count($websites) > 0 ) {
		$i = $offset;
		foreach ( $websites as $website ) {
			$html['html'][$i]['full_name'] 		= $website['full_name'];
			$html['html'][$i]['screen_name'] 	= $website['screen_name'];
			$html['html'][$i]['description'] 	= $website['description'];
			$html['html'][$i]['location'] 		= $website['location'];
			$html['html'][$i]['website'] 		= $website['website'];
			$i++;
		}
	} elseif ( $offset >= (count($websites) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} elseif ( $offset <= (count($websites) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} else {
		$html['html'] = 'noResult';
	}
	echo json_encode($html);
}

function LoadingExtraUnfollow() {
	global $mongoDb;

	$html['html'] = '';

	$limit 		 = (intval($_GET['limit']) != 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) != 0 ) 	? $_GET['offset'] 	: 0;

	$users = array();
	$unfollowSqlLimit = mysql_query("SELECT * FROM `" . DB_PREFIX . "nurtureship` WHERE `user_id` = '" . $_SESSION['Cust_ID'] . "' AND `relationship_status` = 'unfollow' LIMIT $offset, $limit");
	if ( $unfollowSqlLimit ) {
		while ( $unfollowData = mysql_fetch_assoc($unfollowSqlLimit) ) {
			$keyword_document = $mongoDb->prospect_keywords->find(array("search_user_id" => $_SESSION['Cust_ID'], "user_id" => $unfollowData['twitter_user_id']))->sort(array('_id' => -1));
			foreach ( $keyword_document as $document ) {
				$users[] = $document;
			}

			$category_document = $mongoDb->prospect_influencers->find(array("search_user_id" => $_SESSION['Cust_ID'], "user_id" => $unfollowData['twitter_user_id']))->sort(array('_id' => -1));
			foreach ( $category_document as $document ) {
				$users[] = $document;
			}
		}
	}

	/*$countSQL = mysql_query("SELECT `Unfollow` FROM `" . DB_PREFIX . "card_count` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $countSQL ) {
		$countData = mysql_fetch_assoc($countSQL);
		$html['resultCount'] = $countData['Unfollow'];
	} else {
		$html['resultCount'] = 0;
	}*/

	if ( count($users) > 0 ) {
		$i = $offset;
		foreach ( $users as $user ) {
			$html['html'][$i]['prospect_id'] 	= $user['prospect_id'];
			$html['html'][$i]['search_user_id'] = $user['search_user_id'];
			$html['html'][$i]['user_id'] 		= $user['user_id'];
			$html['html'][$i]['full_name'] 		= $user['full_name'];
			$html['html'][$i]['screen_name'] 	= $user['screen_name'];
			$html['html'][$i]['location'] 		= $user['location'];
			$html['html'][$i]['description'] 	= $user['description'];
			$html['html'][$i]['website'] 		= $user['website'];
			$html['html'][$i]['profile_image'] 	= $user['profile_image'];
			$html['html'][$i]['followers'] 		= $user['followers'];
			$html['html'][$i]['following'] 		= $user['following'];
			if ( isset($user['tweet_id']) ) {
				$html['html'][$i]['tweet_id'] 	= $user['tweet_id'];
			}
			if ( isset($user['tweets']) ) {
				$html['html'][$i]['tweets'] 	= $user['tweets'];
			}
			if ( isset($user['influncer_id']) ) {
				$html['html'][$i]['influncer_id'] 	= $user['influncer_id'];
			}
			$html['html'][$i]['status'] 		= $user['status'];
			$i++;
		}
	} elseif ( $offset >= (count($users) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} elseif ( $offset <= (count($users) + $offset) && $offset != 0 ) {
		$html['html'] = 'noMore';
	} else {
		$html['html'] = 'noResult';
	}
	echo json_encode($html);
}

function LoadingSavedFilters() {
	global $mongoDb;

	$html['html'] = '';

	$GetSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "filters` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $GetSQL ) {
		$GetRows = mysql_num_rows($GetSQL);
	} else {
		$GetRows = 0;
	}
	if ( $GetRows == 0 ) {
		$html['html'] = 'noResult';
	} else {
		$i = 1;
		while ( $filter = mysql_fetch_assoc($GetSQL) ) {
			$filter_array = array();
			$filterID    = $filter['Filter_ID'];
			$custID 	 = $filter['Cust_ID'];
			$filterName  = $filter['Filter_Name'];
			$filterFrom  = $filter['Filter_From'];
			$bio_field 	 = $filter['Bio'];
			$talks_about = $filter['Talks_About'];
			$followers 	 = $filter['Followers'];
			$following 	 = $filter['Following'];
			$follows 	 = $filter['Follows'];
			$location 	 = $filter['Location'];
			$filterOn    = $filter['Filter_On'];

			$filter_array["search_user_id"] = $_SESSION['Cust_ID'];
			if ( !empty($bio_field) ) {
				$filter_array['description'] = new MongoRegex("/.*{$bio_field}.*/i");
			} else {
				$filter_array['description'] = array('$ne' => '');
			}
			if ( $filterFrom == 'keyword' ) {
				if ( !empty($talks_about) ) {
					$filter_array['tweets'] = new MongoRegex("/.*{$talks_about}.*/i");
				}
			} elseif ( $filterFrom == 'category' ) {
				if ( !empty($follows) ) {
					$filter_array['influncer_id'] = $follows;
				}
			}
			if ( !empty($followers) ) {
				$followersArray = explode(',', $followers);
				$filter_array['followers'] = array('$gte' => (int)$followersArray[0], '$lte' => (int)$followersArray[1]);
			}
			if ( !empty($following) ) {
				$followingArray = explode(',', $following);
				$filter_array['following'] = array('$gte' => (int)$followingArray[0], '$lte' => (int)$followingArray[1]);
			}
			if ( !empty($location) ) {
				$filter_array['location'] = new MongoRegex("/.*{$location}.*/i");
			}
			$filter_array["status"] = 'pending';

			if ( $filterFrom == 'keyword' ) {
				$document = $mongoDb->prospect_keywords->find($filter_array);
			} elseif ( $filterFrom == 'category' ) {
				$document = $mongoDb->prospect_influencers->find($filter_array);
			}

			$html['html'] .= '<tr>';
			$html['html'] .= '<td>' . $filterName . '</td>';
			$html['html'] .= '<td class="text-center">' . ucfirst($filterFrom) . '</td>';
			//$html['html'] .= '<td class="text-center"></td>'; // $document->count()
			$html['html'] .= '<td class="text-center" data-id="' . $filterID . '" data-name="' . $filterName . '" data-from="' . $filterFrom . '" data-bio="' . $bio_field . '" data-tweet="' . $talks_about . '" data-followers="' . $followers . '" data-following="' . $following . '" data-follows="' . $follows . '" data-location="' . $location . '">';
			$html['html'] .= '<a data-rel="view" class="btn btn-xs btn-primary FilterActionBtn"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
			$html['html'] .= '<a data-rel="edit" class="btn btn-xs btn-success FilterActionBtn"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
			$html['html'] .= '<a data-rel="remove" class="btn btn-xs btn-danger FilterActionBtn"><i class="fa fa-trash"></i></a>';
			$html['html'] .= '</a>';
			$html['html'] .= '</td>';
			$html['html'] .= '</tr>';
			$i++;
		}
	}
	echo json_encode($html);
}

function LoadingExtraResponses() {
	global $helper;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

	$html['html'] = '';
	$limit 		 = (intval($_GET['limit']) != 0 ) 	? $_GET['limit'] 	: 15;
	$offset 	 = (intval($_GET['offset']) != 0 ) 	? $_GET['offset'] 	: 0;

	$responsesSql = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID GROUP BY `Recipient_ID`");
	if ( $responsesSql ) {
		$html['totalResultCount'] = mysql_num_rows($responsesSql);
	} else {
		$html['totalResultCount'] = 0;
	}

	$convSQL = mysql_query("SELECT `Mention_ID` FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID GROUP BY `Mention_ID`");
	if ( $convSQL ) {
		if ( mysql_num_rows($convSQL) > 0 ) {
			while ( $convData = mysql_fetch_assoc($convSQL) ) {
				$helper->getTweetConversation($convData['Mention_ID']);
			}
		}
	}
	
	$Status = array();
	$StatusSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID");
	if ( $StatusSQL ) {
		if ( mysql_num_rows($StatusSQL) > 0 ) {
			$i = 0;
			while ( $StatusData = mysql_fetch_assoc($StatusSQL) ) {
				if ( $StatusData['Response_Status'] == 'unread' ) {
					$Status[$StatusData['Recipient_ID']][$i] = $StatusData;
					$i++;
				}
			}
		}
	}

	$responses = array();
    $TweetSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID GROUP BY `Recipient_ID` ORDER BY `ID` ASC");
    if ( $TweetSQL ) {
        while ( $TweetData = mysql_fetch_assoc($TweetSQL) ) {
        	$responses[] = $TweetData;
        }
        $html['resultCount'] = $html['totalResultCount'];
		if ( count($responses) > 0 ) {
			$i = $offset;
			foreach ( $responses as $response ) {
				$user = $twitteroauth->get('users/show', array("user_id" => $response['Recipient_ID']));
				$html['html'][$i]['unread_count']	= count($Status[$response['Recipient_ID']]) > 99 ? '99+' : count($Status[$response['Recipient_ID']]);
				$html['html'][$i]['full_name'] 		= $user->name;
				$html['html'][$i]['screen_name'] 	= $user->screen_name;
				$html['html'][$i]['profile_image'] 	= $user->profile_image_url;
				$html['html'][$i]['send_to']		= $response['Recipient_ID'];
				$html['html'][$i]['status']			= count($Status[$response['Recipient_ID']]) > 0 ? 'unread' : 'read';
				$html['html'][$i]['send_time'] 		= date('D M d h:i A', strtotime($response['Send_Time']));
				$i++;
			}
		} elseif ( $offset >= $html['resultCount'] && $offset != 0 ) {
			$html['html'] = 'noMore';
		} elseif ( $offset <= $html['resultCount'] && $offset != 0 ) {
			$html['html'] = 'noMore';
		} else {
			$html['html'] = 'noResult';
		}
    }

	function sortByOrder($a, $b) {
		if ($a['status'] == $b['status']) {
	        return 0;
	    }
	    return ($a['status'] > $b['status']) ? -1 : 1;
	}
	usort($html['html'], 'sortByOrder');
	
    echo json_encode($html);
}

function LoadingSingleReplies() {
	global $helper;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

	$html['html'] = '';
	$send_to = $_REQUEST['send_to'];

	$Status = array();
	$StatusSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID");
	if ( $StatusSQL ) {
		if ( mysql_num_rows($StatusSQL) > 0 ) {
			$i = 0;
			while ( $StatusData = mysql_fetch_assoc($StatusSQL) ) {
				if ( $StatusData['Response_Status'] == 'unread' ) {
					$Status[$StatusData['Mention_ID']][$i] = $StatusData;
					$Status[$StatusData['Recipient_ID']][$i] = $StatusData;
					$i++;
				}
			}
		}
	}

	$TweetSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID AND `Recipient_ID` = '$send_to' GROUP BY `Mention_ID` ORDER BY `ID` DESC");
    if ( $TweetSQL ) {
    	if ( mysql_num_rows($TweetSQL) > 0 ) {
    		$i = 0;
	        while ( $reply = mysql_fetch_assoc($TweetSQL) ) {
	        	$html['html'][$i]['recp_count']		= count($Status[$reply['Recipient_ID']]) > 99 ? '99+' : count($Status[$reply['Recipient_ID']]);
	        	$html['html'][$i]['unread_count']	= count($Status[$reply['Mention_ID']]) > 99 ? '99+' : count($Status[$reply['Mention_ID']]);
	        	$html['html'][$i]['recipient_id']	= $reply['Recipient_ID'];
	        	$html['html'][$i]['tweet'] 			= $reply['Tweet_Text'];
				$html['html'][$i]['tweet_id'] 		= $reply['Mention_ID'];
				$html['html'][$i]['status']			= count($Status[$reply['Mention_ID']]) > 0 ? 'unread' : 'read';
				$html['html'][$i]['send_time'] 		= date('D M d h:i A', strtotime($reply['Send_Time']));
				$i++;
	        }
    	}
    }
    echo json_encode($html);
}

function LoadingSingleConversation() {
	global $helper, $twConv;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

	$html['html'] = '';
	$mention_id = $_REQUEST['tweetid'];

	$html['html']['tweetid'] = $mention_id;

	mysql_query("UPDATE `" . DB_PREFIX . "lead_responses` SET `Response_Status` = 'read' WHERE `Cust_ID` = $Cust_ID AND `Mention_ID` = '$mention_id'");
	$ChatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID AND `Mention_ID` = '$mention_id' ORDER BY `ID` ASC");
	if ( $ChatSQL ) {
		if ( mysql_num_rows($ChatSQL) > 0 ) {
			$i = 1;
			while ( $ChatData = mysql_fetch_assoc($ChatSQL) ) {
				$lead = $twitteroauth->get('users/show', array("user_id" => $ChatData['From_ID']));
				if ( strtolower($lead->screen_name) != strtolower($Customer['Cust_Screen_Name']) ) {
					$html['html']['screen_name'] = $lead->screen_name;
				} else {
					$html['html']['screen_name'] = $ChatData['To_ScreenName'];
				}
				$html['html']['tweets'][$i]['name'] 	 = $lead->name;
		    	$html['html']['tweets'][$i]['thumbnail'] = $lead->profile_image_url;
		    	$html['html']['tweets'][$i]['content'] 	 = $ChatData['Tweet_Text'];
				$i++;
			}
		}
	}
    echo json_encode($html);
}

function SubmitLeadReplies() {
	global $helper, $twConv;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
	
	$html['html'] = '';
	$method = 'data';
	$conversate = CONVERSATE_AFTER;

	$tweetid = $_REQUEST['tweetid'];
	$tweetmsg = $_POST['tweetmsg'];
	
	$post = $twitteroauth->post('statuses/update', array('status' => $tweetmsg, 'in_reply_to_status_id' => $tweetid ));
	if ( !isset($post->errors) ) {
		mysql_query("INSERT INTO `" . DB_PREFIX . "lead_responses`(`Cust_ID`, `Recipient_ID`, `Mention_ID`, `Tweet_ID`, `Tweet_Text`, `From_ID`, `To_ID`, `From_ScreenName`, `To_ScreenName`, `Response_Status`, `Send_Time`, `Insert_Time`) VALUES ($Cust_ID, '" . $post->entities->user_mentions[0]->id_str . "', '" . $tweetid . "', '" . $post->id_str . "', '" . addslashes($tweetmsg) . "', '" . $Customer['Cust_Twitter_ID'] . "', '" . $post->entities->user_mentions[0]->id_str . "', '" . $Customer['Cust_Screen_Name'] . "', '" . $post->entities->user_mentions[0]->screen_name . "', 'read', '" . $post->created_at . "', NOW())");
		$tweet = $twConv->fetchConversion($tweetid, $method, $conversate);
		if ( $tweet['error'] == 1 ) {
			$html['html']['error'] = $tweet['message'];
		} else {
			$i = count($tweet['tweets']) - 1;
	    	$html['html']['tweets']['name'] 	 = $tweet['tweets'][$i]['name'];
	    	$html['html']['tweets']['thumbnail'] = $tweet['tweets'][$i]['images']['thumbnail'];
	    	$html['html']['tweets']['content']   = $tweet['tweets'][$i]['content'];
	    	$html['html']['tweets']['date'] 	 = $tweet['tweets'][$i]['date'];
		}
	} else {
		$html['html']['error'] = $post->errors[0]->message;
	}
	echo json_encode($html);
}

function DownloadWebsitesCSV() {
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=websites.csv');
	
	$rows = array(array('Fullname', 'Screenname', 'Bio', 'Location', 'Website'));
	$fp = fopen('php://output', 'w');
	foreach ( $_REQUEST['checkall'] as $websites ) {
		$screen_web = explode('@|@', $websites);
		$rows[] = array($screen_web[0], $screen_web[1], $screen_web[2], $screen_web[3], $screen_web[4]);
	}
	
	foreach ($rows as $list) {
		fputcsv($fp, $list);
	}
}

function SubmitRetweet() {
	global $helper, $mongoDb;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$user_id = $_POST['user_id'];
	$rec_screen_name = $_POST['user_screen'];
	$status_id = $_POST['twit_id'];
	$status_twit = $_POST['twit_string'];
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
	if ( empty( $status_id ) ) {
    	$user_details = $connection->post('statuses/update', array('status' => $status_twit));
    	mysql_query("INSERT INTO `" . DB_PREFIX . "lead_responses`(`Cust_ID`, `Recipient_ID`, `Mention_ID`, `Tweet_ID`, `Tweet_Text`, `From_ID`, `To_ID`, `From_ScreenName`, `To_ScreenName`, `Response_Status`, `Send_Time`, `Insert_Time`) VALUES ($Cust_ID, '" . $user_id . "', '" . $user_details->id_str . "', '" . $user_details->id_str . "', '" . addslashes($status_twit) . "', '" . $Customer['Cust_Twitter_ID'] . "', '" . $user_id. "', '" . $Customer['Cust_Screen_Name'] . "', '" . $rec_screen_name . "', 'read', '" . $user_details->created_at . "', NOW())");
		echo 0;
	} else {
    	$user_details = $twitteroauth->post('statuses/update', array('status' => $status_twit, 'in_reply_to_status_id' => $status_id ));
    	mysql_query("INSERT INTO `" . DB_PREFIX . "lead_responses`(`Cust_ID`, `Recipient_ID`, `Mention_ID`, `Tweet_ID`, `Tweet_Text`, `From_ID`, `To_ID`, `From_ScreenName`, `To_ScreenName`, `Response_Status`, `Send_Time`, `Insert_Time`) VALUES ($Cust_ID, '" . $user_id . "', '" . $status_id . "', '" . $user_details->id_str . "', '" . addslashes($status_twit) . "', '" . $Customer['Cust_Twitter_ID'] . "', '" . $user_id . "', '" . $Customer['Cust_Screen_Name'] . "', '" . $rec_screen_name . "', 'read', '" . $user_details->created_at . "', NOW())");
    	$document_keyword = $mongoDb->prospect_keywords->findOne(array('search_user_id' => $Customer['Cust_ID'], 'user_id' => $user_id, 'status' => 'complete'));
		if ( !empty($document_keyword) ) {
			$where = array('search_user_id' => $Customer['Cust_ID'], 'user_id' => $user_id, 'status' => 'complete');
			$set = array( 
				'prospect_id'      => $document_keyword['prospect_id'],
				'search_user_id'   => $Customer['Cust_ID'],
				'user_id'          => $user_id,
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
		$document_influencer = $mongoDb->prospect_influencers->findOne(array('search_user_id' => $Customer['Cust_ID'], 'user_id' => $user_id, 'status' => 'complete'));
		if ( !empty($document_influencer )) {
			$where = array('search_user_id' => $Customer['Cust_ID'], 'user_id' => $user_id, 'status' => 'complete');
			$set = array( 
				'prospect_id'     => $document_influencer['prospect_id'],
				'search_user_id'  => $Customer['Cust_ID'],
				'user_id'         => $user_id,
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
		echo 1;
	}
}

function CheckUsersDataExist() {
	$valid   = true;
	$users = array();
	$CustSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers`");
	if ( $CustSQL ) {
		if ( mysql_num_rows($CustSQL) > 0 ) {
			$i = 0;
			while ( $cust_result = mysql_fetch_assoc($CustSQL) ) {
				$users[$i]['Cust_UserName'] 	= $cust_result['Cust_UserName'];
				$users[$i]['Cust_Email'] 		= $cust_result['Cust_Email'];
				$users[$i]['Cust_API_Key'] 	  	= $cust_result['Cust_API_Key'];
				$users[$i]['Cust_API_Secret']   = $cust_result['Cust_API_Secret'];
				$users[$i]['Cust_Twitter_ID']   = $cust_result['Cust_Twitter_ID'];
				$users[$i]['Cust_Screen_Name']  = $cust_result['Cust_Screen_Name'];
				$users[$i]['Cust_Access_Token'] = $cust_result['Cust_Access_Token'];
				$users[$i]['Cust_Token_Secret'] = $cust_result['Cust_Token_Secret'];
				$users[$i]['Cust_hopCode'] 	  	= $cust_result['Cust_hopCode'];
				$i++;
			}
		}
	}
	
	foreach ( $users as $user ) {
		if ( isset($_POST['customer_username']) ) {
			if ( $_POST['customer_username'] == $user['Cust_UserName'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_email']) ) {
			if ( $_POST['customer_email'] == $user['Cust_Email'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_twitter_consumer_key']) ) {
			if ( $_POST['customer_twitter_consumer_key'] == $user['Cust_API_Key'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_twitter_consumer_secret']) ) {
			if ( $_POST['customer_twitter_consumer_secret'] == $user['Cust_API_Secret'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_twitter_screenname']) ) {
			if ( $_POST['customer_twitter_screenname'] == $user['Cust_Screen_Name'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_twitter_id']) ) {
			if ( $_POST['customer_twitter_id'] == $user['Cust_Twitter_ID'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_twitter_access_token']) ) {
			if ( $_POST['customer_twitter_access_token'] == $user['Cust_Access_Token'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_access_token_secret']) ) {
			if ( $_POST['customer_access_token_secret'] == $user['Cust_Token_Secret'] ) {
				$valid   = false;
				break;
			}
		} elseif ( isset($_POST['customer_hopcode']) ) {
			if ( $_POST['customer_hopcode'] == $user['Cust_hopCode'] ) {
				$valid   = false;
				break;
			}
		}
	}

	echo json_encode(
		$valid ? array('valid' => $valid) : array('valid' => $valid)
	);
}

function CheckTwitterAuthenticData() {
	global $mongoDb, $helper;

	$valid = true; $message = true;

	$screen_name = $_REQUEST['screen_name'];
	$twitter_id = $_REQUEST['twitter_id'];

	$twitteroauth = new TwitterOAuth($_REQUEST['consumer_key'], $_REQUEST['consumer_secret'], $_REQUEST['access_token'], $_REQUEST['token_secret']);
	$content = $twitteroauth->get('account/verify_credentials');
	if ( isset($content->errors) ) {
		$message = $content->errors[0]->message;
		$valid = false;
	} else {
		$message = $content;
		$valid = true;
	}

	echo json_encode(
		$valid ? array('valid' => $valid, 'message' => $message) : array('valid' => $valid, 'message' => $message)
	);
}

function ForgotPasswordProcess() {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr(str_shuffle($chars), 0, 8);
	$check_customer_exist = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_Email` = '" . $_REQUEST['your_email'] . "' AND `Cust_UserName` = '" . $_REQUEST['your_username'] . "'");
	if ( $check_customer_exist ) {
		if ( mysql_num_rows($check_customer_exist) == 1 ) {
			$customerData = mysql_fetch_assoc($check_customer_exist);
			$customer_Update_sql = mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_Password` = '" . sha1($password) . "', `Cust_Login_Status` = 'NO' WHERE `Cust_Email` = '" . $_REQUEST['your_email'] . "' AND `Cust_UserName` = '" . $_REQUEST['your_username'] . "'");
				if ( $customer_Update_sql ) {
					mysql_query("UPDATE `" . DB_PREFIX . "temp_customers` SET `Temp_Password` = '" . trim($password) . "' WHERE `Temp_Email` = '" . addslashes(trim($_REQUEST['your_email'])) . "'");

					$subject  = 'New Login Details for Social Sonic';
					$body = '
					<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FCFCFD url(' . SITE_URL . 'images/body-bg.png) repeat 0 0;border:1px solid rgba(0, 0, 0, 0.15);font-family:Verdana,sans-serif;">
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td height="75" align="center" style="background:rgba(0, 0, 0, 0.15) repeat;border-bottom: 1px solid rgba(0, 0, 0, 0.15);">
											<img width="200" alt="" src="' . SITE_URL . 'images/ss-logo.png" />
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding:0 20px;">
											<font color="#161616" size="2"><br />
												<span>Dear ' . $customerData['Cust_FirstName'] . ' ' . $customerData['Cust_LastName'] . ',<br /><br />Thank you for using Social Sonic.<br />We recently received a request from you to reset your password on Social Sonic:</span>
											</font><br /><br />
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding-left:20px;">
											<font color="#0e59ac" size="3">
												<span>
													Your new temporary Password: <strong>' . $password . '</strong>
												</span>
											</font><br /><br />
											<font color="#161616" size="2">
												<span>Please keep this information confidential.</span>
											</font><br /><br />
											<font color="#f000" size="2">
												<span>For security purposes we advise you to change this password, <br />when you are prompted to after signing in to Social Sonic for the first time.</span>
											</font><br /><br />
											<font color="#000" size="2">
												<span>in case you have any questions, please call us on the number below<br /><br />Phone support:<br />1-702-960-4130<br />Thank you for choosing&nbsp;&nbsp;<a href="' . SITE_URL . '" style="color:#0e59ac; line-height:20px;">Social Sonic!</a><br /></span>
											</font><br /><br />
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding-left:20px;">
											<font color="#000" size="2">
												<span>Regards,<br />The <a href="#" style="color:#0e59ac;line-height:20px;">Social Sonic</a> Team.</span>
											</font><br /><br />
											<font color="#000" size="2">
												<span>P.S: This is a system generated email. Please do not reply.</span><br /><br />
											</font>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
					emailChoose($customerData['Cust_Email'], $subject, $body, $customerData['Cust_FirstName'] . ' ' . $customerData['Cust_LastName']);
					echo json_encode(1);
				} else {
					echo json_encode(2);
				}
		} else {
			echo json_encode(3);
		}
	}
}

function PostPaymentFormSubmit(){
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr(str_shuffle($chars), 0, 8);
	$check_customer_exist = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_Email` = '" . addslashes(trim($_REQUEST['customer_email'])) . "' OR `Cust_UserName` = '" . addslashes(trim($_REQUEST['customer_username'])) . "'");
	
	
	
	if ( $check_customer_exist ) {
		if ( mysql_num_rows($check_customer_exist) == 0 ) {
			$customer_Insert_sql = mysql_query("INSERT INTO `" . DB_PREFIX . "customers`(`Cust_Email`, `Cust_Password`, `Cust_FirstName`, `Cust_LastName`, `Cust_UserName`, `Cust_API_Key`, `Cust_API_Secret`, `Cust_Twitter_ID`, `Cust_Screen_Name`, `Cust_Access_Token`, `Cust_Token_Secret`, `Cust_hopCode`, `Cust_Payment_Status`, `Cust_Order_ID`, `Cust_Payment_Type`,`Cust_Server`,`Cust_Last_Login_Time`, `Cust_RegisteredOn`) VALUES ('" . addslashes(trim($_REQUEST['customer_email'])) . "', '" . sha1($password) . "', '" . addslashes(trim($_REQUEST['customer_firstname'])) . "', '" . addslashes(trim($_REQUEST['customer_lastname'])) . "', '" . addslashes(trim($_REQUEST['customer_username'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_consumer_key'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_consumer_secret'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_id'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_screenname'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_access_token'])) . "', '" . addslashes(trim($_REQUEST['customer_access_token_secret'])) . "', '" . addslashes(trim($_REQUEST['customer_hopcode'])) . "', 'yes', '" . addslashes($_REQUEST['customer_order_id']) . "', '" . addslashes($_REQUEST['Cust_Payment_Type']) . "', 2, '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "')");
				if ( $customer_Insert_sql ) {
					mysql_query("UPDATE `" . DB_PREFIX . "temp_customers` SET `Temp_UserName` = '" . addslashes(trim($_REQUEST['customer_username'])) . "', `Temp_Password` = '" . $password . "', `Temp_Status` = 'YES' WHERE `Temp_Email` = '" . addslashes(trim($_REQUEST['customer_email'])) . "'");
					
					$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
					$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
					$buzznewsSQL = mysql_query("INSERT INTO `" . BUZZDB_PREFIX . "customers`(`Cust_Email`, `Cust_Password`, `Cust_FirstName`, `Cust_LastName`, `Cust_UserName`, `Cust_API_Key`, `Cust_API_Secret`, `Cust_Twitter_ID`, `Cust_Screen_Name`, `Cust_Access_Token`, `Cust_Token_Secret`, `Cust_hopCode`, `Cust_Payment_Status`, `Cust_Order_ID`, `Cust_Payment_Type`, `Cust_Server`, `Cust_Last_Login_Time`, `Cust_RegisteredOn`) VALUES ('" . addslashes(trim($_REQUEST['customer_email'])) . "', '" . sha1($password) . "', '" . addslashes(trim($_REQUEST['customer_firstname'])) . "', '" . addslashes(trim($_REQUEST['customer_lastname'])) . "', '" . addslashes(trim($_REQUEST['customer_username'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_consumer_key'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_consumer_secret'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_id'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_screenname'])) . "', '" . addslashes(trim($_REQUEST['customer_twitter_access_token'])) . "', '" . addslashes(trim($_REQUEST['customer_access_token_secret'])) . "', '" . addslashes(trim($_REQUEST['customer_hopcode'])) . "', 'yes', '" . addslashes($_REQUEST['customer_order_id']) . "', '" . addslashes($_REQUEST['Cust_Payment_Type']) . "', 5, '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "')");
					if ( $buzznewsSQL ) {
						mysql_close($buzznew_connection);
					}
					$subject  = 'Welcome to Social Sonic';
					$body = '
					<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FCFCFD url(' . SITE_URL . 'images/body-bg.png) repeat 0 0;border:1px solid rgba(0, 0, 0, 0.15);font-family:Verdana,sans-serif;">
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td height="75" align="center" style="background:rgba(0, 0, 0, 0.15) repeat;border-bottom: 1px solid rgba(0, 0, 0, 0.15);">
											<img width="200" alt="" src="' . SITE_URL . 'images/ss-logo.png" />
										</td>
									</tr>
									<tr>
										<td height="100" align="center">
											<font color="#c31629" size="5">Welcome to Social Sonic</font><br/><br/>
											<font color="#5cb85c" size="2">
												<span>Thank you for registering with us.</span>
											</font><br /><br />
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding:0 20px;">
											<font color="#161616" size="2">
												<span>Dear ' . $_REQUEST['customer_firstname'] . ' ' . $_REQUEST['customer_lastname'] . ',<br /><br />Please login to your Social Sonic account with the following credentials:</span>
											</font><br /><br />
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding-left:20px;">
											<font color="#0e59ac" size="3">
												<span>
													Username: <strong>' . $_REQUEST['customer_username'] . '</strong><br />
													Password: <strong>' . $password . '</strong>
												</span>
											</font><br /><br />
											<font color="#161616" size="2">
												<span>Please keep this information confidential.</span>
											</font><br /><br />
											<font color="#f000" size="2">
												<span>For security purposes we advise you to change this password, <br />when you are prompted to after signing in to Social Sonic for the first time.</span>
											</font><br /><br />
											<font color="#000" size="2">
												<span>in case you have any questions, please call us on the number below<br /><br />Phone support:<br />1-702-960-4130<br />Thank you for choosing&nbsp;&nbsp;<a href="' . SITE_URL . '" style="color:#0e59ac; line-height:20px;">Social Sonic!</a><br /></span>
											</font><br /><br />
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding-left:20px;">
											<font color="#000" size="2">
												<span>Regards,<br />The <a href="#" style="color:#0e59ac;line-height:20px;">Social Sonic</a> Team.</span>
											</font><br /><br />
											<font color="#000" size="2">
												<span>P.S: This is a system generated email. Please do not reply.</span><br /><br />
											</font>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';
					emailChoose($_REQUEST['customer_email'], $subject, $body, $_REQUEST['customer_firstname'] . ' ' . $_REQUEST['customer_lastname']);
					echo json_encode(1);
				} else {
					echo json_encode(2);
				}
		} else {
			echo json_encode(3);
		}
	}	
}

function CustomerLoginProcess() {
	global $helper;
	
	$cookie_time = (3600 * 24 * 30);
	$data = array();
	
	$username = addslashes($_POST['username']);
	$password = addslashes($_POST['password']);
	
	$status = $helper->checkLoginData($username , sha1($password));
	if ( $status ) {
		$Customer = $helper->getCustomerById($_SESSION['Cust_ID']);
		if ( isset($_POST['remember']) ) {
			setcookie('__ssUn', $username, time() + $cookie_time, '/');
			setcookie('__ssPs', $password, time() + $cookie_time, '/');
		}
		mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_Last_Login_Time` = '" . date('Y-m-d H:i:s') . "' WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
		/*if ( $Customer['Cust_Server'] == 3 ) {
			mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_Server` = 2 WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
		}*/
		if ( $Customer['Cust_Login_Status'] == 'NO' ) {
			$data['logStatus'] = 1;
		} else {
			$data['logStatus'] = 2;
		}
		$data['Cust_ID'] = $_SESSION['Cust_ID'];
		$data['Cust_Server'] = $helper->redirectSserver($Customer['Cust_Server']);
	} else {
		$data['logStatus'] = 0;
		$data['Cust_ID'] = 0;
		$data['Cust_Server'] = $helper->redirectSserver(1);
	}
	if ( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
		echo json_encode($data);
	} else {
		if ( $data['logStatus'] == 1 ) {
			header('location:' . $data['Cust_Server'] . 'app-launching/?ref=1&cust=' . $data['Cust_ID'] . '&password=true');
		} elseif ( $data['logStatus'] == 2 ) {
			header('location:' . $data['Cust_Server'] . 'app-launching/?ref=2&cust=' . $data['Cust_ID'] . '&sscrm=true');
		} else {
			header('location:' . $data['Cust_Server']);
		}
	}
}

function ChangeAndSkipPassword() {
	global $helper;

	$Customer = $helper->getCustomerById($_SESSION['Cust_ID']);

	if ( isset($_REQUEST['skip']) ) {
		$passwordSQL = mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_Login_Status` = 'YES' WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
		if ( $passwordSQL ) {
			echo json_encode(2);
		} else {
			echo json_encode(0);
		}
	} else {
		$new_password = $_REQUEST['new_password'];
		$passwordSQL = mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `Cust_Password` = '" . sha1($new_password) . "', `Cust_Login_Status` = 'YES' WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
		if ( $passwordSQL ) {
			mysql_query("UPDATE `" . DB_PREFIX . "temp_customers` SET `Temp_Password` = '" . $new_password . "' WHERE `Temp_Email` = '" . addslashes(trim($Customer['Cust_Email'])) . "'");

			$subject  = 'You have successfully changed your password.';
			$body = '
			<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FCFCFD url(' . SITE_URL . 'images/body-bg.png) repeat 0 0;border:1px solid rgba(0, 0, 0, 0.15);font-family:Verdana,sans-serif;">
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="75" align="center" style="background:rgba(0, 0, 0, 0.15) repeat;border-bottom: 1px solid rgba(0, 0, 0, 0.15);">
									<img width="200" alt="" src="' . SITE_URL . 'images/ss-logo.png" />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding-left:20px;">
									<font color="#0e59ac" size="2.5">
										<br /><br /><span>Dear ' . $Customer['Cust_FirstName'] . ' ' . $Customer['Cust_LastName'] . ',<br /><br />You have successfully changed your password and now you can login with your new password.</span>
									</font><br /><br />
									<font color="#000" size="2">
										<span>If you have any questions regarding your account, please reach out to us on the number given below.<br /><br />Phone Support:<br />1-702-960-4130<br />Thank you for choosing&nbsp;&nbsp;<a href="' . SITE_URL . '" style="color:#0e59ac; line-height:20px;">Social Sonic!</a><br /></span>
									</font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding-left:20px;">
									<font color="#000" size="2">
										<span>Regards,<br />The <a href="#" style="color:#0e59ac;line-height:20px;">Social Sonic</a> Team.</span>
									</font><br /><br />
									<font color="#000" size="2">
										<span>P.S: This is a system generated email. Please do not reply.</span><br /><br />
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
			emailChoose($Customer['Cust_Email'], $subject, $body, $Customer['Cust_FirstName'] . ' ' . $Customer['Cust_LastName']);
			echo json_encode(1);
		} else {
			echo json_encode(0);
		}
	}
}

function CheckCurrentPassword() {
	global $helper;

	$valid   = true;
	
	$Customer = $helper->getCustomerById($_SESSION['Cust_ID']);

	$current_password = addslashes(sha1($_POST['current_password']));
	if ( $current_password == $Customer['Cust_Password'] ) {
		$valid   = true;
	} else {
		$valid   = false;
	}
	echo json_encode(
		$valid ? array('valid' => $valid) : array('valid' => $valid)
	);
}

function CheckValidScreenName() {
	global $helper;

	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

	$errors = '';
	$myInfluencers = explode(',', trim($_REQUEST['myInfluencers']));
	foreach ( $myInfluencers as $influencer ) {
		$influencer = trim($influencer, '@');
		$user_details 	= $twitteroauth->get('users/show', array("screen_name" => $influencer));
		if ( isset($user_details->errors) ) {
			$errors .= '<strong>@' . $influencer . '</strong> ';
		}
	}

	echo $errors;
}

function MemberSignupForm() {
	$member_first_name = isset($_REQUEST['memFirstname']) ? $_REQUEST['memFirstname'] : '';
	$member_last_name = isset($_REQUEST['memLastname']) ? $_REQUEST['memLastname'] : '';
	$member_email = isset($_REQUEST['memberEmail']) ? $_REQUEST['memberEmail'] : '';
	$selectTempSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "temp_customers` WHERE `Temp_Email` = '" . addslashes(trim($member_email)) . "'");
	if ( $selectTempSQL ) {
		if ( mysql_num_rows($selectTempSQL) == 0 ) {
			$url = SITE_URL . "customer-signup?inf_field_PostalCode=null&inf_field_FirstName=" . $member_first_name . "&inf_field_Country=null&inf_field_StreetAddress1=null&inf_field_Email=" . $member_email . "&inf_field_StreetAddress2=null&inf_field_Country2=null&inf_field_City2=null&inf_field_State=OR&inf_field_MiddleName=&inf_field_City=null&inf_field_PostalCode2=null&inf_field_State2=null&inf_field_Company=&inf_field_Address2Street2=null&inf_field_Address2Street1=null&inf_field_LastName=" . $member_last_name . "&orderId=00000";
			$subject  = 'Welcome to Social Sonic';
			$body = '<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FCFCFD url(' . SITE_URL . 'images/body-bg.png) repeat 0 0;border:1px solid rgba(0, 0, 0, 0.15);font-family:Verdana,sans-serif;">
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="75" align="center" style="background:rgba(0, 0, 0, 0.15) repeat;border-bottom: 1px solid rgba(0, 0, 0, 0.15);">
									<img width="200" alt="" src="' . SITE_URL . 'images/ss-logo.png" />
								</td>
							</tr>
							<tr>
								<td height="100" align="center">
									<font color="#c31629" size="5">Welcome to Social Sonic</font><br/><br/>
									<font color="#5cb85c" size="2"><span>Thank you for registering with us.</span></font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding:0 20px;">
									<font color="#161616" size="2">
										<span>Dear ' . $member_first_name . ' ' . $member_last_name . ',<br /><br />Please login to your Social Sonic account with the following link:</span>
									</font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding-left:20px;">
									<font color="#0e59ac" size="3"><span><a href = '.$url.'>Please Click Here</a></span></font><br /><br />
									<font color="#000" size="2">
										<span>in case you have any questions, please call us on the number below<br /><br />Phone support:<br />1-702-960-4130<br />Thank you for choosing&nbsp;&nbsp;<a href="' . SITE_URL . '" style="color:#0e59ac; line-height:20px;">Social Sonic!</a><br /></span>
									</font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding-left:20px;">
									<font color="#000" size="2">
										<span>Regards,<br />The <a href="#" style="color:#0e59ac;line-height:20px;">Social Sonic</a> Team.</span>
									</font><br /><br />
									<font color="#000" size="2">
										<span>P.S: This is a system generated email. Please do not reply.</span><br /><br />
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
			emailChoose($member_email, $subject, $body, $member_first_name . ' ' . $member_last_name);
			$inserTempSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "temp_customers`(`Temp_Firstname`, `Temp_Lastname`, `Temp_Email`, `Temp_Url`, `Temp_TransID`, `Temp_CreationDate`) VALUES ('".addslashes(trim($member_first_name))."', '".addslashes(trim($member_last_name))."','".addslashes(trim($member_email))."','".addslashes(trim($url))."','0000','". date('Y-m-d H:i:s')."')");
		}
	}
	header('location:' . SITE_URL . '123employee-agent/member-signup/');
}

function ResendRegURI() {
	$tempid = isset($_REQUEST['User']) ? $_REQUEST['User'] : '';
	$selectTempSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "temp_customers` WHERE `Temp_ID` = $tempid");
	if ( $selectTempSQL ) {
		if ( mysql_num_rows($selectTempSQL) > 0 ) {
			$tempData = mysql_fetch_assoc($selectTempSQL);
			$url = $tempData['Temp_Url'];
			$subject  = 'Welcome to Social Sonic';
			$body = '<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FCFCFD url(' . SITE_URL . 'images/body-bg.png) repeat 0 0;border:1px solid rgba(0, 0, 0, 0.15);font-family:Verdana,sans-serif;">
				<tr>
					<td>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="75" align="center" style="background:rgba(0, 0, 0, 0.15) repeat;border-bottom: 1px solid rgba(0, 0, 0, 0.15);">
									<img width="200" alt="" src="' . SITE_URL . 'images/ss-logo.png" />
								</td>
							</tr>
							<tr>
								<td height="100" align="center">
									<font color="#c31629" size="5">Welcome to Social Sonic</font><br/><br/>
									<font color="#5cb85c" size="2"><span>Thank you for registering with us.</span></font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding:0 20px;">
									<font color="#161616" size="2">
										<span>Dear ' . $tempData['Temp_Firstname'] . ' ' . $tempData['Temp_Lastname'] . ',<br /><br />Please login to your Social Sonic account with the following link:</span>
									</font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding-left:20px;">
									<font color="#0e59ac" size="3"><span><a href = ' . $url . '>Please Click Here</a></span></font><br /><br />
									<font color="#000" size="2">
										<span>in case you have any questions, please call us on the number below<br /><br />Phone support:<br />1-702-960-4130<br />Thank you for choosing&nbsp;&nbsp;<a href="' . SITE_URL . '" style="color:#0e59ac; line-height:20px;">Social Sonic!</a><br /></span>
									</font><br /><br />
								</td>
							</tr>
							<tr>
								<td align="left" valign="top" style="padding-left:20px;">
									<font color="#000" size="2">
										<span>Regards,<br />The <a href="#" style="color:#0e59ac;line-height:20px;">Social Sonic</a> Team.</span>
									</font><br /><br />
									<font color="#000" size="2">
										<span>P.S: This is a system generated email. Please do not reply.</span><br /><br />
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';
			emailChoose($tempData['Temp_Email'], $subject, $body, $tempData['Temp_Firstname'] . ' ' . $tempData['Temp_Lastname']);
		}
	}
	$_SESSION['Resend_Reg'] = '<div role="alert" class="alert alert-warning alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button> Email is sent to the user.</div>';
	header('location:' . SITE_URL . '123employee-agent/member-signup/');
}

function UpdateCustomerAccount() {
	$Cust_Field = addslashes(trim($_REQUEST['Cust_Field']));
	$Cust_Value = addslashes(trim($_REQUEST['Cust_Value']));

	$update = mysql_query("UPDATE `" . DB_PREFIX . "customers` SET `$Cust_Field` = '$Cust_Value' WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
	if ( $update ) {
		$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
		$buzznews_selected = mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
		$buzznews_table_sql = mysql_query("UPDATE `" . BUZZDB_PREFIX . "customers` SET `$Cust_Field` = '$Cust_Value' WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
		if ( $buzznews_table_sql ) {
			mysql_close($buzznew_connection);
		}
		echo 1;
	} else {
		echo 0;
	}
}

function SaveFilterSearch() {
	$filter_name = isset($_REQUEST['filter_name']) 	? $_REQUEST['filter_name'] 	: '';
	$bio_field 	 = isset($_REQUEST['bio_field']) 	? $_REQUEST['bio_field'] 	: '';
	$talks_about = isset($_REQUEST['talks_about']) 	? $_REQUEST['talks_about'] 	: '';
	$followers 	 = isset($_REQUEST['followers']) 	? $_REQUEST['followers'] 	: '';
	$following 	 = isset($_REQUEST['following']) 	? $_REQUEST['following'] 	: '';
	$follows 	 = isset($_REQUEST['follows']) 		? $_REQUEST['follows'] 		: '';
	$location 	 = isset($_REQUEST['location']) 	? $_REQUEST['location'] 	: '';

	$filterID = isset($_REQUEST['filterID']) ? $_REQUEST['filterID'] : '';
	$update = isset($_REQUEST['update']) ? $_REQUEST['update'] : '';

	if ( isset($_REQUEST['follows']) ) {
		$filterFrom = 'category';
	} else {
		$filterFrom = 'keyword';
	}

	if ( $update == 'update' ) {
		$CheckSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "filters` WHERE `Filter_ID` = $filterID AND `Cust_ID` = " . $_SESSION['Cust_ID']);
		if ( $CheckSQL ) {
			$CheckRows = mysql_num_rows($CheckSQL);
		} else {
			$CheckRows = 0;
		}
		$html['SaveSearch'] = $CheckRows;
		if ( $CheckRows == 1 ) {
			$UpdateSQL = mysql_query("UPDATE `" . DB_PREFIX . "filters` SET `Filter_Name` = '$filter_name', `Filter_From` = '$filterFrom', `Bio` = '$bio_field', `Talks_About` = '$talks_about', `Followers` = '$followers', `Following` = '$following', `Follows` = '$follows', `Location` = '$location' WHERE `Filter_ID` = $filterID AND `Cust_ID` = " . $_SESSION['Cust_ID']);
			if ( $UpdateSQL ) {
				$html['filterID'] = $filterID;
				$html['code'] = 1;
			} else {
				$html['code'] = 0;
			}
		} else {
			$html['code'] = 3;
		}
	} else {
		$CheckSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "filters` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);
		if ( $CheckSQL ) {
			$CheckRows = mysql_num_rows($CheckSQL);
		} else {
			$CheckRows = 0;
		}
		$html['SaveSearch'] = $CheckRows;
		if ( $CheckRows < 5 ) {
			$InsertSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "filters`(`Cust_ID`,`Filter_Name`, `Filter_From`, `Bio`, `Talks_About`, `Followers`, `Following`, `Follows`, `Location`, `Filter_On`) VALUES (" . $_SESSION['Cust_ID'] . ", '$filter_name', '$filterFrom', '$bio_field', '$talks_about', '$followers', '$following', '$follows', '$location', NOW())");
			if ( $InsertSQL ) {
				$html['filterID'] = mysql_insert_id();
				$html['code'] = 1;
			} else {
				$html['code'] = 0;
			}
		} else {
			$html['code'] = 3;
		}
	}
	echo json_encode($html);
}

function GetSingleFilter() {
	$filterID = $_REQUEST['filterID'];
	$action = $_REQUEST['action'];

	$CheckSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "filters` WHERE `Filter_ID` = $filterID AND `Cust_ID` = " . $_SESSION['Cust_ID'] . " LIMIT 0, 1");
	if ( $CheckSQL ) {
		$CheckRows = mysql_num_rows($CheckSQL);
	} else {
		$CheckRows = 0;
	}
	if ( $CheckRows > 0 ) {
		$FilterData = mysql_fetch_assoc($CheckSQL);
		if ( $action == 'edit' ) {
			$html['code'] = 2;
			$html['field'] = $FilterData;
		} elseif ( $action == 'remove' ) {
			$RemoveSQL = mysql_query("DELETE FROM `" . DB_PREFIX . "filters` WHERE `Filter_ID` = $filterID AND `Cust_ID` = " . $_SESSION['Cust_ID']);
			if ( $RemoveSQL ) {
				$html['code'] = 3;
			}
		}
	}
	echo json_encode($html);
}

function GetAllCustomers() {
	$requestData = $_REQUEST;
	$columns = array( 
		0 => 'Cust_FirstName', 
		1 => 'Cust_LastName',
		2 => 'Cust_Email',
		3 => 'Cust_RegisteredOn'
	);

	$sql  = "SELECT `Cust_ID` ";
	$sql .= " FROM `" . DB_PREFIX . "customers`";
	$query = mysql_query($sql);
	$totalData = mysql_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT `Cust_ID`, `Cust_FirstName`, `Cust_LastName`, `Cust_Email`, `Cust_RegisteredOn` ";
	$sql .= " FROM `" . DB_PREFIX . "customers` WHERE 1 = 1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`Cust_FirstName` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `Cust_LastName` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `Cust_Email` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `Cust_RegisteredOn` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysql_query($sql);
	$totalFiltered = mysql_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysql_query($sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysql_fetch_assoc($query) ) {
		$nestedData = array();

		$nestedData[] = "<input type='checkbox' class='deleteRow' value='" . $row['Cust_ID'] . "' />";
		$nestedData[] = $row["Cust_FirstName"];
		$nestedData[] = $row["Cust_LastName"];
		$nestedData[] = $row["Cust_Email"];
		$nestedData[] = $row["Cust_RegisteredOn"];
		$data[] = $nestedData;
		$i++;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);
	echo json_encode($json_data);
}

function CustomerDataDelete() {
	global $mongoDb;

 	$data_ids = $_REQUEST['data_ids'];
	$data_id_array = explode(",", $data_ids); 
	if ( !empty($data_id_array) ) {
		foreach ( $data_id_array as $id ) {
			$custSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_ID` = $id LIMIT 0, 1");
			$customer = mysql_fetch_assoc($custSQL);
			$custEmail = $customer['Cust_Email'];
			mysql_query("DELETE FROM `" . DB_PREFIX . "temp_customers` WHERE `Temp_Email` = '$custEmail'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "customers` WHERE `Cust_ID` = $id");
			mysql_query("DELETE FROM `" . DB_PREFIX . "direct_message` WHERE `user_id` = '$id'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "dm_status` WHERE `user_id` = '$id'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "favourite` WHERE `user_id` = '$id'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "filters` WHERE `Cust_ID` = $id");
			mysql_query("DELETE FROM `" . DB_PREFIX . "followers_cursor` WHERE `User_ID` = '$id'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "influencers` WHERE `user_id` = $id");
			mysql_query("DELETE FROM `" . DB_PREFIX . "influencers_cursor` WHERE `Cust_ID` = $id");
			mysql_query("DELETE FROM `" . DB_PREFIX . "nurtureship` WHERE `user_id` = '$id'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = '$id'");
			mysql_query("DELETE FROM `" . DB_PREFIX . "schedule_tweet` WHERE `user_id` = '$id'");

			$buzznew_connection = mysql_connect(BUZZNEWS_DB_SERVER_NAME, BUZZNEWS_DB_USER_NAME, BUZZNEWS_DB_PASSWORD);
			$buzznews_selected 	= mysql_select_db(BUZZNEWS_DB_NAME, $buzznew_connection);
			mysql_query("DELETE FROM `" . BUZZDB_PREFIX . "customers` WHERE `Cust_ID` = $id");

			$remove_followers 	= $mongoDb->user_followers->remove(array("user_id" => $id));
		   	$remove_categories 	= $mongoDb->prospect_influencers->remove(array("search_user_id" => $id));
		   	$remove_keywords 	= $mongoDb->prospect_keywords->remove(array("search_user_id" => $id));
		}
		echo '<div role="alert" class="alert alert-warning alert-dismissible fade in session-success"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button> Selected customer(s) are successfully deleted.</div>';
	} else {
		echo '<div role="alert" class="alert alert-warning alert-dismissible fade in session-success"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button> Something went wrong. Please try again later.</div>';
	}
}

function CheckCustomerSession() {
	if ( !isset($_SESSION['Cust_ID']) ) {
		$data['sessionCheck'] = 1;
	} else {
		$data['sessionCheck'] = 0;
	}
	echo json_encode($data);
}

function GetProspectsLast10Tweets() {
	global $helper;

	$data = array();

	$userid = $_REQUEST['userid'];
	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Customer = $helper->getCustomerById($Cust_ID);
	$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);
	$tweetData = $twitteroauth->get('statuses/user_timeline', array("user_id" => $userid, "count" => "10"));

	if ( isset($tweetData->error) && $tweetData->error == 'Not authorized.' ) {
		$data['error'] = 'This Profile is Protected!';
    } elseif ( count($tweetData) > 0 ) {
    	if ( !isset($tweetData->errors) ) {
    		$i = 0;
    		foreach ( $tweetData as $tweet ) {
    			$data['tweets'][$i]['id'] 	 = $tweet->id_str;
    			$data['tweets'][$i]['text'] = $tweet->text;
    			$i++;
    		}
    	}
    }
    echo json_encode($data);
}

function UnsubscribeCustomer() {
	global $helper;

	$data = array();
	$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
	$Cust_Name = addslashes($_REQUEST['name']);
	$Cust_Email = addslashes($_REQUEST['email']);
	$Cust_Reason = addslashes($_REQUEST['cancel_reason']);
	$other = (!empty($_REQUEST['other']) ? addslashes($_REQUEST['other']) : '');
	$description = (!empty($_REQUEST['description']) ? addslashes($_REQUEST['description']) : '');
	
	$cancelRequestSQL = mysql_query("INSERT INTO `" . DB_PREFIX . "unsubscriptions`(`Cust_ID`, `Cust_Name`, `Cust_Email`, `Cancel_Reason`, `Other_Reason`, `Cancel_Desc`, `Request_Time`) VALUES (".$Cust_ID.",'".$Cust_Name."','".$Cust_Email."','".$Cust_Reason."','".$other."','".$description."','".date('Y-m-d H:i:s') ."')");
	if ( $cancelRequestSQL ) {
		echo 1;
	} else {
		echo 0;
	}
}