<?php /*! Index of this Application */

require_once(dirname(__FILE__) . "/config/config.php");

if ( isset($_SESSION['Cust_ID']) ) {

   	header('location:' . SITE_URL . 'twitter-crm/');

} else {

	header('location:' . SERVER1_URL . 'welcome/');

}

exit;