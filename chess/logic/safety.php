<?php
include_once "world.php";
include_once "attacks.php";
include_once "chessmen.php";



function safe_ForWhite($position)
{
	if (safe_from_bh($position) && safe_from_br($position) && safe_from_bb($position)
		&& safe_from_bq($position) && safe_from_bp($position) && safe_from_bk($position))
		return true;
	else
		return false;
}




function safe_ForBlack($position)
{
	if (safe_from_wh($position) && safe_from_wr($position) && safe_from_wb($position)
		&& safe_from_wq($position) && safe_from_wp($position) && safe_from_wk($position))
		return true;
	else
		return false;
}


















//---Safe from BLACK---

function safe_from_bh($position)
{
	$array = world_GetPosition("bh");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (bh_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_br($position)
{
	$array = world_GetPosition("br");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (br_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_bb($position)
{
	$array = world_GetPosition("bb");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (bb_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_bq($position)
{
	$array = world_GetPosition("bq");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (bq_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_bp($position)
{
	$array = world_GetPosition("bp");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (bp_FakeAtk($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_bk($position)
{
	if (bk_CanMove(world_GetPositionBK(), $position))
		return false;
	else
		return true;
}














//---Safe from WHITE---

function safe_from_wh($position)
{
	$array = world_GetPosition("wh");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (wh_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_wr($position)
{
	$array = world_GetPosition("wr");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (wr_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_wb($position)
{
	$array = world_GetPosition("wb");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (wb_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_wq($position)
{
	$array = world_GetPosition("wq");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (wq_CanMove($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_wp($position)
{
	$array = world_GetPosition("wp");

	for ($i = 0; $i <= count($array) - 1; $i++)
	{
		if (wp_FakeAtk($array[$i], $position))
			return false;
	}
	
	return true;
}



function safe_from_wk($position)
{
	if (wk_CanMove(world_GetPositionWK(), $position))
		return false;
	else
		return true;
}










?>










