<?php
	$guest = "X";
	require_once( "includes/components/login.php" ); 
	$vote_id = $_POST["vote_id"];
	$name = $_POST['name'];
	$voteOptions = getVoteOptions( $dbConnect, $vote_id );
	$vote = getVote( $dbConnect, $vote_id );
	$startTime = $vote['start_time'];
	$endTime = $vote['end_time'];
	$code = $vote['code'];
	$mode = $vote['mode'];
	$canVote = true;
	if($endTime < time())
	{
		$canVote = false;
		$resaon = VOTE_EXPIRED;
	}
	else if($startTime > time())
	{
		$canVote = false;
		$resaon = VOTE_WAITING;
	}
	else if( $code && (!array_key_exists("code", $_POST ) || $_POST['code'] != $code ) )
	{
		$canVote = false;
		$reason = VOTE_CODE;
	}

	if( $canVote )
	{
		$cookieName = "vote".$vote_id;
		if( array_key_exists( $cookieName, $_COOKIE ) )
		{
			$elect_id = $_COOKIE[$cookieName];
			$queryResult = queryDatabase( 
				$dbConnect, 
				"delete from elections ".
				"where elect_id = $1 and vote_id = $2",
				array( $elect_id, $vote_id )
			);
		}
		else
		{
			$elect_id = getNextID( $dbConnect, "elections", "elect_id" );
			if( is_numeric( $elect_id ) )
			{
				setcookie( $cookieName, $elect_id, 0, "/" );
			}
		}
		
		if( is_numeric( $elect_id ) )
		{
			setcookie( "vote_name", $name, 0, "/" );
			if( $mode == 0 )
			{
				forEach( $voteOptions as $voteOption )
				{
					$option_id = $voteOption['option_id'];
					$param = 'voteoption' . $option_id;
		
					if( array_key_exists( $param, $_POST ) )
					{
						$option_id2 = $_POST[$param];
						if( $option_id2 == $option_id )
						{
							$queryResult = queryDatabase( 
								$dbConnect, 
								"insert into elections ".
								"( elect_id, vote_id, name, option_id, the_time ) ".
								"values ".
								"( $1, $2, $3, $4, $5 )", 
								array( $elect_id, $vote_id, $name, $option_id, time() )
							);
							if( !$queryResult || is_object($queryResult) )
							{
								$error = $queryResult;
								break;
							}
						}
					}
				}
			}
			else
			{
				$option_id = $_POST["voteoption"];
				$queryResult = queryDatabase( 
					$dbConnect, 
					"insert into elections ".
					"( elect_id, vote_id, name, option_id, the_time ) ".
					"values ".
					"( $1, $2, $3, $4, $5 )", 
					array( $elect_id, $vote_id, $name, $option_id, time() )
				);
				if( !$queryResult || is_object($queryResult) )
				{
					$error = $queryResult;
				}
			}
		}
		else
			$error = $elect_id;
	}
	else
		$error = $reason;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Abstimmen";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );
/*
			echo( "<pre>" );
			var_dump( $_POST );
			var_dump( $voteOptions );
			var_dump( $error );
			echo( "</pre>" );
*/
			if( isset($error) )
				include "includes/components/error.php";
			else
			{
				echo( "Daten erfolgreich gespeichert." );
				$resultUrl = "result.php?vote_id=".$vote_id;
				if( $code )
					$resultUrl = $resultUrl . "&code=" . $code;
				echo( '<p><a href="' . $resultUrl . '">Ergebniss</a></p>');
			}
			
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
