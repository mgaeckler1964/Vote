<?php
	$tryLogin = true;
	include( "includes/components/login.php" );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Impressum";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			if( is_file( "templates/impressum.html" ) )
				include_once( "templates/impressum.html" );

			include( "includes/components/footerlines.php" );
		 ?>
	</body>
</html>
		
