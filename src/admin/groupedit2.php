<?php
	require_once( "includes/components/login.php" ); 
	$id = $_POST["id"];
	$email = urlencode($_POST["uiemail"]);
		
		
	if( !$id )
	{
		$id = getNextID( $dbConnect, "user_tab", "id" );

		$result = queryDatabase( $dbConnect,
			"insert into user_tab (" .
				"id, email, nachname, vorname, is_group " .
			")" .
			"values" .
			"(" .
				"$1, $2, $3, $4, $5" .
			")",
			array( 
				$id, $email, $email, $email, 'X'
			)
		);
	}
	else
	{
		$result = queryDatabase( $dbConnect, "update user_tab set email = $1 where id = $2", array( $email, $id ) );
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
			$title = "Gruppe Speichern";
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
		<p><a href="groups.php">Gruppenliste</a></p>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
