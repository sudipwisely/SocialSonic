<?php 

/* Class SocialSonic */

class SocialSonic {
	var $skey  = "SocialSonicEncKey20142015";

	public function getCustomerById($CustId) {
		$CustSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_ID` = $CustId LIMIT 0, 1");
		if ( $CustSQL ) {
			$CustRow = mysql_num_rows($CustSQL);
		} else {
			$CustRow = 0;
		}
		if ( $CustRow == 1 ) {
			$Customer = mysql_fetch_assoc($CustSQL);
		} else {
			$Customer = array();
		}
		return $Customer;
	}

	public function getProspectByCustId($CustId) {
		$ProspectSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "prospects` WHERE `Cust_ID` = $CustId LIMIT 0, 1");
		if ( $ProspectSQL ) {
			$ProspectRow = mysql_num_rows($ProspectSQL);
		} else {
			$ProspectRow = 0;
		}
		if ( $ProspectRow == 1 ) {
			$Prospect = mysql_fetch_assoc($ProspectSQL);
		} else {
			$Prospect = array();
		}
		return $Prospect;
	}
	
	public function getCategory() {
		$Category = array();
		$CategorySQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "categories`");
		if ( $CategorySQL ) {
			$CategoryRow = mysql_num_rows($CategorySQL); 
		} else {
			$CategoryRow = 0;
		}
		if ( $CategoryRow > 0 ) {
			while ($cat = mysql_fetch_assoc($CategorySQL)){
				$Category[] = $cat;
			}
		} else {
			$Category = array();
		}
		return $Category;
	}

	public function checkLoginData($username, $password) {
		$LoginSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "customers` WHERE `Cust_UserName` = '" . $username . "' AND `Cust_Password` = '" . $password . "'");
		if ( $LoginSQL ) {
			if ( mysql_num_rows($LoginSQL) == 1 ) {
				$Customer = mysql_fetch_assoc($LoginSQL);
				$_SESSION['Cust_ID'] = $Customer['Cust_ID'];	// Store Customer's Session
				$loginstatus = true;
			} else {
				$loginstatus = false;
			}
		} else {
			$loginstatus = false;
		}
		return $loginstatus;
	}
	
	public function loginWithCookie($username, $password) {
		$status = $this->checkLoginData($username , sha1($password));
		if ( $status ) {
			 header("Location: ".SITE_URL."twitter-crm/");
		}
	}

	public function create_slug($string) {
	   $slug = preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($string));
	   return $slug;
	}

	public function getTweetConversation($mention_id) {
		global $helper, $twConv;

		$Cust_ID = isset($_SESSION['Cust_ID']) ? $_SESSION['Cust_ID'] : '';
		$Customer = $helper->getCustomerById($Cust_ID);
		$twitteroauth = new TwitterOAuth($Customer['Cust_API_Key'], $Customer['Cust_API_Secret'], $Customer['Cust_Access_Token'], $Customer['Cust_Token_Secret']);

		$method = 'data';
		$conversate = CONVERSATE_AFTER;

		$ChatSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Cust_ID` = $Cust_ID AND `Mention_ID` = '$mention_id' LIMIT 0, 1");
		$ChatData = mysql_fetch_assoc($ChatSQL);

		$me = $twitteroauth->get('users/show', array("user_id" => $Customer['Cust_Twitter_ID']));
		$lead = $twitteroauth->get('users/show', array("user_id" => $ChatData['Recipient_ID']));

		$conversations = $twConv->fetchConversion($mention_id, $method, $conversate);
		foreach ( $conversations['tweets'] as $tweet ) {
			$checkSQL = mysql_query("SELECT * FROM `" . DB_PREFIX . "lead_responses` WHERE `Tweet_ID` = '" . $tweet['id'] . "'");
			if ( $checkSQL ) {
				if ( mysql_num_rows($checkSQL) == 0 ) {
					if ( strtolower($tweet['username']) == strtolower($me->screen_name) ) {
						$from_id 	 = $me->id_str;
						$from_screen = $me->screen_name;
						$to_id 		 = $lead->id_str;
						$to_screen 	 = $lead->screen_name;
					} else {
						$from_id 	 = $lead->id_str;
						$from_screen = $lead->screen_name;
						$to_id 		 = $me->id_str;
						$to_screen 	 = $me->screen_name;
					}
					mysql_query("INSERT INTO `" . DB_PREFIX . "lead_responses`(`Cust_ID`, `Recipient_ID`, `Mention_ID`, `Tweet_ID`, `Tweet_Text`, `From_ID`, `To_ID`, `From_ScreenName`, `To_ScreenName`, `Response_Status`, `Send_Time`, `Insert_Time`) VALUES ($Cust_ID, '" . $ChatData['Recipient_ID'] . "', '$mention_id', '" . $tweet['id'] . "', '" . $tweet['content'] . "', '$from_id', '$to_id', '$from_screen', '$to_screen', 'unread', '" . $tweet['date'] . "', NOW())");
				}
			}
		}
	}
	
	public function redirectSserver($Server){
		switch($Server){
			case 1: 
				return SERVER1_URL;
				break;
			case 2: 
				return SERVER2_URL;
				break;
			case 3: 
				return SERVER3_URL;
				break;
			case 4: 
				return SERVER4_URL;
				break;
			case 5: 
				return SERVER5_URL;
				break;
			default:
				return SITE_URL;
				break;
		}
	}
	
	public function alert($str) {
		echo "<script type='text/javascript'>alert('$str');</script>";
	}

	public function confirm() {
		echo "<script type='text/javascript'>confirm('Dou you want to edit?');</script>";
	}

	public function go($str) {
		echo "<script type='text/javascript'>window.location.href = '$str'; </script>";
	}

	public function getmaxval($tablename, $fieldname, $cond) {
		if ( $cond ) {
			$qry = "SELECT MAX(" . $fieldname . ") FROM " . $tablename . " WHERE " . $cond;
		} else {
			$qry = "SELECT MAX(" . $fieldname . ") FROM " . $tablename;
		}
		
		$result = mysql_query($qry);
		$row = mysql_fetch_row($result);
		if ( $row[0] == '0' ) {
			$mval = 1;
		} else {
			$mval = $row[0] + 1;
		}
		return $mval;
	}
	
	public function getreports($qrys) {
		$qry = $qrys;
		$result = mysql_query($qry);
		return $result;
	}

	public function getval($tablename, $fieldname, $condition) {
		$mval = '';
		$qry = "SELECT " . $fieldname . " FROM " . $tablename . " WHERE " . $condition;
		$result = mysql_query($qry);		
		$row = mysql_fetch_row($result);
		if ( !empty($row[0]) ) {
			$mval = $row[0];
		} else {
			$mval = 0;
		}
		return $mval;
	}
	
	public function sumval($tablename, $fieldname, $condition) {
		$mval = 0;
		if ( !empty($condition) ) {
			$qry = "SELECT SUM(" . $fieldname . ") FROM " . $tablename . " WHERE " . $condition;
		} else {
			$qry = "SELECT SUM(" . $fieldname . ") FROM " . $tablename;
		}
		$result = mysql_query($qry);
		$row = mysql_fetch_row($result);
		if ( !empty($row[0]) ) {
			$mval = $row[0];
		}
		return $mval;
	}
	
	public function tmret($fromtime, $totime) {
		$start_date = new DateTime($fromtime);
		$since_start = $start_date->diff(new DateTime($totime));
		$days = $since_start->d;
		$hrs = $since_start->h;
		$mins = $since_start->i;
		if ( $days > 0 ) {
			$chrs = $days * 24;
			$hrs = $hrs + $chrs;
		}
		if ( $hrs > 0 ) {
			$cmins = $hrs * 60;
			$mins = $mins + $cmins;
		}
		return $mins;
	}

	public function strtimediff($fromtime, $totime) {
		$start_date = new DateTime($fromtime);
		$since_start = $start_date->diff(new DateTime($totime));
		$days = $since_start->d;
		$hrs = $since_start->h;
		$mins = $since_start->i;	
		$amins = $since_start->i;		
		if ( $days > 0 ) {
			$chrs = $days * 24;
			$hrs = $hrs + $chrs;
		}
		if ( $hrs > 0 ) {
			$cmins = $hrs * 60;
			$mins = $mins + $cmins;
		}
		$retval = $hrs . ' hrs ' . $amins . ' mins ';
		return $retval;
	}

	public function strtimediff2($fromtime, $totime) {
		$start_date = new DateTime($fromtime);
		$since_start = $start_date->diff(new DateTime($totime));
		$days = $since_start->d;
		$hrs = $since_start->h;
		$mins = $since_start->i;
		$amins = $since_start->i;
		if ( $days > 0 ) {
			$chrs = $days * 24;
			$hrs = $hrs + $chrs;
		}
		if ( $hrs > 0 ) {
			$cmins = $hrs * 60;
			$mins = $mins + $cmins;
		}
		if ( $hrs > 24 ) {
			$retval = '1 day';
		} else {
			$retval = $hrs . ' hrs ' . $amins . ' mins ';
		}
		return $retval;
	}

	public function timedifcal($fromtime, $totime) {
		$start_date = new DateTime($fromtime);
		$since_start = $start_date->diff(new DateTime($totime));
		$days = $since_start->d;
		$hrs = $since_start->h;
		$mins = $since_start->i;
		$amins = $since_start->i;
		if ( $days > 0 ) {
			$chrs = $days * 24;
			$hrs = $hrs + $chrs;
		}
		if ( $hrs > 0 ) {
			$cmins = $hrs * 60;
			$mins = $mins + $cmins;
		}
		$retval = $hrs;
		return $retval;
	}

	public function timeformat_previousposts($logtime, $now) {
		$difftime = new DateTime($logtime);
		$time_obj = new DateTime($logtime);
		$difftime = $difftime->diff(new DateTime($now));
		if ( $difftime->d <= 0 ) {
			if ( $difftime->h <= 0 ) {
				$timediff = "Posted " . $difftime->i . " mins ago";
				return $timediff;
			}
			$timediff = $difftime->h . " hours and " . $difftime->i . " mins ago";
			return $timediff;
		} else if ( $difftime->d > 0 ) {
			$dates = $time_obj->format('Y-m-d');
			$time = $time_obj->format('H:i:s');
			$dates = explode("-", $dates);
			$time = explode(":", $time);
			$timediff = $time[0] . ":" . $time[1] . " on " . $dates[2] . "/" . $dates[1] . "/" . $dates[0];
			return $timediff;
		}
	}

	public function timeformat_upcomingposts($logtime, $now) {
		$difftime = new DateTime($now);
		$time_obj = new DateTime($logtime);
		$difftime = $difftime->diff(new DateTime($logtime));
		if ( $difftime->d <= 0 ) {
			if ( $difftime->h <= 0 ) {
				$time_to_post = "Will be posted in " . $difftime->i . " mins from now";
				return $time_to_post;
			}
			$time_to_post = "Will be posted in " . $difftime->h . " hours and " . $difftime->i . " mins from now";
			return $time_to_post;
		} else if ( $difftime->d > 0 ) {
			$dates = $time_obj->format('Y-m-d');
			$time = $time_obj->format('H:i:s');
			$dates = explode("-", $dates);
			$time = explode(":", $time);
			$timediff = "Will be posted on " . $dates[2] . "/" . $dates[1] . "/" . $dates[0] . " at " . $time[0] . ":" . $time[1];
			return $timediff;
		}
	}

	private function getDateInterval($diff) {
	    $h = intval($diff / 60);
	    $m = intval($diff - ($h * 60));
	    $di = new DateInterval("P0Y0M0DT" . $h . "H" . $m . "M");
	    return $di;
	}

	public function getUTC($ndate) {
	    $ndate = new DateTime($ndate);
	    if ( isset($_SESSION['timeoffset']) ) {
	        $diff = intval($_SESSION['timeoffset']);
	        if ( $diff < 0 ) {
	            $ndate->sub($this->getDateInterval(-$diff)); 
	        } else {
	            $ndate->add($this->getDateInterval($diff)); 
	        }
	    }
	    return date($ndate->format('Y-m-d H:i:s'));
	}

	public function getTimeZoneTime($ndate) {
	    $ndate = new DateTime($ndate);
	    if ( isset($_SESSION['timeoffset']) ) {
	        $diff = intval($_SESSION['timeoffset']);
	        if ( $diff < 0 ) {
	            $ndate->add($this->getDateInterval(-$diff)); 
	        } else {
	            $ndate->sub($this->getDateInterval($diff)); 
	        }
	    }
	    return date($ndate->format('Y-m-d H:i:s'));
	}

	public function getTimeZoneSpecificTime($ndate) {
	    $ndate = new DateTime($ndate);
	    if ( isset($_SESSION['timeoffset']) ) {
	        $diff = intval($_SESSION['timeoffset']);
	        if ( $diff < 0 ) {
	            $ndate->add($this->getDateInterval(-$diff)); 
	        } else {
	            $ndate->sub($this->getDateInterval($diff)); 
	        }
	    }
	    return date($ndate->format('Y-m-d H:i:s'));
	}

	function clean_data($input) {
		$input = trim(htmlentities(strip_tags($input, ",")));
		if ( get_magic_quotes_gpc() )
			$input = stripslashes($input);
		$input = mysql_real_escape_string($input);
		return $input;
	}

	public function timediff15($logtime, $now) {
		$difftime = new DateTime($logtime);
		$time_obj = new DateTime($logtime);
		$difftime = $difftime->diff(new DateTime($now));
		if ( $difftime->d >= 14 || $difftime->d <= 15 ) {
			return true;
		}
		return false;
	}

	function is_session_started() {
	    if ( php_sapi_name() !== 'cli' ) {
	        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
	            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
	        } else {
	            return session_id() === '' ? FALSE : TRUE;
	        }
	    }
	    return FALSE;
	}

	function check_login_status($login_url) {
		if ( !isset($_SESSION['user_id']) ) {
			header("location:" . $login_url);
			exit();
		}
	}
	
	function add_date($givendate, $day = 0, $mth = 0, $yr = 0) {
		$cd = strtotime($givendate);
		$arr = explode(" ", $givendate);
		$addT = explode(":", $arr[1]);
		$hours = $addT[0];
		$mins = $addT[1];
		$secs = $addT[2];
		$newdate = date('Y-m-d', mktime(0, 0, 0, date('m', $cd) + $mth, date('d', $cd) + $day, date('Y', $cd) + $yr)) . " " . $hours . ":" . $mins . ":" . $secs;
		return $newdate;
	}

	public  function safe_b64encode($string) {
	    $data = base64_encode($string);
	    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
	    return $data;
	}

	public function safe_b64decode($string) {
	    $data = str_replace(array('-', '_'), array('+', '/'), $string);
	    $mod4 = strlen($data) % 4;
	    if ( $mod4 ) {
	        $data .= substr('====', $mod4);
	    }
	    return base64_decode($data);
	}

	public  function engEncode($value) {
	    if ( !$value ) {
	    	return false;
	   	}
	    $text = $value;
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
	    return trim($this->safe_b64encode($crypttext));
	}

	public function engDecode($value){
	    if ( !$value ) {
	    	return false;
	    }
	    $crypttext = $this->safe_b64decode($value);
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
	    return trim($decrypttext);
	}

	public function busniess_data() {
	    $sql = "SELECT * FROM `user_business_table` WHERE user_id = " . $_SESSION['user_id'];
		$result = mysql_query($sql);
		return mysql_num_rows($result);
	}

	public function keyword_buzz_data() {
	    $sql = "SELECT * FROM `tag_search` WHERE user_id = " . $_SESSION['user_id'];
		$result = mysql_query($sql);
		return mysql_num_rows($result);
	}
}

$helper = new SocialSonic();