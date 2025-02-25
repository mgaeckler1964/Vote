<?php include_once( "includes/components/login.php" ); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Neue Abstimmung";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );
		 ?>
		<form action="voteEdit2.php" method="post">
			<table>
				<tr>
					<th>Name</th>
					<td><input type="text" name="name" required size=64 maxlength=255></td>
				</tr>
				<tr>
					<th>Frage</th>
					<td><input type="text" name="question" required size=64 maxlength=255></td>
				</tr>
				<tr>
					<th>Start</th>
					<td><input type="datetime-local" name="start_time" required></td>
				</tr>
				<tr>
					<th>Ende</th>
					<td><input type="datetime-local" name="end_time" required></td>
				</tr>
				<tr>
					<th></th>
					<td><input type="submit"><input type="reset"></td>
				</tr>
			</table>
		
		</form>

		<?php
			include( "includes/components/footerlines.php" );
		 ?>
	</body>
</html>
		
