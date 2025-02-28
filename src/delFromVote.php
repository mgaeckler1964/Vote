<?php
	require_once( "includes/components/login.php" ); 
	include_once( "includes/tools/commontools.php" ); 

	$vote_id = $_GET["vote_id"];
	$option_id = $_GET["option_id"];
	$canWrite = true;

	if( !$actUser['administrator'] )
	{
		$vote = getVote($dbConnect,$vote_id);
		if( $vote["user_id"] != $actUser['id'] )
		{
			$canWrite = false;
		}
	}

	if( $canWrite )
	{
		$queryResult = queryDatabase( 
			$dbConnect,
			"delete from vote_options ".
			"where option_id = $1",
			array( $option_id )
		);
	
		if( isset( $queryResult ) && !is_object($queryResult) )
		{
			header("Location: voteEdit.php?vote_id=" . $vote_id );
			exit();
		}
	}
	else
		$error = NO_PERM;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Option Löschen";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			include "includes/components/error.php";
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
