<?php 
	include_once( "includes/components/login.php" ); 
	
	$old_password = "";
	if( array_key_exists( "password", $_GET ) )
		$old_password = $_GET["password"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Kennwort ändern";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>

		<?php if( !$actUser['guest'] ) { ?>

			<form action="password2.php" method="post">
				<table>
					<tr><td class="fieldLabel">Altes Kennwort</td><td><input type="password" name="old_password" value="<?php echo htmlspecialchars($old_password, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
					<tr><td class="fieldLabel">Neues Kennwort</td><td><input type="password" name="new_password1"></td></tr>
					<tr><td class="fieldLabel">Wiederholung</td><td><input type="password" name="new_password2"></td></tr>
					<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
					<tr>
						<td class="fieldLabel">&nbsp;</td>
						<td>
							<input type='submit' value='Speichern'>
							<input type='button' onClick='window.history.back();' value='Abbruch'>
						</td>
					</tr>
				</table>
			</form>
		<?php } else { ?>
			<p>Nicht erlaubt</p>
		<?php } ?>
		
	
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
