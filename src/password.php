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
			createPasswordStyle();
			
			$pw1id = "pw1";
			$pw1name = "new_password1";
			$pw2id = "pw2";
			$pw2name = "new_password2";

			$toggleBtnId = "toggleBtn";
			$eyeIconId = "eyeIcon";
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>

		<?php if( !$actUser['guest'] ) { ?>

			<form action="password2.php" method="post">
				<table>
					<tr><td class="fieldLabel">Altes Kennwort</td><td><input type="password" name="old_password" value="<?php echo htmlspecialchars($old_password, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
					<tr>
						<td class="fieldLabel">Neues Kennwort</td>
						<td><?php createPasswordInput($pw1id, $pw1name,$toggleBtnId,$eyeIconId); ?></td>
					</tr>
					<tr>
						<td class="fieldLabel">Wiederholung</td>
						<td><?php createPasswordInput($pw2id, $pw2name); ?></td>
					</tr>
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
	<?php createPasswordScript($pw1id,$pw2id,$toggleBtnId,$eyeIconId); ?>
</html>
