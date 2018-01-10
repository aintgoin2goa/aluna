<?php

if (!class_exists('ConstantContact', true)){
	require_once('ConstantContactz.php');
}


$username = $username;
$password = $password;
$apikey = str_replace(' ', '', $apikey);
$cc = new ConstantContact($apikey, $username, $password);

if(isset($_POST['name'])){
	$name = $_POST['name'];
}else{
	$name = '';
}
if(isset($_POST[$custom1name])){
	$custom1 = $_POST[$custom1name];
}else{
	$custom1 = '';
}
if(isset($_POST[$custom2name])){
	$custom2 = $_POST[$custom2name];
}else{
	$custom2 = '';
}

function getErrorMessage($result){
	if (strstr($result, '409')){
		echo 'Contact already exists within mailing list.';
	} else if (strstr($result, '400')) {
		echo 'Invalid email provided.';
	} else {
		echo $result;
	}
}

$result = $cc->addContactToMailingList($_POST['email'], $name, $custom1, $custom2, $custom1name, $custom2name, $listid);
$find = strstr($result, 'Error');
if($find){
	getErrorMessage($result);
}else{
	echo '';
}
?>