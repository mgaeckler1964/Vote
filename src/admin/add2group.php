<?php
	require_once( "includes/components/login.php" ); 

	$id = $_GET["id"];
	$newUserId = $_GET["newUserId"];
	
	$error = addUser2Group( $id, $newUserId );
	if( !$error )
	{
		header("Location: groupedit.php?id=" . $id );
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Fehler";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php 
			include( "includes/components/headerlines.php" ); 
			include( "../includes/components/error.php" ); 
			include( "includes/components/footerlines.php" );
		?>
	</body>
</html>

