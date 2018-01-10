<?php 
	$provider = $_POST['provider'];
	$campaignID = $_POST['campaignid'];
	$campname = $_POST['campname'];
	$mailing_list_id = $_POST['mailingid'];
	$mailing_info = unserialize(base64_decode($this->option('formhtml')));
	if (empty($mailing_info)){
		$mailinglists = $this->get_db('popdom_mailing');
		if (is_array($mailinglists))
		{
			foreach ($mailinglists as $mailinglist)
			{
				if ($mailinglist->id == $mailing_list_id)
				{
					$mailing_info = unserialize(base64_decode($mailinglist->settings));
				}
			}
		}
	}
	

	if (!empty($mailing_info) && !empty($provider)){
		// set up variables used by every provider
		foreach($mailing_info as $key => $value){
			$$key = $value;
		}
		
		if (gettype($provider_details) == "array"){
			// create all variables required for each provider
			foreach($provider_details as $key => $value){
				$$key = $value;
			}
			$formhtml = stripslashes($provider_details['formhtml']);
			$hidden_fields = '';
			if (isset($hidden)){
				foreach($hidden as $key => $value){
					$$key = $value;
				}
			}
		}
	}
	
/*
		 $maildetail = print_r($_POST,true);
		 die($maildetail);
*/
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
	if($provider == 'mc'){
		include_once 'mc/subscribe.php';
	}else if($provider == 'cm'){
		include_once 'campmon/subscribe.php';
	}else if($provider == 'aw'){
		include_once 'aweber_api/subscribe.php';
	}else if($provider == 'cc'){
		include_once 'concon/subscribe.php';
	}else if($provider == 'ic'){
		include_once 'icon/subscribe.php';
	}else if($provider == 'gr'){
		include_once 'getre/subscribe.php';
	}else if($provider == 'nm'){
		include_once 'email.php';
	}
	
	die();
?>