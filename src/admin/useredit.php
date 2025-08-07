<?php require_once( "includes/components/login.php" ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../support/styles.css">
		<?php
			$title = "Benutzer Erfassen";
			include_once( "includes/components/defhead.php" );

			if( array_key_exists( "id", $_GET ) )
				$id = $_GET["id"];
		?>
	</head>
	<body class="center">
		<?php
			include( "includes/components/headerlines.php" );
			if( isset( $id ) )
			{
				$user = getUser( $dbConnect, $id );
				$nachname = $user['nachname'];
				$vorname = $user['vorname'];
				$strasse = $user['strasse'];
				$postfach = $user['postfach'];
				$land = $user['land'];
				$plz = $user['plz'];
				$ort = $user['ort'];
				$email = $user['email'];
			}
			else
			{
				$id = "";
				$nachname = "";
				$vorname = "";
				$strasse = "";
				$postfach = "";
				$land = "";
				$plz = "";
				$ort = "";
				$email = "";
			}
		?>

		<form action="useredit2.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo $id;?>">
			<table>
				<tr><td class="fieldLabel">Name</td><td><input type="text" name="nachname" value="<?php echo htmlspecialchars($nachname, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Vorname</td><td><input type="text" name="vorname" value="<?php echo htmlspecialchars($vorname, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Anschrift</td><td><input type="text" name="strasse" value="<?php echo htmlspecialchars($strasse, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Postfach</td><td><input type="text" name="postfach" value="<?php echo htmlspecialchars($postfach, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Land-PLZ Ort</td><td>
					<input type=text" name="land" value="<?php echo htmlspecialchars($land, ENT_QUOTES, 'ISO-8859-1'); ?>">
					<input type=text" name="plz" value="<?php echo htmlspecialchars($plz, ENT_QUOTES, 'ISO-8859-1'); ?>">
					<input type=text" name="ort" value="<?php echo htmlspecialchars($ort, ENT_QUOTES, 'ISO-8859-1'); ?>">
				</td></tr>
				<tr><td class="fieldLabel">E-Mail</td><td><input type="email" required="required" name="uiemail" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Passwort</td><td><input type="password" name="uipassword"></td></tr>
				<tr><td class="fieldLabel">Passwort (Wdh)</td><td><input type="password" name="uipassword2"></td></tr>
				<tr><td class="fieldLabel">Administrator</td><td><input type="checkbox" name="administrator" value="X"
					<?php if( isset( $user ) && $user['administrator'] ) echo "checked"; ?>
				></td></tr>
				<tr><td class="fieldLabel">Gast-Konto</td><td><input type="checkbox" name="guest" value="X"
					<?php if( isset( $user ) && $user['guest'] ) echo "checked"; ?>
				></td></tr>
				<tr><td class="fieldLabel">Anmeldung erlaubt</td><td><input type="checkbox" name="loginenabled" value="X"
					<?php if( isset( $user ) && $user['loginenabled'] ) echo "checked"; ?>
				></td></tr>
				<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldLabel">&nbsp;</td>
					<td>
						<input type="submit" value="Speichern">
						<?php
							echo "<input type='button' onClick='window.history.back();' value='Abbruch'>";
						?>
					</td>
				</tr>
			</table>
		</form>
		<?php
			if( $id )
			{
				$queryResult = queryDatabase( $dbConnect, "select * from user_login_prot where userid = $1 order by logindate desc", array( $id ) );
				if( $queryResult && !is_object( $queryResult ) )
				{
					echo( "<hr><table><tr><th>Datum</th><th>IP-Adresse</th></tr>\n" );
					while( $row = fetchQueryRow( $queryResult ) )
						echo( "<tr><td>" . formatTimeStamp($row['logindate']) . "</td><td>{$row['remoteip']}</td></tr>\n" );
				}
				echo( "</table>" );
			}
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
