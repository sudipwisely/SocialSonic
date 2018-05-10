<?php 

/* Configuration of session variable */

ini_set('session.name', 'SocialSonic');
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 0);
ini_set('session.gc_maxlifetime', 1440);
ini_set('session.save_handler', 'files');
ini_set('session.save_path', dirname(dirname(__FILE__)) . '/tmp/sessions');
session_start();