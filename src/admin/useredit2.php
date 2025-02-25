<?php require_once( "includes/components/login.php" ); ?>
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
	$password = $_POST["password"];
	$password2 = $_POST["password2"];
		
		
	if( $id == 1 || $actUser['id'] == $id ) // root is allways an admin an the current user must not remove his own admin flag
		$administrator = 'X';
	else
		$administrator = array_key_exists( "administrator", $_POST ) ? $_POST["administrator"] : "";
	$guest = array_key_exists( "guest", $_POST ) ? $_POST["guest"] : "";
	$loginenabled = array_key_exists( "loginenabled", $_POST ) ? $_POST["loginenabled"] : "";

	if( !$id )
	{
		$id = getNextID( $dbConnect, "user_tab", "id" );

		$result = queryDatabase( $dbConnect,
			"insert into user_tab (" .
				"id, nachname, vorname, strasse, postfach, land, plz, ort, email, administrator, guest, loginenabled " .
			")" .
			"values" .
			"(" .
				"$1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12" .
			")",
			array( 
				$id, $nachname, $vorname, $strasse, $postfach, $land, $plz, $ort, $email, $administrator, $guest, $loginenabled
			)
		);
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
				$nachname, $vorname, $strasse, $postfach, $land, $plz, $ort,
				$email, $administrator, $guest, $loginenabled,
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
				echo "<p>Daten erfolgreich gespeichert.</p>";
			else
				include "../includes/components/error.php";
		?>
		<p><a href="users.php">Benutzerliste</a></p>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
