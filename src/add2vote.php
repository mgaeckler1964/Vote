<?php
	require_once( "includes/components/login.php" ); 
	include_once( "includes/tools/commontools.php" ); 

	$vote_id = $_GET["vote_id"];
	$text = $_GET["text"];
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
		$option_id = getNextID( $dbConnect, "vote_options", "option_id" );
	
		$queryResult = queryDatabase( 
			$dbConnect,
			"insert into vote_options ".
			"(option_id, vote_id, text) ".
			"values ".
			"($1, $2, $3 )",
			array( $option_id, $vote_id, $text )
		);
	
		if( isset( $queryResult ) && !is_object($queryResult) )
		{
			header("Location: voteEdit.php?vote_id=" . $vote_id );
			exit();
		}
	}
	else
		$error = "Keine Berechtigung.";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Option Speichern";
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
