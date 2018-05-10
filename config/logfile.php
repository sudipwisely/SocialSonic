<?php
class logfile{
	function write($the_string ){
		if( $fh = @fopen( dirname(dirname(__FILE__)) . "/cron/logfile.txt", "a+" ) ){
			fputs( $fh, $the_string, strlen($the_string) );
			fclose( $fh );
			return( true );
		} else{
			return( false );
		}
	}
}

$lf = new logfile();
?>