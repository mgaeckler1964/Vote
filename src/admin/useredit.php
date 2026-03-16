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
		if( array_key_exists( "register", $_GET ) )
		{
			$selfRegisterMode = 1;
			$adminMode = 0;
		}
		else if( array_key_exists( "profile", $_GET ) )
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
		include_once("../includes/tools/commontools.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../support/styles.css">
		<?php
			if( $profileMode )
			{
				$title = "Profil &auml;ndern";
				$id = $actUser['id'];
			}
			else if( $selfRegisterMode )
				$title = "Benutzer registrieren";
			else
				$title = "Benutzer erfassen";
			include_once( "includes/components/defhead.php" );

			if( array_key_exists( "id", $_GET ) && !$selfRegisterMode )
				$id = $_GET["id"];

			createPasswordStyle();
			
			$pw1id = "pw1";
			$pw1name = "uipassword";
			$pw2id = "pw2";
			$pw2name = "uipassword2";

			$toggleBtnId = "toggleBtn";
			$eyeIconId = "eyeIcon";

		?>
	</head>
	<body class="center">
		<?php
			include( "includes/components/headerlines.php" );

			if( isset($id) && $profileMode && $actUser['id'] != $id )
			{
				// something went wrong
				echo("<p>Zugriff verweigert.</p></body></html>");
				exit();
			}
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

			<?php if( $selfRegisterMode ) { ?>
				<input type="hidden" name="register" value="1">
			<?php } else if( $profileMode ) { ?>
				<input type="hidden" name="profile" value="1">
			<?php } ?>

			<table>
				<tr><td class="fieldLabel">Name</td><td><input type="text" required="required" name="nachname" value="<?php echo htmlspecialchars($nachname, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Vorname</td><td><input type="text" required="required" name="vorname" value="<?php echo htmlspecialchars($vorname, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Anschrift</td><td><input type="text" name="strasse" value="<?php echo htmlspecialchars($strasse, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Postfach</td><td><input type="text" name="postfach" value="<?php echo htmlspecialchars($postfach, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">Land-PLZ Ort</td><td>
					<input type=text" name="land" size=16 maxlength=16 value="<?php echo htmlspecialchars($land, ENT_QUOTES, 'ISO-8859-1'); ?>">
					<input type=text" name="plz" size=16 maxlength=16 value="<?php echo htmlspecialchars($plz, ENT_QUOTES, 'ISO-8859-1'); ?>">
					<input type=text" name="ort" value="<?php echo htmlspecialchars($ort, ENT_QUOTES, 'ISO-8859-1'); ?>">
				</td></tr>
				<tr><td class="fieldLabel">E-Mail</td><td>
					<?php if( $profileMode ) { ?>
						<input type="hidden" name="profile" value="1">
						<input type="hidden" required="required" name="uiemail" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'ISO-8859-1'); ?>">
						<?php echo htmlspecialchars($email, ENT_QUOTES, 'ISO-8859-1'); ?>
					<?php } else { ?>
						<input type="email" required="required" name="uiemail" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'ISO-8859-1'); ?>">
					<?php } ?>
				</td></tr>

				<?php if( $profileMode ) { ?>
					<tr><td class="fieldLabel">Altes Passwort</td><td><input type="password" name="old_password"></td></tr>
				<?php } ?>
				<?php if( !$selfRegisterMode ) { ?>
					<tr>
						<td class="fieldLabel">Passwort</td>
						<td><?php createPasswordInput($pw1id, $pw1name,$toggleBtnId,$eyeIconId); ?></td>
					</tr>
					<tr><td class="fieldLabel">Passwort (Wdh)</td><td><?php createPasswordInput($pw2id, $pw2name); ?></td></tr>
				<?php } ?>
				<?php if( $adminMode ) { ?>
					<tr><td class="fieldLabel">Administrator</td><td><input type="checkbox" name="administrator" value="X"
						<?php if( isset( $user ) && $user['administrator'] ) echo "checked"; ?>
					></td></tr>
					<tr><td class="fieldLabel">Gast-Konto</td><td><input type="checkbox" name="guest" value="X"
						<?php if( isset( $user ) && $user['guest'] ) echo "checked"; ?>
					></td></tr>
					<tr><td class="fieldLabel">Anmeldung erlaubt</td><td><input type="checkbox" name="loginenabled" value="X"
						<?php if( isset( $user ) && $user['loginenabled'] ) echo "checked"; ?>
					></td></tr>
				<?php } ?>

				<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldLabel">&nbsp;</td>
					<td>
						<input type="submit" value="Speichern">
						<?php
							echo "<input type='button' onClick='";
							if( $adminMode )
								echo "window.history.back();";
							else
								echo "window.document.location.href=\"../index.php\";";
							echo "' value='Abbruch'>";
						?>
					</td>
				</tr>
			</table>
		</form>
		<?php
			if( $id && !$profileMode )
			{
				$queryResult = queryDatabase( $dbConnect, "select * from user_login_prot where userid = $1 order by logindate desc", array( $id ) );
				if( $queryResult && !is_object( $queryResult ) )
				{
					echo( "<hr><table><tr><th>Datum</th><th>IP-Adresse</th></tr>\n" );
					while( $row = fetchQueryRow( $queryResult ) )
						echo( "<tr><td>" . formatTimeStamp($row['logindate']) . "</td><td>{$row['remoteip']}</td></tr>\n" );
					echo( "</table>" );
				}
			}
		?>
		<?php 
			if( !$selfRegisterMode  && !$profileMode )
				include( "includes/components/footerlines.php" ); 
		?>
	</body>
	<?php createPasswordScript($pw1id,$pw2id,$toggleBtnId,$eyeIconId); ?>
</html>
