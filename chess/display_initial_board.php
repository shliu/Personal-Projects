<?php

//Create initial(new) chess board
echo "<table bgcolor='777777' border='5'>";
for ($row = 8; $row >= 1; $row--)
{
	echo "<tr>";
	for ($col = 1; $col <= 8; $col++)
	{
		echo "<td ".display_graphic(set_normal_layout($col, $row))."width='$cell_w' height='$cell_h' align='center' bgcolor='".apply_chequered_colors($col, $row, $cell_light, $cell_dark)."'>";
		echo "<input type='radio' name='dest_pos' value='$col$row'></br>";
		echo set_normal_layout($col, $row);
		echo "</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type='radio' name='sele_pos' value='$col$row'></td>";
		echo "<input type='hidden' name='$col$row' value='".set_normal_layout($col, $row)."'>";
	}
	echo "</tr>";
}
echo "</table>";
echo "<input type='hidden' name='round' value='1'>";
echo "it's currently round 1, it's white's turn";

?>