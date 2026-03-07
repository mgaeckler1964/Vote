<?php 
	require_once( "../includes/tools/config.php" );
	if( !defined('SELF_REGISTER') || SELF_REGISTER==0 )
		$selfRegisterOK = 0;
	else
		$selfRegisterOK = 1;

	if($selfRegisterOK && array_key_exists( "register", $_POST ))
		$selfRegisterMode = 1;
	else
		$selfRegisterMode = 0;

	if( !$selfRegisterMode )
		require_once( "includes/components/login.php" );
	else
	{
		require_once( "../includes/tools/commontools.php" );
		require_once( "../includes/tools/database.php" );
		$dbConnect = openDatabase();
	}

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
?>
<?php
	$id = $_POST["id"];
	$nachname = $_POST["nachname"];
	$vorname = $_POST["vorname"];
	$strasse = $_POST["strasse"];
	$postfach = $_POST["postfach"];
	$land = $_POST["land"];
	$plz = $_POST["plz"];
	$ort = $_POST["ort"];
	$email = $_POST["uiemail"];
	
	if( !$selfRegisterMode )
	{
		$password = $_POST["uipassword"];
		$password2 = $_POST["uipassword2"];

		if( $id == 1 || $actUser['id'] == $id ) // root is allways an admin an the current user must not remove his own admin flag
			$administrator = 'X';
		else
			$administrator = array_key_exists( "administrator", $_POST ) ? $_POST["administrator"] : "";
		$guest = array_key_exists( "guest", $_POST ) ? $_POST["guest"] : "";
		$loginenabled = array_key_exists( "loginenabled", $_POST ) ? $_POST["loginenabled"] : "";
	}
	else
	{
		$id = 0;
		$password = createRandomString();
		$password2 = $password;
		$administrator = "";
		$guest = "";
		$loginenabled = "X";
	}

	if( !$id )
	{
		if( !$selfRegisterMode || !checktUser4register( $dbConnect, $email, $_SERVER['REMOTE_ADDR'] ) )
		{
			$id = getNextID( $dbConnect, "user_tab", "id" );
	
			$result = queryDatabase( $dbConnect,
				"insert into user_tab (" .
					"id, nachname, vorname, strasse, postfach, land, plz, ort, email, administrator, guest, loginenabled, cr_time, remoteip " .
				")" .
				"values" .
				"(" .
					"$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14" .
				")",
				array( 
					$id, urlencode($nachname), urlencode($vorname), urlencode($strasse), urlencode($postfach), urlencode($land), 
					urlencode($plz), urlencode($ort), urlencode($email), $administrator, $guest, $loginenabled, time(), $_SERVER['REMOTE_ADDR']
				)
			);
		}
		else
		{
			$result = false;
			$error = "Benutzer kann nicht angelegt werden.";
		}
	}
	else
	{
		$result = queryDatabase( $dbConnect,
			"update user_tab " .
			"set nachname = $1," .
				"vorname = $2," .
				"strasse = $3," .
				"postfach = $4," .
				"land = $5," .
				"plz = $6," .
				"ort = $7, " .
				"email = $8, " .
				"administrator = $9, " .
				"guest = $10, ".
				"loginenabled = $11 " .
			"where id = $12",
			array( 
				urlencode($nachname), urlencode($vorname), urlencode($strasse), urlencode($postfach), urlencode($land), 
				urlencode($plz), urlencode($ort), urlencode($email), $administrator, $guest, $loginenabled,
				$id 
			)
		);
	}
	if( is_object( $result ) )
	{
		$error = $result;
		$result = false;
	}

	if( $result && $password > "" && $password==$password2)
	{
		$result = queryDatabase( $dbConnect,
			"update user_tab " .
			"set password = $1 " .
			"where id = $2",
			array( mgMd5Hash($password), $id )
		);
		if( !$selfRegisterMode && $id == $actUser['id'] )
			setcookie( "password", $password );

	}
	if( is_object( $result ) )
	{
		$error = $result;
		$result = false;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Benutzer Speichern";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			if( $result )
			{
				echo "<p>Daten erfolgreich gespeichert.</p>";
				if( $selfRegisterMode )
				{
					echo "<p>Sie k&oumlnnen sich nun mit dieser E-Mailadresse ein neues Passwort erstellen lassen und sich anmelden. <a href='../forgotPassword.php?email=".urlencode($email)."'>Weiter...</a></p>";
				}
			}
			else if( !$selfRegisterMode )
				include "../includes/components/error.php";
			else
				echo "<p>Fehler beim Speichern, E-Mail schon vergeben?.</p>";
		?>
		<?php if( !$selfRegisterMode ) { ?>
			<p><a href="users.php">Benutzerliste</a></p>
			<?php include( "includes/components/footerlines.php" ); ?>
		<?php } ?>
	</body>
</html>
