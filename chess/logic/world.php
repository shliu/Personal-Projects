<?php
//updates the chessboard
function world_Update()
{
	//get (update) chessboard
	global $chessboard;
	for ($row = 8; $row >= 1; $row--)
	{
		for ($col = 1; $col <= 8; $col++)
		{
			$chessboard[$col.$row] = $_POST[$col.$row];
		}
	}
	
	//get inputs
	global $sele_pos, $dest_pos, $round, $old_dbl_move;
	$sele_pos = $_POST['sele_pos'];
	$dest_pos = $_POST['dest_pos'];
	$round = $_POST['round'];
	$old_dbl_move = $_POST['new_dbl_move'];
	
	//parse color/type
	global $sele_piece, $sele_color, $dest_piece, $dest_color;
	$sele_piece = world_GetType($sele_pos);
	$sele_color = world_GetColor($sele_pos);
	$dest_piece = world_GetType($dest_pos);
	$dest_color = world_GetColor($dest_pos);
	
	//check who's turn it is
	global $black_move;
	if ($round % 2 == 0)
		$black_move = true;
	else
		$black_move = false;
}







function world_GetSelePos()
{
	global $sele_pos;
	
	return $sele_pos;
}



function world_GetDestPos()
{
	global $dest_pos;
	
	return $dest_pos;
}











//returns the chess piece $type on the specified $position
//may return NULL if no chess piece is found
function world_GetType($position)
{
	global $chessboard;
	
	$type = $chessboard[$position];
	
	return $type;
}










//returns the $color of the chess piece selected by the given $position
//may return NULL if no chess piece is found
function world_GetColor($position)
{
	global $chessboard;
	
	$color = substr($chessboard[$position], 0, 1);
	
	return $color;
}











//returns an ARRAY!!! containing the $positions where the given $type was found on the chessboard
//use print_r($array) to print out everything in the array
function world_GetPosition($type)
{
	global $chessboard;

	for ($row = 8; $row >= 1; $row--)
	{
		for ($col = 1; $col <= 8; $col++)
		{
			if ($chessboard[$col.$row] == $type)
				$array[] = $col.$row;
		}
	}
	
	return $array;
}




function world_GetPositionWK()
{
	global $chessboard;

	for ($row = 8; $row >= 1; $row--)
	{
		for ($col = 1; $col <= 8; $col++)
		{
			if ($chessboard[$col.$row] == 'wk')
				return $col.$row;
		}
	}
}



function world_GetPositionBK()
{
	global $chessboard;

	for ($row = 8; $row >= 1; $row--)
	{
		for ($col = 1; $col <= 8; $col++)
		{
			if ($chessboard[$col.$row] == 'bk')
				return $col.$row;
		}
	}
}








//sets any $position given to the specified $type
function world_SetType($position, $type)
{
	global $chessboard;
	
	$chessboard[$position] = $type;
}














//sets any $position given to NULL
function world_SetNull($position)
{
	global $chessboard;
	
	$chessboard[$position] = NULL;
}













//moves the chess piece of $old_pos[ition] to $new_pos[ition]
function world_Move($old_pos, $new_pos)
{
	global $move_successful, $enpassant_capture, $last_move;
	
	$last_move = array($old_pos, world_GetType($old_pos), $new_pos, world_GetType($new_pos));
	
	if ($enpassant_capture)
		world_EnPassantCapture($old_pos, $new_pos);

	world_SetType($new_pos, world_GetType($old_pos));
	world_SetNull($old_pos);
	
	$move_successful = true;
}








//deletes the last recorded double move position
function world_EnPassantCapture($sele_pos, $dest_pos)
{
	global $last_move;
	
	$dest_col = substr($dest_pos, 0, 1);
	$dest_row = substr($dest_pos, 1, 1);
	
	if (world_GetColor($sele_pos) == 'w')
	{
		$last_move[] = $dest_col.($dest_row - 1);
		$last_move[] = world_GetType($dest_col.($dest_row - 1));
		world_SetNull($dest_col.($dest_row - 1));
	}
	else if (world_GetColor($sele_pos) == 'b')
	{
		$last_move[] = $dest_col.($dest_row + 1);
		$last_move[] = world_GetType($dest_col.($dest_row + 1));
		world_SetNull($dest_col.($dest_row + 1));
	}
}






//can only undo right after a move, during the same round
function world_UndoMove()
{
	global $last_move;
	
	for ($i = 0; $i <= count($last_move) - 1; $i+=2)
	{
		world_SetType($last_move[$i], $last_move[$i+1]);
	}
}







//returns TRUE if given $position on chessboard is empty (NULL)
function world_PositionEmpty($position)
{
	global $chessboard;
	
	if ($chessboard[$position] == NULL)
		return true;
	else
		return false;
}












//returns TRUE if the given $position is within normal chessboard boundaries: 1<=x<=8, 1<=y<=8
function world_IsInBoundary($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);
	
	if ((strlen($position) == 2) && ($col <= 8) && ($col >= 1) && ($row <= 8) && ($row >= 1))
		return true;
	else
		return false;
}







//checks entire $array and see if any element matches the given $position
//return TRUE on first match found
function world_IsInArray($position, $array)
{
	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if ($array[$i] == $position)
			return true;
	}
	return false;
}











//declares the double move made by pawn
function world_DeclareDoubleMove($position)
{
	global $new_dbl_move;
	$new_dbl_move = $position;
}






//get previous double move
function world_GetLastDoubleMove()
{
	global $old_dbl_move;
	return $old_dbl_move;
}








//save previous double move
function world_SaveDoubleMove()
{
	global $old_dbl_move, $new_dbl_move;
	
	$new_dbl_move = $old_dbl_move;
}




//a way to tell world_Move() that it needs to perform en passant capture
function world_DeclareEnPassantCapture()
{
	global $enpassant_capture;
	$enpassant_capture = true;
}


?>