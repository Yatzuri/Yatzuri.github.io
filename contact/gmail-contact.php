<?php

/*
THIS FILE USES PHPMAILER INSTEAD OF THE PHP MAIL() FUNCTION
*/

use PHPMailer\PHPMailer\PHPMailer;

require './PHPMailer-master/vendor/autoload.php';




$fromEmail = $_POST['email'];
$fromName = $_POST['name'];


$sendToEmail = 'yatzuri.rojas@gmail.com';
$fields = array('name' => 'Name:', 'email' => 'Email:', 'message' => 'Message:');

$okMessage = 'Successfully submitted - I will get back to you soon!';

$errorMessage = 'There was an error while submitting the form. Please try again later';



error_reporting(E_ALL & ~E_NOTICE);

try
{
    
    if(count($_POST) == 0) throw new \Exception('Form is empty');
    $emailTextHtml .= "<h3>New message from the Yatzuri Rojas:</h3><hr>";
    $emailTextHtml .= "<table>";

    foreach ($_POST as $key => $value) {
        if (isset($fields[$key])) {
            $emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
        }
    }
    $emailTextHtml .= "</table><hr>";
    $emailTextHtml .= "<p>Have a great day!<br><br>Sincerely,<br><br>Yatzuri Rojas</p>";
    
    $mail = new PHPMailer;



$mail->IsSMTP();
                $mail->SMTPAuth='true';
                $mail->SMTPSecure = 'ssl';
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = "465"; // 8025, 587 and 25 can also be used. Use Port 465 for SSL.
                
                $mail->Username = "email@gmail.com";
                $mail->Password = "password";

                $mail->From = $fromEmail;
                $mail->FromName = $fromName;
                $mail->AddAddress($sendToEmail);
                $mail->AddReplyTo($fromEmail,$fromName);
















    $mail->Subject = 'New message from contact form';

    $mail->Body = $emailTextHtml;
    $mail->isHTML(true);
    //$mail->msgHTML($emailTextHtml); // this will also create a plain-text version of the HTML email, very handy
    
    
    if(!$mail->send()) {
        throw new \Exception('Email send failed. ' . $mail->ErrorInfo);
    }
    
    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    // $responseArray = array('type' => 'danger', 'message' => $errorMessage);
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}


// if requested by AJAX request return JSON response
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