<?php /*! MongoDB Remove of this Application */

require_once(dirname(__FILE__) . "/config/config.php");

/*mysql_query("DELETE FROM `" . DB_PREFIX . "nurtureship`");
mysql_query("DELETE FROM `" . DB_PREFIX . "favourite`");
mysql_query("DELETE FROM `" . DB_PREFIX . "followers_cursor`");*/
mysql_query("DELETE FROM `" . DB_PREFIX . "influencers_cursor` WHERE `Cust_ID` = " . $_SESSION['Cust_ID']);

/*$document = $mongoDb->prospect_keywords->find();
echo '<pre>'; print_r($document->count()); echo '</pre>';*/

/*$document = $mongoDb->prospect_influencers->find();
echo '<pre>'; print_r($document->count()); echo '</pre>';

$document = $mongoDb->user_followers->find();
echo '<pre>'; print_r($document->count()); echo '</pre>';

$document = $mongoDb->prospect_keywords->remove();
echo '<pre>'; print_r($document); echo '</pre>';*/

$document = $mongoDb->prospect_influencers->remove(array('search_user_id' => $_SESSION['Cust_ID']));
echo '<pre>'; print_r($document); echo '</pre>';

/*$document = $mongoDb->user_followers->remove();
echo '<pre>'; print_r($document); echo '</pre>';*/