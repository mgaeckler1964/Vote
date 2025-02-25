<?php
	require_once( "includes/components/login.php" ); 

	$vote_id = $_GET["vote_id"];
	$vote = getVote( $dbConnect, $vote_id );
	$name = $vote['name'];
	$question = $vote['question'];
	$startTime = $vote['start_time'];
	$endTime = $vote['end_time'];
	$voteOptions = getVoteOptions( $dbConnect, $vote_id );
	$elections = getElections( $dbConnect, $vote_id );

	$result = array();
	forEach( $elections as $election )
	{
		$elect_id = $election["elect_id"];
		if( array_key_exists($elect_id, $result ) )
			$row = $result[$elect_id];
		else
			$row = array();
		$option_id = $election["option_id"];
		$row[$option_id] = $election["name"];
		$result[$elect_id] = $row; 
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="support/styles.css">
		<?php
			$title = "Abstimmungsergebniss";
			include_once( "includes/components/defhead.php" );

		?>
	</head>
	<body class="center">
		<?php
			include( "includes/components/headerlines.php" );
		?>

		<h2><?php echo htmlspecialchars($name); ?></h2>
		<h3><?php echo htmlspecialchars($question); ?></h3>

		<hr><table>

		<?php
			echo( "<tr>" );
			forEach( $voteOptions as $voteOption )
				echo( "<td>{$voteOption['text']}</td>" );
			echo( "</tr>" );
			
			forEach($result as $row)
			{
				echo( "<tr>" );
				forEach( $voteOptions as $voteOption )
				{
					$option_id = $voteOption["option_id"];
					echo( "<td>" );
					if( array_key_exists($option_id, $row ) )
						echo($row["$option_id"]);
					echo( "</td>" );
				}
				echo( "</tr>" );
			}
		?>
		</table>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
