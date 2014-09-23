<?php 
//Global vars - sets table cell dimensions and colors
$cell_w = 75;						//cell width
$cell_h = 75;						//cell height
$cell_light = "EEEEEE";				//RGB color of "light" boxes
$cell_dark = "ABCDEF";				//RGB color of "dark" boxes



include_once "board_design.php";
include_once "logic/world.php";
include_once "logic/chessmen.php";
include_once "logic/safety.php";
include_once "logic/checks.php";




if ($_POST['Submit'])
{
	world_Update();

	if ($black_move)	//******************Black***************************
	{
		if ($sele_piece == 'bh')		//**Knight**
		{
			if (bh_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'br')	//**Rook**
		{
			if (br_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'bb')	//**Bishop**
		{
			if (bb_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'bq')	//**Queen**
		{
			if (bq_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'bp')	//**Pawn**
		{
			if (bp_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'bk')	//**King**
		{
			if (bk_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
	}
	else				//******************White***************************
	{
		if ($sele_piece == 'wh')		//**Knight**
		{
			if (wh_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'wr')	//**Rook**
		{
			if (wr_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'wb')	//**Bishop**
		{
			if (wb_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'wq')	//**Queen**
		{
			if (wq_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'wp')	//**Pawn**
		{
			if (wp_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
		else if ($sele_piece == 'wk')	//**King**
		{
			if (wk_CanMove($sele_pos, $dest_pos))
				world_Move($sele_pos, $dest_pos);
		}
	}
	
	
	if (($black_move && bk_InCheck()) || (!$black_move && wk_InCheck()))
	{
		if ($move_successful)
		{
			echo "bad move, that puts your king in check";
			$round--;
		}
		else
		{
			echo "still in check";
		}
		world_UndoMove();
	}
	else if ($black_move && wk_InCheck())
	{
		if (wk_InCheckmate())
			echo "**CHECKMATE** wk";
		else
			echo "check wk";
	}
	else if (!$black_move && bk_InCheck())
	{
		if (bk_InCheckmate())
			echo "**CHECKMATE** bk";
		else
			echo "check bk";
	}

	
	if ($move_successful)
		$round++;
	else
		world_SaveDoubleMove();	//if no legit move was made, set $new_dbl_move = $old_dbl_move
}
?>




<html>
	<head>
		<title>PHP Chess Game</title>
	</head>
	<body>
		<?php echo "$error_msg";?>
		<form method="post" action="">
		<?php
		if ($_POST['Submit'])
		{
			include 'display_next_board.php';
		}
		else
		{
			include 'display_initial_board.php';
		}
		echo "<input type='hidden' name='new_dbl_move' value='$new_dbl_move'>";
		?>
		<input type="submit" name="Submit" value="Submit"/>
		<input type="submit" name="Reset" value="Reset"/>
		</form>
	</body>
</html>













