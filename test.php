<?php
	
	//Testing validator.php

	require_once('validator.php');

	$validator = new Validator();

	$length = count($_POST);
	var_dump($_POST);
	die();

	if(!is_object($validator))
		die('This is unexpected !');

	echo 'Text Field: '.$validator->validateTextField($_POST['text']).'<br>';
	echo 'Number Field: '.$validator->validateNumberField($_POST['number']).'<br>';
	echo 'Email Field: '.$validator->validateEmailField($_POST['email']).'<br>';
?>