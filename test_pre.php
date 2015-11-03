<?php

	include_once('header.php');
	include_once('database.php');

?>

<body>

	<p>Heyyy !!!</p>
	<p>
		<?php

			$db = new DB();
			
			echo $db->check_connection();

			echo "<br>";

			echo $db->get_database();

			echo "<br>";

			$query = "INSERT INTO pokemon(num_dex, name, tipe1, tipe2, thumbail, image) VALUES(002, 'Ivysaur', 'Plant', 'Poison', './images/thumbails/002.png', './images/pokemon_display/002.gif')";

			//echo $db->query($query);

			echo "<br>";

			$query = "SELECT * FROM pokemon";

			//var_dump($db->get_results($query));

			$control = $db->get_results($query);

			$length = count($control);

			for($i = 0; $i < $length; $i++){

				echo $control[$i]['name'].'<br>';

			}

			$query = "SELECT * FROM pokemon WHERE id = 30";

			var_dump($db->get_var($query, 2));
			
		?>	
	</p>

	<img src="./images/pokemon_display/001.gif" alt="Bulbasaur" id="001_pkmn" width="80px" heigh="80px">
	<img src="./images/pokemon_display/002.gif" alt="Ivysaur" id="002_pkmn" width="120px" heigh="120px">

	<form method="post" action="test.php">
		<div>
			<p>Text Field:</p>
		</div>
		<div>
			<input type="text" name="text">
		</div>
		<div>
			<p>Number Field:</p>
		</div>
		<div>
			<input type="number" name="number">
		</div>
		<div>
			<p>Email field:</p>
		</div>
		<div>
			<input type="email" name="email">
		</div>
		<div>
			<p>Url validator:</p>
		</div>
		<div>
			<input type="text" name="url">
		</div>
		<div>
			<input type="submit" value="Test it !">
			<input type="reset" value="Clean fields">
		</div>
	</form>

</body>

<?php
	
	include('footer.php');

?>