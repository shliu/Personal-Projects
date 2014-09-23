
<!-- ********Display Parent Chart******** -->
<table bgcolor="<?php echo $background ?>">
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
					echo "<td width='$cell_w' height='$cell_h' align='center' bgcolor='$parent[$array_counter]'>$display_counter</td>
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
