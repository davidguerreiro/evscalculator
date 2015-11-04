<?php

session_start();
require_once('database.php');
require_once('validator.php');

/**
*
*This function checks if a GET variable has beeen passed
*
*@param unknow $gevariable GET variable to be validate
*@return boolean status Validation status
*
**/


function checkGetVariable($getvariable){

	$status = (isset($getvariable)) ? true : false;

	return $status;

}

/**
*
*This function returns the user to the homepage
*
*@return void
*/
function returnHome(){

	if(isset($_SESSION['session']))
		unset($_SESSION['session']);

	header('Location: index.php');
	die();

}


/**
*
*This function creates the training session with the first data available
*
*@param int $pokemon_id Name of the pokemon
*@param array $pokemon_stats Trained stats
*
*@return array $session || boolean false Session array with all the session's data or false if erro occured
*
*/
function createSession($pokemon_id, $pokemon_name, $pokemon_stats){

	if(isset($_SESSION['session']))			
		unset($_SESSION['session']);											//Delete any other previous session

	$validator = new Validator();												//Validator handler
	$db = new Db();

	if(!isset($pokemon_id) || !$validator->validateExist($pokemon_stats) || !isset($pokemon_name))
		return false;

	if(!$validator->validateNumberField($pokemon_id))
		return false;

	if(!$validator->validateTextField($pokemon_name))
		return false;

	//Checking if that pokemon exist in our database 

	$query = "SELECT COUNT(*) FROM pokemon WHERE name = '$pokemon_name'";

	if($db->get_var($query) <= 0)
		return false;

	//Let's created the session

	$_SESSION['session']['id'] = $pokemon_id;
	$_SESSION['session']['pokemon_name'] = $pokemon_name;
	
	$length = count($pokemon_stats);

	for($i = 0; $i < $length; $i++){
		$_SESSION['session']['stats'][$i]['name'] = $pokemon_stats[$i];				//Pushing every stat into the session array
		$_SESSION['session']['stats'][$i]['value'] = 0;
	}

	if(count($_SESSION['session']['stats']) > 0)
		return $_SESSION['session'];
	else
		return false;


}

/**
*
*This function returns an array with the final session created ready to start the training
*
*@param array $session Session array with the data created in the previous form
*@param string $starting_stat Stat which is going to be used as a first stat to train
*
*@return array $session || boolean false Array with all the data we need to perform a training or false if an error occurr
*
*/
function updateSession($session, $starting_stat){

	$validator = new Validator();

	if(!is_array($session))
		return false;

	if(!isset($_SESSION['session']))
		return false;

	if(!$validator->validateTextField($starting_stat))
		return false;

	$control = true;
	$shorted = false;


	//getting the stats value fields

	$length = count($session['stats']);

	for($i = 0; $i < $length; $i++){

		$get_var_name = $session['stats'][$i]['name'].'_num_total';

		if(!isset($_GET[$get_var_name])){
			$control = false;
			continue;
		}

		$session['stats'][$i]['value'] = $_GET[$get_var_name];

	}

	//short the array stats session with the first stat selected

	do{
		shuffle($session['stats']);

		if($session['stats'][0]['name'] === $starting_stat)
			$shorted = true;
	}
	while(!$shorted);

	$_SESSION['session'] = $session;


	if($control)
		return $session;
	else
		return false;
}

/**
*
*This function returns the pokemon's main image path
*
*@param int $id Pkmn id
*@param string $type Type of the image: largue = full image, small = thumbail. Default : largue
*@return string $path Pkmn's image path
*
*/
function getPkmnImage($id, $type = 'large'){

	$db = new Db;

	if(!is_int($id))
		settype($id, 'integer');

	$query = ($type == 'largue') ? "SELECT image FROM pokemon WHERE id = $id" : "SELECT thumbail FROM pokemon WHERE id = $id";

	$path = $db->get_var($query);

	if(is_string($path) && $path !== '')
		return $path;
	else
		return false;

}

/**
*
*This function returns an array which contains the links we need to navigate through the evs training template
*
*@param $session Array that contains all the training data, like the pokemon number or the stats
*@return $navigation_links array Array that contains all the data related with the navigation links || false if validation fails
*
*/
function getTemplateNavLinks($session, $stat_number){

	//variables
	$navigation_links = array();							//Array that contains all the data related with the links
	$total_stats = count($session['stats']);				//Array that contains all the training data, like the pokemon name or the stats
	$total_stats--;												
	$training_template_url = 'training_template.php?stat=';	//Template URl

	//validation
	if(!is_array($session))
		return false;

	if(!is_int($stat_number))
		settype($stat_number, 'integer');

	if($stat_number < 0 || $stat_number > 6)
		return false;

	if($stat_number == 0){
		//we are training the first selected stat
		$stat_number++;
		$navigation_links[0]['href'] = $training_template_url.$stat_number;
		$navigation_links[0]['text'] = 'Next stat';
		$navigation_links[0]['icon'] =  "<i class='fa fa-arrow-right link_icon'></i>";
	}
	else if($stat_number == $total_stats){
		//we are training the last selected stat
		$stat_number--;
		$navigation_links[0]['href'] = $training_template_url.$stat_number;
		$navigation_links[0]['text'] = 'Previous stat';
		$navigation_links[0]['icon'] = "<i class='fa fa-arrow-left link_icon'></i>";
	}
	else{
		//we are training a stat located between the first one and the last one
		$back = $stat_number - 1;
		if($back < 0)
			$back = 0;

		$next = $stat_number + 1;
		if($next > 5)
			$next = 5;

		$navigation_links[0]['href'] = $training_template_url.$back;
		$navigation_links[0]['text'] = 'Previous stat';
		$navigation_links[0]['icon'] = "<i class='fa fa-arrow-left link_icon'></i>";
		$navigation_links[1]['href'] = $training_template_url.$next;
		$navigation_links[1]['text'] = 'Next stat';
		$navigation_links[1]['icon'] = "<i class='fa fa-arrow-right link_icon'></i>";
	}

	$length = count($navigation_links);

	if($length > 0)
		return $navigation_links;
	else
		return false;

}

/**
*
*This function gets the class related with the selected stat.
*
*@param String $stat_name Name of the stat that we are working with
*@return String $stat_class Name of the CSS stat class || false if paramenter validation fails
*
*/
function getTemplateStatClass($stat_name){

	//variables
	$stat_class = '';

	//validation
	if(!is_string($stat_name))
		return false;

	switch($stat_name){
		case 'ps':
			$stat_class = 'ps-element';
			break;
		case 'attack':
			$stat_class = 'attack-element';
			break;
		case 'defense':
			$stat_class = 'defense-element';
			break;
		case 'spattack':
			$stat_class = 'spattack-element';
			break;
		case 'spdefense':
			$stat_class = 'spdefense-element';
			break;
		case 'speed':
			$stat_class = 'speed-element';
			break;
		default:
			$stat_class = '';
			break;
	}

	if($stat_class == '')
		return false;
	else
		return $stat_class;

}

/**
*
*This function checks that everything is correct and then tigger the training starting for the first stat
*
*@param array $session Session array with all the data necesary to start the training
*@return void
*
*/
function startTraining($session){

	if(!is_array($session))
		returnHome();

	header('Location: training_template.php?stat=0');

}


?>
