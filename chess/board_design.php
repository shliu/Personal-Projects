<?php
function set_normal_layout($col, $row)
{
	//set black pieces
	if ($col.$row == 58)
		return "bk";
	else if ($col.$row == 48)
		return "bq";
	else if (($col.$row == 18) || ($col.$row == 88))
		return "br";
	else if (($col.$row == 38) || ($col.$row == 68))
		return "bb";
	else if (($col.$row == 28) || ($col.$row == 78))
		return "bh";
	else if ($row == 7)
		return "bp";
	//set white pieces
	else if ($col.$row == 51)
		return "wk";
	else if ($col.$row == 41)
		return "wq";
	else if (($col.$row == 11) || ($col.$row == 81))
		return "wr";
	else if (($col.$row == 31) || ($col.$row == 61))
		return "wb";
	else if (($col.$row == 21) || ($col.$row == 71))
		return "wh";
	else if ($row == 2)
		return "wp";
	else
		;
}





function display_graphic($piece)
{
/*
	if (substr($piece, 1, 1) == h)
		return "background='knight.bmp'";
	else
		;
*/
}








function apply_chequered_colors($col, $row, $cell_light, $cell_dark)
{
	//apply chequered colors to board
	if ((($col % 2) && !($row % 2))
		|| (!($col % 2) && ($row % 2)))
		return $cell_light;
	else
		return $cell_dark;
}









function check_radio_button($col, $row, $position)
{
	//mark the radio button that was previously selected
	if ($col.$row == $position)
		return " checked";
	else
		;
}




?>