<?php
	$guest = "X";
	require_once( "includes/components/login.php" ); 

	$vote_id = $_GET["vote_id"];
	$vote = getVote( $dbConnect, $vote_id );
	$name = $vote['name'];
	$question = $vote['question'];
	$startTime = $vote['start_time'];
	$endTime = $vote['end_time'];
	$code = $vote['code'];
	$canVote = true;
	if($endTime < time())
	{
		$canVote = false;
	}
	else if($startTime > time())
	{
		$canVote = false;
	}
	else if( $code && (!array_key_exists("code", $_GET ) || $_GET['code'] != $code ) )
	{
		$canVote = false;
	}

	$result = array();
	$counter = array();
	
	if( !$code || (array_key_exists("code", $_GET ) && $_GET['code'] == $code ) )
	{
		$voteOptions = getVoteOptions( $dbConnect, $vote_id );
		$elections = getElections( $dbConnect, $vote_id );

		forEach( $elections as $election )
		{
			$elect_id = $election["elect_id"];
			if( array_key_exists($elect_id, $result ) )
				$row = $result[$elect_id];
			else
				$row = array();
			$option_id = $election["option_id"];
	
			if( array_key_exists($option_id, $counter ) )
				$counter[$option_id] = $counter[$option_id]+1;
			else
				$counter[$option_id] = 1;
			
			$row[$option_id] = $election["name"];
			$result[$elect_id] = $row; 
		}
	}
	else
	{
		$voteOptions = array();
		$elections = array();
		$name = "Falscher Code";
		$question = "Nicht lesbar";
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

		<h2><?php echo htmlspecialchars($name, ENT_QUOTES, 'ISO-8859-1'); ?></h2>
		<h3><?php echo htmlspecialchars($question, ENT_QUOTES, 'ISO-8859-1'); ?></h3>

		<hr><table>

		<?php
			echo( "<tr>" );
			forEach( $voteOptions as $voteOption )
				echo( "<th>". htmlspecialchars($voteOption['text'], ENT_QUOTES, 'ISO-8859-1') ."</th>" );
			echo( "</tr>" );
			
			forEach($result as $row)
			{
				echo( "<tr>" );
				forEach( $voteOptions as $voteOption )
				{
					$option_id = $voteOption["option_id"];
					echo( "<td>" );
					if( array_key_exists($option_id, $row ) )
						echo(htmlspecialchars($row["$option_id"], ENT_QUOTES, 'ISO-8859-1'));
					echo( "</td>" );
				}
				echo( "</tr>" );
			}
			echo( "<tr>" );
			forEach( $voteOptions as $voteOption )
			{
				$option_id = $voteOption["option_id"];
				if( !array_key_exists($option_id, $counter ) )
					$counter[$option_id] = 0;

				echo( "<td>{$counter[$option_id]}</td>" );
			}
			echo( "</tr>" );
		?>
		</table>
		<?php
			if( $canVote )
			{
				$doVoteUrl = "dovote.php?vote_id=".$vote_id;
				if( $code )
					$doVoteUrl = $doVoteUrl . "&code=" . $code;
				echo( '<p><a href="' . $doVoteUrl . '">Abstimmen</a></p>');
			
			}
		?>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
