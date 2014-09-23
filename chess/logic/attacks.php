<?php
include_once "world.php";



function attacks_of_king($position)
{
	$king_col = substr($position, 0, 1);
	$king_row = substr($position, 1, 1);

	for ($col = $king_col - 1; $col <= $king_col + 1; $col++)
	{
		for ($row = $king_row - 1; $row <= $king_row + 1; $row++)
		{
			if (($col.$row != $position) && world_IsInBoundary($col.$row))
				$array[] = $col.$row;
		}	
	}

	return $array;
}












function attacks_of_knight($position)
{
	$start_col = substr($position, 0, 1);
	$start_row = substr($position, 1, 1);

	for ($col = $start_col - 2; $col <= $start_col + 2; $col++)
	{
		for ($row = $start_row - 2; $row <= $start_row + 2; $row++)
		{
			$change_col = abs($start_col - $col);
			$change_row = abs($start_row - $row);
			if (((($change_row == 2) && ($change_col == 1)) || (($change_row == 1) && ($change_col == 2))) && (world_IsInBoundary($col.$row)))
				$array[] = $col.$row;
		}
	}
	
	return $array;
}













function attacks_going_N($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);
	
	$row++;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$row++;
	}
	
	return $array;
}







function attacks_going_S($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$row--;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$row--;
	}
	
	return $array;
}









function attacks_going_E($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$col++;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$col++;
	}
	
	return $array;
}










function attacks_going_W($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$col--;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$col--;
	}
	
	return $array;
}








function attacks_going_NE($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$col++;
	$row++;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$col++;
		$row++;
	}
	
	return $array;
}










function attacks_going_NW($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$col--;
	$row++;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$col--;
		$row++;
	}
	
	return $array;
}











function attacks_going_SE($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$col++;
	$row--;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$col++;
		$row--;
	}
	
	return $array;
}











function attacks_going_SW($position)
{
	$col = substr($position, 0, 1);
	$row = substr($position, 1, 1);

	$col--;
	$row--;
	while (world_IsInBoundary($col.$row))
	{
		$array[] = $col.$row;
		if (!world_PositionEmpty($col.$row))
			break;
		$col--;
		$row--;
	}
	
	return $array;
}
?>
