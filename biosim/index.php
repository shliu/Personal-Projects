<html>

<head>
	<?php
	$set_row = 10;
	$set_col = 9;
	
	$cell_s = 8;
	$cell_w = 25;
	$cell_h = 25;
		
	$pop_total = $set_row * $set_col;
	$pop_half = $pop_total / 2;
	
	//----------------------------------------------
	//Do the following on first load or when reset button is pressed
	//----------------------------------------------
	if ((!$_POST['Submit']) || ($_POST['Reset']))
	{
		echo "<title>Welcome to the Biology Simulator</title>";
		
		//generate initial background color
		//generates RGB colors separately
		//if any value is <16 (<10 in hex), add a placeholder '0' in front
		$rand_r = dechex(rand(0, 255));
		if (strlen($rand_r) < 2){$rand_r = "0".$rand_r;}
		$rand_g = dechex(rand(0, 255));
		if (strlen($rand_g) < 2){$rand_g = "0".$rand_g;}
		$rand_b = dechex(rand(0, 255));
		if (strlen($rand_b) < 2){$rand_b = "0".$rand_b;}
		$bg_default = $rand_r.$rand_g.$rand_b;
	}
	//----------------------------------------------
	//Only crunch numbers when submit has been pressed
	//----------------------------------------------
	else
	{
		//copy generation
		$generation = $_POST['generation'];
		
		echo "<title>Bio Sim (Gen $generation)</title>";
		
		//copy background color
		$background = $_POST['background']; 
		//split background color to R,G,B and convert to decimal
		$bg_r = hexdec(substr($background, 0, 2));
		$bg_g = hexdec(substr($background, 2, 2));
		$bg_b = hexdec(substr($background, 4, 2));
		
		//copy parent's color
		for ($i = 0; $i < $pop_total; $i++)
		{
			$parent[$i] = $_POST['c'.$i];
		}
		
		//********Find Color Differences********
		//compare background to each parent then place total difference into array $difference[]
		for ($i = 0; $i < $pop_total; $i++)
		{
			//split color to R,G,B and convert to decimal
			$parent_r = hexdec(substr($parent[$i], 0, 2));
			$parent_g = hexdec(substr($parent[$i], 2, 2));
			$parent_b = hexdec(substr($parent[$i], 4, 2));
			
			//find difference between the RGB values of background vs parent
			//sum the total difference (sum of absolute value of RGB differences)
			$difference[$i] = abs($bg_r - $parent_r);
			$difference[$i] += abs($bg_g - $parent_g);
			$difference[$i] += abs($bg_b - $parent_b);
		}
		
		//********Population "Pruning"********
		//find first 50% parents with highest difference and remove them from the population
		for ($i = 0; $i < $pop_half; $i++)
		{
			$position = array_search(max($difference), $difference); //find position of array containing highest value
			$difference[$position] = -1; //marks position as empty
		}
		
		//********Reproduction********
		//reproduction of surviving parents
		//simulates mitosis and genetic mutation
		for ($i = 0; $i < $pop_total; $i++)
		{
			//look at the entire grid for parent who survived 
			//"survial" is defined by its difference >= 0
			if ($difference[$i] >= 0)
			{
				$position = array_search(-1, $difference); //find an empty position (empty is defined as $difference = -1)
				
				//simulated mutation
				//find and replace one hex char with a semi-randomly generated hex char
				//new char can be between -4 to 4 values greater than the original
				$mutation_pos = rand(0, 5);
				$mutation = hexdec(substr($parent[$i], $mutation_pos, 1));
				$mutation += rand(-3, 3);
				if ($mutation < 0){$mutation = 0;} //hex can't be < 0
				elseif ($mutation > 15){$mutation = "f";} //hex can't be > 15
				else {$mutation = dechex($mutation);}
				$mutated_ind = substr_replace($parent[$i], $mutation, $mutation_pos, 1);
				
				//simulated mitosis
				$offspring[$i] = $parent[$i]; //one offspring is a perfect copy of its parent
				$offspring[$position] = $mutated_ind; //the other offspring suffers a minor mutation
				$difference[$position] = -2; //mark mutated offspring's position so that:
											//1) < 0, so it's not identified as "survived"
											//2) != -1 so the position isn't identified as empty
			}
		}
	}
	?>
</head>

<body>
	<?php
	if ((!$_POST['Submit']) || ($_POST['Reset']))
	{
	?>
		<form method="post" action="">
			<table>
				<tr>
					<th>Original</th>
				</tr>
				<tr>
					<td><?php include 'display_original.php'; ?></td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="generation" value="1">
						<input type="hidden" name="background" value="<?php echo $bg_default ?>"/>
						<input type="submit" name="Submit" value="Begin simulation"/></br></br></br>
						<input type="submit" name="Reset" value="Reset"/>
					</td>
				</tr>
			</table>
		</form>
	<?php
	}
	else
	{
	?>
		<form method="post" action="">
			<table>
				<tr>
					<th>Generation <?php echo $generation ?>:</th>
					<th>Selection:</th>
					<th>Offsprings:</th>
				</tr>
				<tr>
					<td><?php include 'display_parent.php'; ?></td>
					<td><?php include 'display_selection.php'; ?></td>
					<td><?php include 'display_offspring.php'; ?></td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="generation" value="<?php echo ++$generation ?>">
						<input type="submit" name="Submit" value="Continue ->"/></br>
						Change Background: <input type="text" maxlength="6" size="6" name="background" value="<?php echo $background ?>"/></br></br>
						<input type="submit" name="Reset" value="Reset"/>
					</td>
				</tr>
			</table>
		</form>
	<?php
	}
	?>
</body>

</html>
