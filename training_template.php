<?php
	
	require_once('functions.php');

	if(!checkGetVariable($_GET['stat']))
		returnHome();
	else
		$stat_number = $_GET['stat'];

	if(!isset($_SESSION['session']))
		returnHome();
	else
		$session = $_SESSION['session'];

	settype($stat_number, 'integer');

	$ruta_img = getPkmnImage($session['id'], 'largue');										//Returns pkmn image path
	$stat_class = getTemplateStatClass($session['stats'][$stat_number]['name']);			//Returns a String which contains the stat class related
	$links_data = getTemplateNavLinks($session, $stat_number);								//Returns an array with all the links data

	//Checking if the images and the links have been receibed properly
	if(!$ruta_img || !$links_data || !$stat_class)
		returnHome();

	require_once('header.php');

?>

<section class="content">
	<h2 class="selected_stat <?php echo $stat_class; ?>"><?php echo ucfirst($session['stats'][$stat_number]['name']); ?></h2>
	<div class="element subtitle_content">
		<h2 class="training_title" id="evs_form">Evs ( <span id="evs_assigned">0</span> / <span id="evs_total"><?php echo $session['stats'][$stat_number]['value']; ?></span> )</h2>
	</div>
	<div class="element hidden" id="alert-message">
		<p class="message" id="alert-message-form"></p>
	</div>
	<div class="element background">
		<div class="pokemon-image-content">
			<img src="<?php echo $ruta_img; ?>" class="pokemon-image-displayed" id="pokemon-sprite" title="<?php echo $session['name']; ?>">
		</div>
	</div>
	<div class="element controllers margin_top">
		<input type="number" name="evs_assigned" id="evs_input" placeholder="Evs" autocomplete="off" class="evs_input">
		<input type="button" value="Add" class="add_button option_button" onclick="add_evs()">
	</div>
	<div class="element controllers">
		<input type="button" class="option_button" value="Reset" onclick="reset_evs()">
		<input type="button" class="option_button" value="Undo" onclick="undo_evs()">
	</div>
	<div class="element">
		<?php

			//Displaying the navigation links

			$length = count($links_data);

			for($i = 0; $i < $length; $i++){

				$title = 'Go to the '.$links_data[$i]['text'];

				?>
					<a href="<?php echo $links_data[$i]['href']; ?>" title="<?php echo $title; ?>" class="homelink"><?php echo ($links_data[$i]['text'] == 'Previous stat') ? $links_data[$i]['icon'].' '.$links_data[$i]['text'] : $links_data[$i]['text'].' '.$links_data[$i]['icon']; ?></a>
				<?php
			}
		?>
		<a href="index.php" title="Finish this training" class="homelink">Finish this training</a>
	</div>
</section>

<?php

	require_once('footer.php');

?>