
<!-- ********Display Original Chart******** -->
<table bgcolor="<?php echo $bg_default ?>">
	<td>
		<table cellspacing="<?php echo $cell_s ?>">
			<?php
			$display_counter = 1;
			$array_counter = 0;
			for ($row = 0; $row < $set_row; $row++) //generate rows
			{
				echo "<tr>
				";
				for ($col = 0; $col < $set_col; $col++) //generate columns
				{
					//generate random color
					//generates RGB colors separately
					//if each value is <16 (<10 in hex), add a placeholder '0' in front
					$rand_r = dechex(rand(0, 255));
					if (strlen($rand_r) < 2){$rand_r = "0".$rand_r;}
					$rand_g = dechex(rand(0, 255));
					if (strlen($rand_g) < 2){$rand_g = "0".$rand_g;}
					$rand_b = dechex(rand(0, 255));
					if (strlen($rand_b) < 2){$rand_b = "0".$rand_b;}
					echo "<td width='$cell_w' height='$cell_h' align='center' bgcolor='$rand_r$rand_g$rand_b'>$display_counter</td>
					<input type='hidden' name='c$array_counter' value='$rand_r$rand_g$rand_b'>
					";
					$display_counter++;
					$array_counter++;
				}
				echo "</tr>
				";
			}
			?>
		</table>
	</td>
</table>
