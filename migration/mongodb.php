<?php 

/* This file contains mongodb collections details */
require_once(dirname(dirname(__FILE__)) . '/config/config.php');
require_once(dirname(dirname(__FILE__)) . "/config/mongodb.php");

$mongoDb->createCollection("prospect_keywords", array("autoIndexId" => 1));
$mongoDb->prospect_keywords->ensureIndex(array("user_id" => 1, "search_user_id" => 1, "prospect_id" => 1, "status" => 1));

$mongoDb->createCollection("prospect_influencers", array("autoIndexId" => 1));
$mongoDb->prospect_influencers->ensureIndex(array("user_id" => 1, "search_user_id" => 1, "prospect_id" => 1, "influncer_id" => 1, "status" => 1));

$mongoDb->createCollection("user_followers", array("autoIndexId" => 1));
$mongoDb->user_followers->ensureIndex(array("user_screen_name" => 1, "user_id" => 1));

$mongoDb->createCollection("influence_followers", array("autoIndexId" => 1));
$mongoDb->influence_followers->ensureIndex(array("influncer_id" => 1));