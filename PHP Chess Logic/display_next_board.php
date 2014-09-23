<?php

//Re-create chess board for every move
echo "<table bgcolor='777777' border='5'>";
for ($row = 8; $row >= 1; $row--)
{
	echo "<tr>";
	for ($col = 1; $col <= 8; $col++)
	{
		echo "<td ".display_graphic($chessboard[$col.$row])."width='$cell_w' height='$cell_h' align='center' bgcolor='".apply_chequered_colors($col, $row, $cell_light, $cell_dark)."'>";
		echo "<input type='radio' name='dest_pos' value='$col$row'".check_radio_button($col, $row, $dest_pos)."></br>";
		echo $chessboard[$col.$row];
		echo "</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type='radio' name='sele_pos' value='$col$row'".check_radio_button($col, $row, $sele_pos)."></td>";
		echo "<input type='hidden' name='$col$row' value='".$chessboard[$col.$row]."'>";
	}
	echo "</tr>";
}
echo "</table>";
echo "<input type='hidden' name='round' value='$round'>";
echo "it's currently round $round";

if ($round % 2 == 0)
		echo ", it's black's turn";
	else
		echo ", it's white's turn";

?>