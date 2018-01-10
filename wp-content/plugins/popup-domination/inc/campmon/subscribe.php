<?php
if (!class_exists('CS_REST_Subscribers', true)){
	require_once ('csrest_subscribers.php');
}


if(isset($_POST['name'])){
	$name = $_POST['name'];
}else{
	$name = '';
}
//$name = $_POST['name'];
$email = $_POST['email'];
if(isset($_POST['custom1	'])){
	$custom1 = $_POST['customf1'];
}else{
	$custom1 = '';
}
if(isset($_POST['custom2'])){
	$custom2 = $_POST['customf2'];
}else{
	$custom2 = '';
}


$wrap = new CS_REST_Subscribers($listid, $apikey);

if(isset($custom2) || !empty($custom2)){
	$result = $wrap->add(array(
	    'EmailAddress' => $email,
	    'Name' => $name,
	    'CustomFields' => array(
 			array(
		       'Key' => $custom1,
		       'Value' => $_POST['custom1']
		    ),
 			array(
		       'Key' => $custom2,
		       'Value' => $_POST['custom2']
		    ),
		),

	));

}else if(isset($custom1) || !empty($custom1)){
	$result = $wrap->add(array(
	    'EmailAddress' => $email,
	    'Name' => $name,
	    'CustomFields' => array(
 			array(
		       'Key' => $custom1,
		       'Value' => $_POST['custom1']
		    )
		),

	));
}else{
	$result = $wrap->add(array(
	    'EmailAddress' => $email,
	    'Name' => $name
	));

}

function getErrorMessage($error){
	if ($error == '401') {
		echo 'Incorrect API Key.';
	} else if ($error == '400'){
		echo 'Invalid email provided.';
	}
}



if(!$result->was_successful()) {
	getErrorMessage($result->http_status_code);
    echo 'Failed with code '.$result->http_status_code."\n<br /><pre>";
    var_dump($result->response);
    return;
}

?>