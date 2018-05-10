<?php 

/*function getMailer() {
    include_once(dirname(__FILE__) . '/mail_lib/ses/AmazonSESMailer.php');
	return new AmazonSESMailer(PHPMAILER_USERNAME, PHPMAILER_PASSWORD);
}

function SendMail($toaddr, $subject, $body) {
    $mailer = getMailer();
    $mailer->AddAddress($toaddr);
    $mailer->SetFrom(PHPMAILER_FROM);
    $mailer->Subject = $subject;
    $mailer->MsgHtml($body);
    $mailer->Send();
}*/

function SocialSonicMail($subject, $body, $recipient_name, $recipient_mail, $cc = FALSE, $bcc = FALSE, $attachment = FALSE, $debug = FALSE) {
	include_once(dirname(__FILE__) . "/PHPMailer/class.phpmailer.php");
	include_once(dirname(__FILE__) . "/PHPMailer/class.smtp.php");
	$mail = new PHPMailer;
	
	$mail->isSMTP();                                      						// Set mailer to use SMTP
	$mail->Host 		= PHPMAILER_HOST;  										// Specify main and backup server
	$mail->Port 		= PHPMAILER_PORT;  
	$mail->SMTPAuth 	= TRUE;                               					// Enable SMTP authentication
	$mail->Username 	= PHPMAILER_USERNAME;                            		// SMTP username
	$mail->Password 	= PHPMAILER_PASSWORD;                           		// SMTP password
	$mail->SMTPSecure 	= PHPMAILER_SMTPSECURE;                            		// Enable encryption, 'ssl' also accepted
	
	$mail->From 		= PHPMAILER_FROM;
	$mail->FromName 	= PHPMAILER_FROMNAME;
	
	if ( is_array($recipient_name) && is_array($recipient_mail) ) {
		for ( $i = 0; $i < count($recipient_mail); $i++ ) {
			$mail->addAddress($recipient_mail[$i], $recipient_name[$i]);  		// Add multiple recipient
		}
	} else {
		$mail->addAddress($recipient_mail, $recipient_name);  					// Add a recipient
	}
	
	$mail->addReplyTo(PHPMAILER_FROM, PHPMAILER_FROMNAME);						// Reply to email and name
	
	if ( $cc ) {																// Add CC recipient
		if ( is_array($cc) ) {
			for ( $i = 0; $i < count($cc); $i++ ) {
				$mail->addCC($cc[$i]);											// Add multiple CC recipient
			}
		} else {
			$mail->addCC($cc);													// Add single CC recipient
		}
	}
	
	if ( $bcc ) {																// Add BCC recipient
		if ( is_array($bcc) ) {
			for ( $i = 0; $i < count($bcc); $i++ ) {
				$mail->addBCC($bcc[$i]);										// Add multiple BCC recipient
			}
		} else {
			$mail->addBCC($bcc);												// Add single BCC recipient
		}
	}
	
	$mail->WordWrap 	= PHPMAILER_WORDWRAP;                                 	// Set word wrap to 50 characters
	
	if ( $attachment ) {
		for ( $i = 0; $i < count($attachment); $i++ ) {
			$mail->addAttachment($attachment[$i]['src'], $attachment[$i]['name']);
		}
	}
	
	$mail->isHTML(TRUE);                                  						// Set email format to HTML
	
	if ( $debug ) {
		$mail->SMTPDebug 	= 2;												// Enable debugging mode
	}
	
	$mail->Subject 		= $subject;
	$mail->Body    		= $body;
	
	if ( !$mail->send() ) {
		return 'Message could not be sent.';
		return 'Mailer Error: ' . $mail->ErrorInfo;
		exit;
	}
	return 'Message has been sent';
}

function emailChoose($send_to_mail, $subject_line, $body, $send_to_name) {
	$mail_choose = SocialSonicMail($subject_line, $body, $send_to_name, $send_to_mail);
	return $mail_choose;
}