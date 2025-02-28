<?php
	require_once( "includes/components/login.php" ); 

	$vote_id = $_GET["vote_id"];
	$vote = getVote( $dbConnect, $vote_id );
	$name = $vote['name'];
	$question = $vote['question'];
	$startTime = $vote['start_time'];
	$endTime = $vote['end_time'];
	$code = $vote['code'];
	$mode = $vote['mode'];
	$voteOptions = getVoteOptions( $dbConnect, $vote_id );
	$election_count = getElectionCount( $dbConnect, $vote_id );
	$user_id = $vote['user_id'];
	$user = getUser( $dbConnect, $user_id );
	$fullname = $user['fullname'];

	if( $mode == 0 )
	{
		$multicheck = "checked";
		$singlecheck = "";
	}
	else
	{
		$singlecheck = "checked";
		$multicheck = "";
	}
	
	$canWrite = true;
	if( !$actUser['administrator'] )
	{
		if( $vote["user_id"] != $actUser['id'] )
		{
			$canWrite = false;
		}
	}
	$voteUrl = "dovote.php?vote_id=".$vote_id;
	$resultUrl = "result.php?vote_id=".$vote_id;
	if( $code )
	{
		$voteUrl = $voteUrl  . "&code=".$code;
		$resultUrl = $resultUrl . "&code=".$code;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="support/styles.css">
		<?php
			$title = "Abstimmung Bearbeiten";
			include_once( "includes/components/defhead.php" );

		?>
	</head>
	<body class="center" onLoad="document.getElementById('optionText').focus();">
		<?php
			include( "includes/components/headerlines.php" );
		?>

		<form action="voteEdit2.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="vote_id" value="<?php echo $vote_id;?>">
			<table>
				<tr>
					<td class="fieldLabel">Name</td>
					<td><input type="text" required="required" name="name" size=64 maxlength=255 value="<?php echo htmlspecialchars($name); ?>"></td>
				</tr>
				<tr>
					<td class="fieldLabel">Abstimmcode</td>
					<td>
						<input type="text" name="code" value="<?php echo htmlspecialchars($code); ?>"  size=8 maxlength=8>
						Mit Abstimmcode erscheint die Abstimmung nicht bei den aktiven Abstimmungen und die Stimmabgabe und 
						Ergebnisanzeige erfolgt nur &uuml;ber die URLs unten.
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">Frage</td>
					<td><input type="text" required="required" name="question" size=64 maxlength=255 value="<?php echo htmlspecialchars($question); ?>"></td>
				</tr>
				<tr>
					<td class="fieldLabel">Start</td>
					<td><input type="datetime-local" required="required" name="start_time" value="<?php echo htmlspecialchars(formatHtmlTimeStamp($startTime)); ?>"></td>
				</tr>
				<tr>
					<td class="fieldLabel">Ende</td>
					<td><input type="datetime-local" required="required" name="end_time" value="<?php echo htmlspecialchars(formatHtmlTimeStamp($endTime)); ?>"></td>
				</tr>
				<tr>
					<th>Modus</th>
					<td>
						<input type="radio" name="mode" value="0" <?php echo($multicheck);?> >Mehrfachauswahl<br>
						<input type="radio" name="mode" value="1" <?php echo($singlecheck);?> >Einfachauswahl<br>
					</td>
				</tr>
				<tr>
					<th>Urls</th>
					<td>
						<a href="<?php echo($voteUrl);?>" target="_blank">Abstimmen</a><br>
						<a href="<?php echo($resultUrl);?>" target="_blank">Ergebniss</a>
					</td>
				<tr><td class="fieldLabel">Ersteller</td><td><?php echo htmlspecialchars($fullname); ?></td></tr>
				<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldLabel">&nbsp;</td>
					<td>
						<?php if( $canWrite ) { ?>
							<input type="submit" value="Speichern">
						<?php } ?>

						<input type='button' onClick="document.location.href='index.php'" value='Abbruch'>
					</td>
				</tr>
			</table>
		</form>
		<?php
			if( $vote_id )
			{
				echo( "<hr><table>" );
				forEach( $voteOptions as $voteOption )
				{
					echo( "<tr><td>{$voteOption['text']}</td><td>" );
					if( $canWrite )
					{
						$option_id = $voteOption['option_id'];
						if( $election_count == 0 && isset($prev) )
						{
							echo( "<a href='upVote.php?vote_id=${vote_id}&prev_id={$prev}&option_id=${option_id}'>Oben</a>&nbsp;" );
						}
						$prev = $option_id;
						echo( "<a href='delFromVote.php?vote_id={$vote_id}&option_id={$option_id}'>Löschen</a>");
					}
					else
						echo("-");
					echo( "</td></tr>" );
				}
				echo( "</table>" );

				if ($canWrite )
				{
					echo( "<form name='addOptionForm' action='add2vote.php'>" );
					echo( "<input type='hidden' name='vote_id' value='$vote_id'>" );
					echo( "<input type='text' name='text' id='optionText' required>" );
					echo( "<input type='submit' value='Hinzuf&uuml;gen'>" );
					echo( "</form>" );
				}
				else
					echo( "<p>Speichern nicht erlaubt.</p>" );
				
			}
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
