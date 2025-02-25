<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<?php require_once( "includes/tools/config.php" ); ?>
<html>
	<head>
		<?php
			$title = APPLICATION_NAME . " - Forgot Password";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>
		
		<form action="forgotPassword2.php" method="post">
			<table>
				<tr><td class="fieldLabel">Benutzername (E-Mail)</td><td><input type="email" required="required" name="email"></td></tr>
				<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldLabel">&nbsp;</td>
					<td><input type="submit" value="Kennwort erstellen"></td>
				</tr>
			</table>
		</form>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
