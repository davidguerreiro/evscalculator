<?php
	
	require_once('functions.php');

	if(!checkGetVariable($_GET['form']))
		returnHome();

	require_once('header.php');

?>


<section class="content">
	<form action="trainingform2.php" method="get">
		<div class="element">
			<div class="form-element">
				<div class="full">
					<label for="pokemon_name" class="full-label">Choose a Pokémon</label>
				</div>
				<div class="full">
					<input type="text" name="pokemon_name" placeholder="Ex. Bulbasaur" required="required" id="pokemon_name_input" onkeyup="change_pkmn_image()" autocomplete="off">
					<input type="hidden" name="pokemon_id" value="" id="pokemon_id_input">
				</div>
			</div>
		</div>
		<div class="element background">
			<div class="pokemon-image-content">
				<img src="./images/static/interrogation.gif" class="pokemon-image-displayed" id="pokemon-sprite" title="No pokemon selected">
			</div>
		</div>
		<div class="element background">
			<div class="form-element">
				<div class="full">
					<label class="full-label">Choose the stats you want to spread EVS</label>
				</div>
			</div>
			<div class="form-element">
				<div class="full">
					<input type="checkbox" name="stats[]" value="ps" class="stat-check" id="ps-check" onclick="check_selected_stats('ps')">
					<label for="ps-check" class="half-label ps-label" id="ps">Ps</label>
				</div>
			</div>
			<div class="form-element">
				<div class="full">
					<input type="checkbox" name="stats[]" value="attack" class="stat-check" id="attack-check" onclick="check_selected_stats('attack')">
					<label for="attack-check" class="half-label attack-label" id="attack">Attack</label>
				</div>
			</div>
			<div class="form-element">
				<div class="full">
					<input type="checkbox" name="stats[]" value="defense" class="stat-check" id="defense-check" onclick="check_selected_stats('defense')">
					<label for="defense-check" class="half-label defense-label" id="defense">Defense</label>
				</div>
			</div>
			<div class="form-element">
				<div class="full">
					<input type="checkbox" name="stats[]" value="spattack" class="stat-check" id="spattack-check" onclick="check_selected_stats('spattack')">
					<label for="spattack-check" class="half-label spattack-label" id="spattack">SpAttack</label>
				</div>
			</div>
			<div class="form-element">
				<div class="full">
					<input type="checkbox" name="stats[]" value="spdefense" class="stat-check" id="spdefense-check" onclick="check_selected_stats('spdefense')">
					<label for="spdefense-check" class="half-label spdefense-label" id="spdefense" >SpDefense</label>
				</div>
			</div>
			<div class="form-element">
				<div class="full">
					<input type="checkbox" name="stats[]" value="speed" class="stat-check" id="speed-check" onclick="check_selected_stats('speed')">
					<label for="speed-check" class="half-label speed-label" id="speed">Speed</label>
				</div>
			</div>
		</div>
		<div class="element">
			<input type="submit" value="Next" disabled="disabled" class="form-button" id="next_button">
		</div>
	</form>
</section>

<?php
	
	require_once('footer.php');

?>

