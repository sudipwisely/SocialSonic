<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Thank you</title>
</head>
<body>
	<?php
	$nam = $_POST["inputname"];
	$ema = trim($_POST["inputemail"]);
	$subj = $_POST["inputsubject"];
	$str = $_POST["inputmessage"];
	$spa = $_POST["spam"];
	$to="admin@bootexperts.com";

$message = 
	"Name of sender: $nam\n\n" .
	"Email of sender: $ema\n\n" .
	"Subject of sender: $subj\n\n" .
	"Message of sender: $str\n\n";  
if(mail($to,"Sharf home", $message, "FROM: $email")){
	echo "Thank you";
}
else{
	echo "sorry";
} 
?>

</body>
</html>