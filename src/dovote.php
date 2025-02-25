<?php
	require_once( "includes/components/login.php" ); 

	$vote_id = $_GET["vote_id"];
	$vote = getVote( $dbConnect, $vote_id );
	$name = $vote['name'];
	$question = $vote['question'];
	$startTime = $vote['start_time'];
	$endTime = $vote['end_time'];
	$voteOptions = getVoteOptions( $dbConnect, $vote_id );
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

		<h2><?php echo htmlspecialchars($name); ?></h2>

		<form action="dovote2.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="vote_id" value="<?php echo $vote_id;?>">
			<table>
				<tr>
					<td class="fieldLabel">Name</td>
					<td>
						<input type="text" name="name" required>
					</td>
				</tr>
				<tr>
					<td class="fieldLabel">Frage</td>
					<td><?php echo htmlspecialchars($question); ?></td>
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
							forEach( $voteOptions as $voteOption )
								echo( "<input type='checkbox' name='voteoption${voteOption['option_id']}' value='${voteOption['option_id']}' >{$voteOption['text']}<br>" );
			
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
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
