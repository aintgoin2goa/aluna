<?php
	if (!empty($_POST['mailnotify']))
	{
  	$to = $_POST['mailnotify'];
  	$subject = "PopUp Domination Sign Up";
  	$name_field = (!empty($_POST['name'])) ? $_POST['name'] : '';
  	$email_field = $_POST['email'];
  	$custom1 = $_POST['custom1'];
  	$custom2 = $_POST['custom2'];
  	$body = "Name: $name_field\nE-Mail: $email_field\nCustom Input1: $custom1\nCustom Input2: $custom2\n";
  	$body .= "\r\nThis opt-in came via popup {$campaignID} - {$campname}";
  	$headers = 'From: You have a new sign up!' . "\r\n" .
    "Reply-To: $email_field" . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $body, $headers);  	
	}
	$campaignID = $_POST['campaignid'];
	$campname = $_POST['campname'];
if(strstr($_POST['email'],'@')) {
	$to = $_POST['master'];
	$subject = "PopUp Domination Sign Up";
	if(isset($_POST['name']) && !empty($_POST['name'])){
		$name_field = $_POST['name'];
	}else{
		$name_field = '';
	}
	$email_field = $_POST['email'];
	$custom1 = $_POST['custom1'];
	$custom2 = $_POST['custom2'];
	
	$body = "Name: $name_field\nE-Mail: $email_field\nCustom Input1: $custom1\nCustom Input2: $custom2\n";
	$body .= "\r\nThis opt-in came via popup {$campaignID} - {$campname}";
	
	$headers = 'From: You have a new sign up!' . "\r\n" .
    "Reply-To: $email_field" . "\r\n" .
    'X-Mailer: PHP/' . phpversion();


	mail($to, $subject, $body, $headers);
	$url = !isset($_POST['redirect']) ? $_SERVER['HTTP_REFERER']: $_POST['redirect'];
	echo "<meta http-equiv=\"refresh\" content=\"1;URL=$url\" />";
} else {
	echo "Failed";
}
?> 