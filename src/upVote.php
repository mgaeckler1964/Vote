<?php
	require_once( "includes/components/login.php" ); 
	include_once( "includes/tools/commontools.php" ); 

	$vote_id = $_GET["vote_id"];
	$option_id = $_GET["option_id"];
	$prev_id = $_GET["prev_id"];
	$canWrite = true;

	if( !$actUser['administrator'] )
	{
		$vote = getVote($dbConnect,$vote_id);
		if( $vote["user_id"] != $actUser['id'] )
		{
			$error = NO_PERM;
			$canWrite = false;
		}
	}

	if( $canWrite )
	{
		$election_count = getElectionCount( $dbConnect, $vote_id );
		if( $election_count > 0 )
		{
			$canWrite = false;
			$error = "Es gibt schon Abstimmungen";
		}
	}

	if( $canWrite )
	{
		$prevOption = getVoteOption( $dbConnect, $prev_id );
		$thisOption = getVoteOption( $dbConnect, $option_id );
		
		
		$queryResult = queryDatabase( 
			$dbConnect,
			"update vote_options ".
			"set text = $1 ".
			"where option_id = $2",
			array( $thisOption["text"], $prev_id )
		);
	
		if( isset( $queryResult ) && !is_object($queryResult) )
		{
			$queryResult = queryDatabase( 
				$dbConnect,
				"update vote_options ".
				"set text = $1 ".
				"where option_id = $2 ",
				array( $prevOption["text"], $option_id )
			);
		}
		
		if( isset( $queryResult ) && !is_object($queryResult) )
		{
			header("Location: voteEdit.php?vote_id=" . $vote_id );
			exit();
		}
	}
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
