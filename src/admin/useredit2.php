<?php 
	require_once( "../includes/tools/config.php" );
	if( !defined('SELF_REGISTER') || SELF_REGISTER==0 )
		$selfRegisterOK = 0;
	else
		$selfRegisterOK = 1;

	$selfRegisterMode = 0;
	$profileMode = 0;
	$adminMode = 1;
	if($selfRegisterOK)
	{
		if( array_key_exists( "register", $_POST ) )
		{
			$selfRegisterMode = 1;
			$adminMode = 0;
		}
		else if( array_key_exists( "profile", $_POST ) )
		{
			$profileMode = 1;
			$adminMode = 0;
		}
	}

	if( $adminMode )
		require_once( "includes/components/login.php" );
	else if( $profileMode )
		require_once( "../includes/components/login.php" );
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

	$nachname = $_POST["nachname"];
	$vorname = $_POST["vorname"];
	$strasse = $_POST["strasse"];
	$postfach = $_POST["postfach"];
	$land = $_POST["land"];
	$plz = $_POST["plz"];
	$ort = $_POST["ort"];
	
	if( $selfRegisterMode )		// register a new user
	{
		$id = 0;
		$old_password = "";
		$password = createRandomString();
		$password2 = $password;
		$administrator = "";
		$guest = "";
		$loginenabled = "X";
		$email = $_POST["uiemail"];
	}
	else if( $profileMode )		// edit yourself
	{
		$id = $actUser['id'];
		$old_password = $_POST["old_password"];
		$password = $_POST["uipassword"];
		$password2 = $_POST["uipassword2"];
	}
	else						// admin mode edit any user
	{
		$id = $_POST["id"];
		$old_password = "";
		$password = $_POST["uipassword"];
		$password2 = $_POST["uipassword2"];
		$email = $_POST["uiemail"];

		if( $id == 1 || $actUser['id'] == $id ) // root is allways an admin an the current user must not remove his own admin flag
			$administrator = 'X';
		else
			$administrator = array_key_exists( "administrator", $_POST ) ? $_POST["administrator"] : "";
		$guest = array_key_exists( "guest", $_POST ) ? $_POST["guest"] : "";
		$loginenabled = array_key_exists( "loginenabled", $_POST ) ? $_POST["loginenabled"] : "";
	}

	if( isset($email) )
	{
		$existUser = getUser( $dbConnect, $id, $email );
		if( $existUser && array_key_exists( 'id', $existUser ) && $existUser['id'] != $id )
		{
			$error = "E-Mail existiert bereits";
			$result = false;
		}
	}
	
	if( !isset($error) )
	{
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
		else if( $profileMode )
		{
			$result = queryDatabase( $dbConnect,
				"update user_tab " .
				"set nachname = $1," .
					"vorname = $2," .
					"strasse = $3," .
					"postfach = $4," .
					"land = $5," .
					"plz = $6," .
					"ort = $7 " .
				"where id = $8",
				array( 
					urlencode($nachname), urlencode($vorname), urlencode($strasse), urlencode($postfach), urlencode($land), 
					urlencode($plz), urlencode($ort),
					$id 
				)
			);
		}
		else
		{
			// admin mode
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
	}
	if( is_object( $result ) )
	{
		$error = $result;
		$result = false;
	}

	if( $result && $profileMode )
	{
		if( !$old_password )
		{
			$password = "";
			$password2 = "";
		}
		else if( $actUser['password'] != mgMd5Hash($old_password) )
		{
			$result = false;
			$error = "Falsche Kennwort";
		}
	}
	if( $result && $password > "" && $password!=$password2)
	{
		$result = false;
		$error = "Kennw&ouml;rter nicht identisch.";
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
		{
			setcookie( "password", $password );
		}
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
			if( $profileMode )
				$title = "Profil speichern";
			else
				$title = "Benutzer speichern";
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
				echo "<p>Fehler beim Speichern. E-Mail schon vergeben?</p>";
		?>
		<?php if( $adminMode ) { ?>
			<p>
				<a href="users.php">&gt;&gt;&nbsp;Benutzerliste</a>
				<a href="#" onClick="window.history.back();">&gt;&gt;&nbsp;Zur&uuml;ck</a>
			</p>
			<?php include( "includes/components/footerlines.php" ); ?>
		<?php } else { ?>
			<p>
				<a href="../">&gt;&gt;&nbsp;Startseite</a> 
				<a href="#" onClick="window.history.back();">&gt;&gt;&nbsp;Zur&uuml;ck</a>
			</p>
			<?php include( "../includes/components/footerlines.php" ); ?>
		<?php } ?>
	</body>
</html>
