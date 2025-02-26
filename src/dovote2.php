<?php
	require_once( "includes/components/login.php" ); 
	$vote_id = $_POST["vote_id"];
	$name = $_POST['name'];
	$voteOptions = getVoteOptions( $dbConnect, $vote_id );
	$vote = getVote( $dbConnect, $vote_id );
	$end_time = $vote['end_time'];

	if( $end_time > time() )
	{
		$elect_id = getNextID( $dbConnect, "elections", "elect_id" );
		if( is_numeric( $elect_id ) )
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
			$error = $elect_id;
	}
	else
		$error = "Abstimmung abgelaufen";
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
				echo( "Daten erfolgreich gespeichert." );
			
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
