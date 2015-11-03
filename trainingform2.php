<?php
	
	require_once('functions.php');

	if(!checkGetVariable($_GET['pokemon_name']))
		returnHome();

	require_once('header.php');

	$session = createSession($_GET['pokemon_id'], $_GET['pokemon_name'], $_GET['stats']);

	if(!is_array($session))
		returnHome();

	$length = count($session['stats']);



?>

<section class="content">
	<div class="element">
		<h2 class="training-title" id="evs_form">Evs assigned ( <span id="evs_init">0</span> / <span id ="evs_total">510</span> ) </h2>
	</div>
	<div class="element hidden" id="alert_message">
	</div>
	<form action="set_training.php" method="get">
		<div class="element">
			<?php

				for($i = 0; $i < $length; $i++){

					//stats input name
					$num_total = $session['stats'][$i]['name'].'_num_total';
					$stat = $session['stats'][$i]['name'];
					$class = 'stat-element '.$stat.'-element';
					
					?>
					<div class="<?php echo $class; ?>">
						<label for="<?php echo $num_init; ?>" class="stat-element-label"><?php echo ucfirst($stat); ?></label>
						<input type="number" class="stat-number" name="<?php echo $num_total; ?>" required="required" id="<?php echo $stat.'_total'; ?>" onkeyup="check_assigned_evs('<?php echo $stat; ?>')" autocomplete="off">
					</div>
					<?php
				}
			?>
		</div>
		<div class="element background">
			<div class="form-element">
				<div class="full">
					<label for="stats_order" class="full-label">Start with:</label>
				</div>
				<div class="full">

						<?php

							for($i = 0; $i < $length; $i++){

								//Displaying the select to choose which stat will be trainined on first place
								$stat = $session['stats'][$i]['name'];
								$id = $stat."-check";						//radio button ID 
								$class = ($i == 0) ? "half-label ".$stat."-label selected" : "half-label ".$stat."-label";	//label css class

								?>
								<div class="form-element">
									<div class="full">
										<input type="radio" name="starting_stat" value="<?php echo $stat; ?>" class="stat-check" id="<?php echo $id; ?>" onclick="check_order_selected('<?php echo $stat; ?>')" <?php if($i == 0) echo "checked='checked'"; ?>>
										<label for="<?php echo $id; ?>" name="starting_stats_label" class="<?php echo $class; ?>" id="<?php echo $stat; ?>"><?php echo ucfirst($stat); ?></label>
									</div>
								</div>
									<!--<option value="<?php echo $stat; ?>"><?php echo ucfirst($stat); ?></option>-->

								<?php
							}
						?>
				</div>
			</div>
		</div>
		<div class="element">
			<input type="submit" value="Start Training" disabled="disabled" class="form-button" id="start_training_button">
		</div>
	</form>
</section>

<?php

	require_once('footer.php');

?>

