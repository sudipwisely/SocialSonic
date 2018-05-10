<?php

/* Configuration for the MongoDB */

$mongo = new MongoClient(MONGODB_SERVER_NAME);
if ( !$mongo ) {
	die("Unable to connect with mongodb");
} else {
	$mongoDb=$mongo->socialsonic;
	if ( !$mongoDb ) {
		die("Unable to open  database");
	}
}