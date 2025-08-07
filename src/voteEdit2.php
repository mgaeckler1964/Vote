<?php
	require_once( "includes/components/login.php" ); 
	$vote_id = 0;
	if( array_key_exists("vote_id", $_POST) )
		$vote_id = $_POST["vote_id"];

	$name = $_POST['name'];
	$question = $_POST['question'];
	$start_time = strtotime($_POST['start_time']);
	$end_time = strtotime($_POST['end_time']);
	$code = $_POST['code'];
	$mode = $_POST['mode'];
	$canWrite = true;

	if( $vote_id )
	{
		if( !$actUser['administrator'] )
		{
			$vote = getVote($dbConnect,$vote_id);
			if( $vote["user_id"] != $actUser['id'] )
			{
				$canWrite = false;
			}
		}
	}
	if( !$vote_id )
	{
		$vote_id = getNextID( $dbConnect, "votes", "vote_id" );
		if( is_numeric( $vote_id ) )
		{
			$queryResult = queryDatabase( 
				$dbConnect, 
				"insert into votes ".
				"( vote_id, user_id, name, question, start_time, end_time, code, mode ) ".
				"values ".
				"( $1, $2, $3, $4, $5, $6, $7, $8 )", 
				array( $vote_id, $actUser['id'], $name, urlencode($question), $start_time, $end_time, $code, $mode )
			);
			$nextURL = "voteEdit.php?vote_id=" . $vote_id;
		}
		else
			$error = $vote_id;
	}
	else if( $canWrite )
	{
		$queryResult = queryDatabase( 
			$dbConnect, 
			"update votes set name = $1, question=$2,  start_time=$3, end_time=$4, code = $5, mode = $6 where vote_id = $7", 
			array( $name, urlencode($question), $start_time, $end_time, $code, $mode, $vote_id ) 
		);
		$nextURL = "index.php";
	}
	else
		$error = NO_PERM;

	if( !isset($error) )
	{
		if( $queryResult && !is_object($queryResult) )
		{
			header("Location: " . $nextURL);
			exit();
		}
		else
			$error = $queryResult;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Abstimmung Speichern";
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
