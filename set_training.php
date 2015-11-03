<?php
	
	require_once('functions.php');

	if(!checkGetVariable($_GET['starting_stat']) || !isset($_SESSION['session']))
		returnHome();

	$session = updateSession($_SESSION['session'], $_GET['starting_stat']);

	
	if(!is_array($session))
		returnHome();
	else
		startTraining($session);
	
	
?>