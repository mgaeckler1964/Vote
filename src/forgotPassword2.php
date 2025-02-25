<?php
	require_once( "includes/tools/database.php" );
	require_once( "includes/tools/commontools.php" );

	$message = "Kennwort wurde erstellt, bitte prüfen Sie Ihre Mail";
	$email = $_POST["email"];
	if( $email )
	{
		$dbConnect = openDatabase();
		if( $dbConnect )
		{
			$user = getUser( $dbConnect, 0, $email );
			if( !is_array( $user ) && $user['id'] )
			{
				$newPasswordLength = mt_rand( 6, 12 );

				$new_password = "";
				
				for( $i=0; $i<$newPasswordLength; $i++ )
					$new_password .= chr( mt_rand( 97, 122) );	// a-z

				$result = queryDatabase( $dbConnect,
					"update user_tab " .
					"set password = $1 " .
					"where id = $2",
					array( mgMd5Hash($new_password), $user['id'] )
				);
				if( $result && !is_object( $result ) )
				{
					$messageBody ="Ihr neues Kennwort lautet " . $new_password;

					if( !mail( $email, "Kennwortänderung", $messageBody ) )
						$message = "Fehler beim Verschicken der Mail";
				}
				// else do not show error
			}
			// else do not show error

		}
		else
			$message = "Datenbankfehler.";
	}
	else
		$message = "Kein Benutzer angegeben.";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Kennwortänderung";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>

		<p><?php echo( $message ) ?></p>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
