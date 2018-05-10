<?php 
// Website Title and URL
define("SITE_TITLE", 		 "SocialSonic");
define("BLOG_URL", 			 "http://buzznews.co/");
define('CUR_HOST', 			 $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');

// CRON Servers
define("CRON_SERVER1_URL", 			 "http://172.31.2.188/");
define("CRON_SERVER2_URL", 			 "http://172.31.1.143/");
define("CRON_SERVER3_URL", 			 "http://172.31.8.215/");
/*define("CRON_SERVER4_URL", 			 "http://172.31.0.235/");
define("CRON_SERVER5_URL", 			 "http://172.31.0.209/");
define("CRON_SERVER6_URL", 			 "http://172.31.3.102/");*/


// Server URLs
define("SERVER1_URL", 			 "http://socialsoniccrm.com/");
define("SERVER2_URL", 			 "http://52.52.154.232/");
define("SERVER3_URL", 			 "http://52.52.32.96/");
/*define("SERVER4_URL", 			 "http://52.8.107.222/");
define("SERVER5_URL", 			 "http://52.52.178.217/");
define("SERVER6_URL", 			 "http://52.53.101.223/");*/

if ( CUR_HOST == SERVER1_URL || CUR_HOST == CRON_SERVER1_URL ) {
	define('CUR_SERVER', 1);
	define("SITE_URL",			 "http://socialsoniccrm.com/");
	define("LOGIN_REDIRECT_URL", "http://socialsoniccrm.com/welcome/");

	// MongoDB database Details
	define("MONGODB_SERVER_NAME",	"mongodb://172.31.13.26:27017/");
	define("MONGODB_USER_NAME",		"");
	define("MONGODB_PASSWORD", 		"");
} elseif ( CUR_HOST == SERVER2_URL || CUR_HOST == CRON_SERVER2_URL ) {
	define('CUR_SERVER', 2);
	define("SITE_URL",			 "http://52.52.154.232/");
	define("LOGIN_REDIRECT_URL", "http://52.52.154.232/welcome/");

	// MongoDB database Details
	define("MONGODB_SERVER_NAME",	"mongodb://172.31.13.6:27017/");
	define("MONGODB_USER_NAME","");
	define("MONGODB_PASSWORD", "");
} elseif ( CUR_HOST == SERVER3_URL || CUR_HOST == CRON_SERVER3_URL ) {
	define('CUR_SERVER', 3);
	define("SITE_URL",			 "http://52.8.107.222/");
	define("LOGIN_REDIRECT_URL", "http://52.8.107.222/welcome/"); 

	// MongoDB database Details
	define("MONGODB_SERVER_NAME",	"mongodb://172.31.9.101:27017/");   
	define("MONGODB_USER_NAME",		"");
	define("MONGODB_PASSWORD", 		"");
}

 /*elseif ( CUR_HOST == SERVER4_URL || CUR_HOST == CRON_SERVER4_URL ) {
	define("SITE_URL",			 "http://52.52.32.96/");
	define("LOGIN_REDIRECT_URL", "http://52.52.32.96/welcome/"); 

	// MongoDB database Details
	define("MONGODB_SERVER_NAME",	"mongodb://172.31.15.99:27017/");   
	define("MONGODB_USER_NAME",		"");
	define("MONGODB_PASSWORD", 		"");
} elseif ( CUR_HOST == SERVER5_URL || CUR_HOST == CRON_SERVER5_URL ) {
	define("SITE_URL",			 "http://52.52.178.217/");
	define("LOGIN_REDIRECT_URL", "http://52.52.178.217/welcome/");

	// MongoDB database Details
	define("MONGODB_SERVER_NAME",	"mongodb://172.31.2.106:27017/");   
	define("MONGODB_USER_NAME",		"");
	define("MONGODB_PASSWORD", 		"");
} elseif ( CUR_HOST == SERVER6_URL || CUR_HOST == CRON_SERVER6_URL ) {
	define("SITE_URL",			 "http://52.53.101.223/");
	define("LOGIN_REDIRECT_URL", "http://52.53.101.223/welcome/");

	// MongoDB database Details
	define("MONGODB_SERVER_NAME",	"mongodb://172.31.11.200:27017/");
	define("MONGODB_USER_NAME",		"");
	define("MONGODB_PASSWORD", 		"");
}*/

// MySQL Database Details
define("DB_SERVER_NAME",	"socialsonic.cxetlvz7u3w2.us-west-1.rds.amazonaws.com");
define("DB_USER_NAME",		"socialsonic");
define("DB_PASSWORD",		"socialsonic");
define("DB_NAME",			"socialsonic");
define("DB_PREFIX", 		"ss_");

// Buzznews MySQL Database Details
define("BUZZNEWS_DB_SERVER_NAME", 	"166.62.28.98"); 
define("BUZZNEWS_DB_USER_NAME", 	"buzznews");
define("BUZZNEWS_DB_PASSWORD", 		"RTo*mBqy8OFA"); 
define("BUZZNEWS_DB_NAME", 			"buzznews");
define("BUZZDB_PREFIX",             "ss_");

// PHPMYADMIN Details
define("MYADMIN_URL",		"http://52.8.12.182/pmyadmin");
define("MYADMIN_USERNAME",	"socialsonic");
define("MYADMIN_PASSWORD",	'Social$onic123#');

// Twitter Auth
define("ADMIN_CONSUMER_KEY", 	 "ikCUwRCtsiYUUWO1Kn0Z3Phbe");
define("ADMIN_CONSUMER_SECRET",  "deCYhmv60gXMflN16Iomdx79osAtZSLVEfbcetkYupDZy4Df5q");
define("ADMIN_TWITTER_USERNAME", "engagewise3");
define("ADMIN_TWITTER_ID", 		 "718077998751043584");
define("ADMIN_ACCESS_TOKEN", 	 "718077998751043584-zH9MI7U0QQQd0YXQQInQ8kfYG9Wabbu");
define("ADMIN_TOKEN_SECRET", 	 "mJoArfzzM7pFs87xfkbnXy7cVgwxFKb9xXqk8Z6Vfhhcu");

// Email Constant
define("PHPMAILER_SMTPSECURE", 	"ssl");
define("PHPMAILER_HOST", 		"email-smtp.us-east-1.amazonaws.com");
define("PHPMAILER_PORT", 		"465");
define("PHPMAILER_USERNAME",	"AKIAIXQ6DMU6LSYBKROA");
define("PHPMAILER_PASSWORD",	"AlsYaqN4U8AKxjUmuB4q66E2rlqEMQVYaUHcJDZkuJ1W");
define("PHPMAILER_FROM",		"support@123employee.com");
define("PHPMAILER_FROMNAME", 	"SocialSonic");
define("PHPMAILER_WORDWRAP", 	"50");

// Login Credentials
define("ADMIN_USERNAME", "SocialSonic");
define("ADMIN_PASSWORD", "S0c!@lS0n!c");