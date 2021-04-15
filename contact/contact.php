<?php

/*
THIS FILE USES PHPMAILER INSTEAD OF THE PHP MAIL() FUNCTION
*/

use PHPMailer\PHPMailer\PHPMailer;

require './PHPMailer-master/vendor/autoload.php';

/*
*  CONFIGURE EVERYTHING HERE
*/

// an email address that will be in the From field of the email.
$fromEmail = $_POST['email'];
$fromName = $_POST['name'];

// an email address & name that will receive the email with the output of the form
$sendToEmail = 'name@mydomain.com';
$sendToName = 'Name';

// subject of the email
$subject = 'New message from contact form';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('name' => 'Name:', 'email' => 'Email:', 'message' => 'Message:');

// message that will be displayed when everything is OK :)
$okMessage = 'Successfully submitted - we will get back to you soon!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

/*
*  LET'S DO THE SENDING
*/

error_reporting(E_ALL & ~E_NOTICE);

try
{
    
    if(count($_POST) == 0) throw new \Exception('Form is empty');
    $emailTextHtml .= "<h3>New message from Yatzuri Rojas:</h3><hr>";
    $emailTextHtml .= "<table>";

    foreach ($_POST as $key => $value) {
        if (isset($fields[$key])) {
            $emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
        }
    }
    $emailTextHtml .= "</table><hr>";
    $emailTextHtml .= "<p>Have a great day!<br><br>Sincerely,<br><br>Yatzuri Rojas</p>";
    
    $mail = new PHPMailer;

    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($sendToEmail, $sendToName);
    $mail->addReplyTo($_POST['email'], $_POST['name']);
    


    $mail->Subject = $subject;

    $mail->Body = $emailTextHtml;
    $mail->isHTML(true);
    
    
    if(!$mail->send()) {
        throw new \Exception('Email send failed. ' . $mail->ErrorInfo);
    }
    
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}


if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
    
    header('Content-Type: application/json');
    
    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
?>