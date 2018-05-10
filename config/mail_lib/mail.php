<?php
function getMailer() {
    require_once('ses/AmazonSESMailer.php');
    return new AmazonSESMailer('AKIAILWEXKRJ2MHNSQZQ', 
                                  'UU4uwIVnv7khou7q6z7O9YHkgEC4aKR0R9V9KO2k');
}

function sendEMail($toaddr, $subject, $body) {
    // TODO: Log all mails from here.
    // Create a mailer class with your Amazon ID/Secret in the constructor
    $mailer = getMailer();

    // Then use this object like you would use PHPMailer normally!
    $mailer->AddAddress($toaddr);
    $mailer->SetFrom('team@engagewise.com');
    $mailer->Subject = $subject;
    $mailer->MsgHtml($body);
    $mailer->Send();
}
?>
