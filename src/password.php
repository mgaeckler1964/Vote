<?php include_once( "includes/components/login.php" ); ?>
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
					<tr><td class="fieldLabel">Altes Kennwort</td><td><input type="password" name="old_password"></td></tr>
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
