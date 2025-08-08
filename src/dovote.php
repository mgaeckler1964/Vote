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
	$mode = $vote['mode'];
	$voteOptions = getVoteOptions( $dbConnect, $vote_id );
	$canVote = true;
	if($endTime < time())
	{
		$canVote = false;
		$reason = VOTE_EXPIRED;
	}
	else if($startTime > time())
	{
		$canVote = false;
		$reason = VOTE_WAITING;
	}
	else if( $code && (!array_key_exists("code", $_GET ) || $_GET['code'] != $code ) )
	{
		$canVote = false;
		$reason = VOTE_CODE;
	}
	if( array_key_exists( "vote_name", $_COOKIE ) )
		$vote_name = urldecode($_COOKIE["vote_name"]);
	else
		$vote_name = "";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="support/styles.css">
		<?php
			$title = "Abstimmen";
			include_once( "includes/components/defhead.php" );

		?>
	</head>
	<body class="center">
		<?php
			include( "includes/components/headerlines.php" );
		?>

		<h2><?php echo htmlspecialchars($name, ENT_QUOTES, 'ISO-8859-1'); ?></h2>

		<?php if( $canVote ) { ?>
		
			<form action="dovote2.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="vote_id" value="<?php echo $vote_id;?>">
				<input type="hidden" name="code" value="<?php echo $code;?>">
				<table>
					<tr>
						<td class="fieldLabel">Name</td>
						<td>
							<input type="text" name="name" required value="<?php echo( htmlspecialchars($vote_name, ENT_QUOTES, 'ISO-8859-1') ); ?>">
						</td>
					</tr>
					<tr>
						<td class="fieldLabel">Frage</td>
						<td><?php echo htmlspecialchars($question, ENT_QUOTES, 'ISO-8859-1'); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Start</td>
						<td><?php echo htmlspecialchars(formatTimeStamp($startTime)); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Ende</td>
						<td><?php echo htmlspecialchars(formatTimeStamp($endTime)); ?></td>
					</tr>
					<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
					<tr>
						<td class="fieldLabel">&nbsp;</td>
						<td><hr>
							<?php
								$checked = "checked";
								forEach( $voteOptions as $voteOption )
								{
									if( $mode == 0 )
										echo( "<input type='checkbox' name='voteoption${voteOption['option_id']}' value='${voteOption['option_id']}' >". htmlspecialchars($voteOption['text'], ENT_QUOTES, 'ISO-8859-1') ."<br>" );
									else
										echo( "<input type='radio' {$checked} name='voteoption' value='${voteOption['option_id']}' >". htmlspecialchars($voteOption['text'], ENT_QUOTES, 'ISO-8859-1') ."<br>" );
									$checked = "";
								}
				
							?>
						<hr></td>
					</tr>
					<tr>
						<td class="fieldLabel">&nbsp;</td>
						<td>
							<input type="submit" value="Speichern">
							<input type='button' onClick="document.location.href='index.php'" value='Abbruch'>
						</td>
					</tr>
				</table>
			</form>
		<?php } else {
			echo("<p>{$reason}</p>");
		} ?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
