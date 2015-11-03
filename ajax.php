<?php

header('Content-type: application/json; charset=utf-8');
require_once('database.php');
require_once('validator.php');

$db = new Db();
$validator = new Validator();

//Changing the pokemon image on the first form

if($_REQUEST['action'] == 'changePkmnImage'){

	$status = false;

	if(!$validator->validateTextField($_REQUEST['pkmnName']))
		echo json_encode($status);
	else{

		//Checking in the database if that pkmn exist

		$pokemon_name = ucfirst($_REQUEST['pkmnName']);
		$query = "SELECT id, image FROM pokemon WHERE name = '$pokemon_name'";

		$result = $db->get_row($query);

		if(is_array($result))
			echo json_encode($result);
		else
			echo json_encode($status);
	}
}

?>