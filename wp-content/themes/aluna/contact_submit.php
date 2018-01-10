<?php
require_once('class.phpmailer.php');


extract($_POST);

$name = strip_tags( trim($name) );
$email = strip_tags( trim($email) );
$message = nl2br( strip_tags( trim($message) ) );

$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

try {
  	$mail->AddAddress('alunathemovie@aol.com', 'Aluna the Movie');
  	$mail->SetFrom($email, $name);
  	$mail->AddReplyTo($email, $name);
	$mail->AddBCC('paul.wilson66@gmail.com', 'Paul Wilson');
  	$mail->Subject = 'A message from alunathemovie.com';
  	$mail->MsgHTML($message);
  	$mail->Send();
  	header("HTTP/1.0 200 OK");
} catch (phpmailerException $e) {
  	header("HTTP/1.0 500 Internal Server Error");
} catch (Exception $e) {
  	header("HTTP/1.0 500 Internal Server Error");
}
?>
