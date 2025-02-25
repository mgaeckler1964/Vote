<?php
	require_once( "includes/components/login.php" ); 

	$id = $_GET["id"];
	$oldUserId = $_GET["oldUserId"];
	
	$error = deleteUserFromGroup( $id, $oldUserId );
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

