<?php include_once( "includes/components/login.php" ); ?>
<?php

	$id = $actUser["id"];
	$old_password = $_POST["old_password"];
	$new_password1 = $_POST["new_password1"];
	$new_password2 = $_POST["new_password2"];
	
	if( $actUser['guest'] )
		$error = "Nicht erlaubt";
	else if( $actUser['password'] && $actUser['password'] != mgMd5Hash($old_password) )
		$error = "Altes Kennwort ist falsch#1";
	else if( !$actUser['password'] && $old_password )
		$error = "Altes Kennwort ist falsch#2";
	else if( $new_password1 != $new_password2 )
		$error = "Kennwörter stimmen nicht überein";
	else
	{
		if( $new_password1 )
		{
			$result = queryDatabase( $dbConnect,
				"update user_tab " .
				"set password = $1 " .
				"where id = $2",
				array( mgMd5Hash($new_password1), $id )
			);
		}
		else
		{
			$result = queryDatabase( $dbConn,
				"update user_tab " .
				"set password = null " .
				"where id = $1",
				array( $id )
			);
		}
		if( $result && !is_object( $result ) )
			setcookie( "password", $new_password1 );
		else
			$error = $result;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Kennwort Speichern";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			if( !isset( $error ) )
				echo "<p>Kennwort erfolgreich gespeichert.</p>\n";
			else
				include "includes/components/error.php";
				
			include( "includes/components/footerlines.php" ); 
		?>
	</body>
</html>
