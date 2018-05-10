<?php 
define('ADMIN', true);

require_once(dirname(dirname(__FILE__)) . '/config/config.php');
session_destroy();
header('location:' . SITE_URL . 'admin/');
exit;