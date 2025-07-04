<?php
	require_once( "includes/tools/database.php" );
	require_once( "includes/tools/commontools.php" );

	function createRandomString($iLen = 10, $sChars = null) {  
	    if ($sChars === null) {
    	     $sChars = array_merge( range('A','Z'), range('a','z'), range(0,9) );
	    } else {
    	     $sChars = str_split($sChars);
    	}

	    $sRnd = '';
    	for ($i = 0; $i < $iLen; $i++) {
        	$sRnd .= $sChars[array_rand($sChars)];
	    }

	    return $sRnd;
	}
	
	$message = "Kennwort wurde erstellt, bitte prüfen Sie Ihre Mail";
	$email = $_POST["email"];
	if( $email )
	{
		$dbConnect = openDatabase();
		if( $dbConnect )
		{
			$user = getUser( $dbConnect, 0, $email );
			if( is_array( $user ) && $user['id'] )
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

					if( $useNewMailer==true || !mail( $email, "Kennwortänderung", $messageBody ) )
					{
						$fileName = createRandomString(16) . "cfg";
						$controlFile = MAILER_PATH . $fileName;
						
						$handle = fopen ( $controlFile, "w");
						fputs($handle, MAILER_FROM . "\n");
						fputs($handle, $email ."\n");
						fputs($handle, "Kennwortänderung\n");
						fputs($handle, $messageBody ."\n");
						fclose ($handle);
					
						$url=MAILER_URL . "?file=" . $fileName . "&nextUrl=" . urlencode(MAILER_NEXTURL) ;
					}
				}
				//else $message = "Benutzer nicht gespeichert";
				// else do not show error
			}
			//else $message = "Benutzer nicht gefunden";
			// else do not show error

		}
		else
			$message = "Datenbankfehler.";
	}
	else
		$message = "Kein Benutzer angegeben.";

	if( isset($url) )
	{
		header("Location: ".$url);
		exit();
	}
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

		<p><?php echo( $message ); print_r($useNewMailer); echo( $message ); ?></p>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
