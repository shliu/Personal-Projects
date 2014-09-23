<?php
include_once "world.php";
include_once "attacks.php";




//------------------------Knight------------------------
function wh_CanMove($sele_pos, $dest_pos)
{
	if (world_IsInArray($dest_pos, attacks_of_knight($sele_pos)) && world_GetColor($dest_pos) != 'w')
		return true;
	else
		return false;
}


function bh_CanMove($sele_pos, $dest_pos)
{
	if (world_IsInArray($dest_pos, attacks_of_knight($sele_pos)) && world_GetColor($dest_pos) != 'b')
		return true;
	else
		return false;
}
















//------------------------Rook------------------------
function wr_CanMove($sele_pos, $dest_pos)
{
	if ((world_IsInArray($dest_pos, attacks_going_N($sele_pos)) || world_IsInArray($dest_pos, attacks_going_S($sele_pos)) 
		|| world_IsInArray($dest_pos, attacks_going_E($sele_pos)) || world_IsInArray($dest_pos, attacks_going_W($sele_pos)))
		&& world_GetColor($dest_pos) != 'w')
		return true;
	else
		return false;
}


function br_CanMove($sele_pos, $dest_pos)
{
	if ((world_IsInArray($dest_pos, attacks_going_N($sele_pos)) || world_IsInArray($dest_pos, attacks_going_S($sele_pos)) 
		|| world_IsInArray($dest_pos, attacks_going_E($sele_pos)) || world_IsInArray($dest_pos, attacks_going_W($sele_pos)))
		&& world_GetColor($dest_pos) != 'b')
		return true;
	else
		return false;
}














//------------------------Bishop------------------------
function wb_CanMove($sele_pos, $dest_pos)
{
	if ((world_IsInArray($dest_pos, attacks_going_NE($sele_pos)) || world_IsInArray($dest_pos, attacks_going_NW($sele_pos)) 
		|| world_IsInArray($dest_pos, attacks_going_SE($sele_pos)) || world_IsInArray($dest_pos, attacks_going_SW($sele_pos)))
		&& world_GetColor($dest_pos) != 'w')
		return true;
	else
		return false;
}



function bb_CanMove($sele_pos, $dest_pos)
{
	if ((world_IsInArray($dest_pos, attacks_going_NE($sele_pos)) || world_IsInArray($dest_pos, attacks_going_NW($sele_pos)) 
		|| world_IsInArray($dest_pos, attacks_going_SE($sele_pos)) || world_IsInArray($dest_pos, attacks_going_SW($sele_pos)))
		&& world_GetColor($dest_pos) != 'b')
		return true;
	else
		return false;
}
















//------------------------Queen------------------------
function wq_CanMove($sele_pos, $dest_pos)
{
	if (wr_CanMove($sele_pos, $dest_pos) || wb_CanMove($sele_pos, $dest_pos))
		return true;
	else
		return false;
}



function bq_CanMove($sele_pos, $dest_pos)
{
	if (br_CanMove($sele_pos, $dest_pos) || bb_CanMove($sele_pos, $dest_pos))
		return true;
	else
		return false;
}

















//------------------------Pawn------------------------
function wp_CanMove($sele_pos, $dest_pos)
{
	//get col/row of starting position
	$col = substr($sele_pos, 0, 1);
	$row = substr($sele_pos, 1, 1);
	
	//check for possible en-passant capture
	$en_passant = world_GetLastDoubleMove() == $dest_pos;
	
	//find type of move
	$move1 = (($dest_pos == $col.($row+1)) && world_PositionEmpty($col.($row+1)));
	$move2 = (($dest_pos == $col.($row+2)) && ($row == 2) && world_PositionEmpty($col.($row+1)) && world_PositionEmpty($col.($row+2)));
	
	//find type of attack
	$atk_norm1 = (($dest_pos == ($col+1).($row+1)) && !world_PositionEmpty(($col+1).($row+1)));
	$atk_norm2 = (($dest_pos == ($col-1).($row+1)) && !world_PositionEmpty(($col-1).($row+1)));
	$atk_pass1 = (($dest_pos == ($col+1).($row+1)) && $en_passant);
	$atk_pass2 = (($dest_pos == ($col-1).($row+1)) && $en_passant);
	
	if ($move1)
	{
		return true;
	}
	else if ($move2)
	{
		world_DeclareDoubleMove($col.($row+1));
		return true;
	}
	else if (($atk_norm1 || $atk_norm2) && world_GetColor($dest_pos) != 'w')
	{
		return true;
	}
	else if ($atk_pass1 || $atk_pass2)
	{
		world_DeclareEnPassantCapture();
		return true;
	}
	else
		return false;
}








function bp_CanMove($sele_pos, $dest_pos)
{
	//get col/row of starting position
	$col = substr($sele_pos, 0, 1);
	$row = substr($sele_pos, 1, 1);	
	
	//check for possible en-passant capture
	$en_passant = world_GetLastDoubleMove() == $dest_pos;
	
	//find type of move
	$move1 = (($dest_pos == $col.($row-1)) && world_PositionEmpty($col.($row-1)));
	$move2 = (($dest_pos == $col.($row-2)) && ($row == 7) && world_PositionEmpty($col.($row-1)) && world_PositionEmpty($col.($row-2)));
		
	//find type of attack
	$atk_norm1 = (($dest_pos == ($col+1).($row-1)) && !world_PositionEmpty(($col+1).($row-1)));
	$atk_norm2 = (($dest_pos == ($col-1).($row-1)) && !world_PositionEmpty(($col-1).($row-1)));
	$atk_pass1 = (($dest_pos == ($col+1).($row-1)) && $en_passant);
	$atk_pass2 = (($dest_pos == ($col-1).($row-1)) && $en_passant);
	
	if ($move1)
	{
		return true;
	}
	else if ($move2)
	{
		world_DeclareDoubleMove($col.($row-1));
		return true;
	}
	else if (($atk_norm1 || $atk_norm2) && world_GetColor($dest_pos) != 'b')
		return true;
	else if ($atk_pass1 || $atk_pass2)
	{
		world_DeclareEnPassantCapture();
		return true;
	}
	else
		return false;
}




//--Fake Attacks--

function bp_FakeAtk($sele_pos, $dest_pos)
{
	//get col/row of starting position
	$col = substr($sele_pos, 0, 1);
	$row = substr($sele_pos, 1, 1);

	if (($dest_pos == ($col+1).($row-1)) || ($dest_pos == ($col-1).($row-1)))
		return true;
	else
		return false;
}



function wp_FakeAtk($sele_pos, $dest_pos)
{
	//get col/row of starting position
	$col = substr($sele_pos, 0, 1);
	$row = substr($sele_pos, 1, 1);

	if (($dest_pos == ($col+1).($row+1)) || ($dest_pos == ($col-1).($row+1)))
		return true;
	else
		return false;
}
















//------------------------King------------------------
function wk_CanMove($sele_pos, $dest_pos)
{
	if (world_IsInArray($dest_pos, attacks_of_king($sele_pos)) && world_GetColor($dest_pos) != 'w')
		return true;
	else
		return false;
}



function bk_CanMove($sele_pos, $dest_pos)
{
	if (world_IsInArray($dest_pos, attacks_of_king($sele_pos)) && world_GetColor($dest_pos) != 'b')
		return true;
	else
		return false;
}










?>