<?php
include_once "world.php";
include_once "attacks.php";
include_once "chessmen.php";
include_once "safety.php";



//check only
function wk_InCheck()
{
	if (!safe_ForWhite(world_GetPositionWK()))
		return true;
	else
		return false;
}


function bk_InCheck()
{
	if (!safe_ForBlack(world_GetPositionBK()))
		return true;
	else
		return false;
}













/*
1) your king goes into check
2) your king cannot "escape", which is defined by moving the king 
	onto a square that's either empty of an enemy square that's safe
3) your attacker cannot be captured by any piece other than your king 
	(while not putting your king into check in the process)
4) if your attacker is a rook/bishop/queen, none of your pieces 
	can get "between" your king and your attacker (without putting your king into check in the process)
*/
function wk_InCheckmate()
{
	if (!wk_CanEscape() && attacker_IsSafe() && !attacker_Blockable())
		return true;
	else
		return false;
}




function bk_InCheckmate()
{
	if (!bk_CanEscape() && attacker_IsSafe() && !attacker_Blockable())
		return true;
	else
		return false;
}











function wk_CanEscape()
{
	$array = attacks_of_king(world_GetPositionWK());
	
	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if ((world_GetColor($array[$i]) != 'w') && (safe_ForWhite($array[$i])))
			return true;
	}
	
	return false;
}




function bk_CanEscape()
{
	$array = attacks_of_king(world_GetPositionBK());
	
	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if ((world_GetColor($array[$i]) != 'b') && (safe_ForBlack($array[$i])))
			return true;
	}
	
	return false;
}










function attacker_IsSafe()
{
	//get attacker position
	$attacker = world_GetDestPos();
	
	if (world_GetColor($attacker) == 'w')
	{
		if (safe_from_bh($attacker) && safe_from_br($attacker) && safe_from_bb($attacker)
			&& safe_from_bq($attacker) && safe_from_bp($attacker))
			return true;
	}
	else if (world_GetColor($attacker) == 'b')
	{
		if (safe_from_wh($attacker) && safe_from_wr($attacker) && safe_from_wb($attacker)
			&& safe_from_wq($attacker) && safe_from_wp($attacker))
			return true;
	}
	
	return false;
}







function attacker_Blockable()
{
	$attacker = world_GetType(world_GetDestPos());
	
	if (($attacker == 'wr') || ($attacker == 'wb') || ($attacker == 'wq'))
	{
		$route = route_of_attack();

		if (bh_CanIntercept($route)|| br_CanIntercept($route) || bb_CanIntercept($route) || bq_CanIntercept($route) || bp_CanIntercept($route))
			return true;
	}
	if (($attacker == 'br') || ($attacker == 'bb') || ($attacker == 'bq'))
	{
		$route = route_of_attack();

		if (wh_CanIntercept($route)|| wr_CanIntercept($route) || wb_CanIntercept($route) || wq_CanIntercept($route) || wp_CanIntercept($route))
			return true;
	}
	else
		return false;
}







function route_of_attack()
{
	$attacker_pos = world_GetDestPos();
	
	if (world_GetColor($attacker_pos) == 'w')
		$king = world_GetPositionBK();
	else
		$king = world_GetPositionWK();

	if (world_IsInArray($king, attacks_going_N($attacker_pos)))
		return attacks_going_N($attacker_pos);
	else if (world_IsInArray($king, attacks_going_S($attacker_pos)))
		return attacks_going_S($attacker_pos);
	else if (world_IsInArray($king, attacks_going_E($attacker_pos)))
		return attacks_going_E($attacker_pos);
	else if (world_IsInArray($king, attacks_going_W($attacker_pos)))
		return attacks_going_W($attacker_pos);
	else if (world_IsInArray($king, attacks_going_NE($attacker_pos)))
		return attacks_going_NE($attacker_pos);
	else if (world_IsInArray($king, attacks_going_NW($attacker_pos)))
		return attacks_going_NW($attacker_pos);
	else if (world_IsInArray($king, attacks_going_SE($attacker_pos)))
		return attacks_going_SE($attacker_pos);
	else if (world_IsInArray($king, attacks_going_SW($attacker_pos)))
		return attacks_going_SW($attacker_pos);
}






function bh_CanIntercept($route)
{
	$piece_pos = world_GetPosition("bh");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (bh_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!bk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}


function br_CanIntercept($route)
{
	$piece_pos = world_GetPosition("br");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (br_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!bk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}



function bb_CanIntercept($route)
{
	$piece_pos = world_GetPosition("bb");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (bb_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!bk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}




function bq_CanIntercept($route)
{
	$piece_pos = world_GetPosition("bq");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (bq_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!bk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}




function bp_CanIntercept($route)
{
	$piece_pos = world_GetPosition("bp");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (bp_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!bk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}


















function wh_CanIntercept($route)
{
	$piece_pos = world_GetPosition("wh");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (wh_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!wk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}


function wr_CanIntercept($route)
{
	$piece_pos = world_GetPosition("wr");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (wr_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!wk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}



function wb_CanIntercept($route)
{
	$piece_pos = world_GetPosition("wb");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (wb_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!wk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}




function wq_CanIntercept($route)
{
	$piece_pos = world_GetPosition("wq");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (wq_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!wk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}



function wp_CanIntercept($route)
{
	$piece_pos = world_GetPosition("wp");
	
	for ($i = 0; $i <= count($route) - 2; $i++) //go through all the boxes in the attack route
	{
		for ($j = 0; $j <= count($piece_pos) - 1; $j++) //go through every instance of bh
		{
			if (wp_CanMove($piece_pos[$j], $route[$i]))
			{
				world_Move($piece_pos[$j], $route[$i]);
				
				if (!wk_InCheck())
					$result = true;
				else
					$result = false;
				
				world_UndoMove();
			}
		}
	}
	
	return $result;
}



?>


















