<?php require_once( "includes/components/login.php" ); ?>
<?php
	$id = $_GET["id"];
		
	$error = deleteUser( $id );
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Benutzer/Gruppe Löschen";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			if( !$error )
				echo "<p>Daten erfolgreich gespeichert.</p>";
			else 
				include "../includes/components/error.php";
		?>
		<p><a href="users.php">&gt;&gt;&nbsp;Benutzerliste</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="groups.php">&gt;&gt;&nbsp;Gruppenliste</a></p>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
