<?php
	setcookie( "userID", "", 0, "/" );
	setcookie( "password", "", 0, "/" );
	session_start();
	$_SESSION = array();
	session_destroy();
	include_once( "includes/tools/config.php" );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Abmelden";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>

		<p>Sie wurden abgemeldet.</p>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
