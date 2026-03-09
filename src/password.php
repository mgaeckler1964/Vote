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
		<style>
			#toggleBtn {
				position: relative;
				top: 15px;
				transform: translateY(-50%);
				background: none;
				border: none;
				cursor: pointer;
				color: #666;
			}
			#toggleBtn:hover { color: #000; }
		</style>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>

		<?php if( !$actUser['guest'] ) { ?>

			<form action="password2.php" method="post">
				<table>
					<tr><td class="fieldLabel">Altes Kennwort</td><td><input type="password" name="old_password" value="<?php echo htmlspecialchars($old_password, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
					<tr>
						<td class="fieldLabel">Neues Kennwort</td>
						<td>
							<input type="password" id="pw1" name="new_password1"> 
							<button type="button" id="toggleBtn" aria-label="Passwort anzeigen">
								<svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
									viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
									<circle cx="12" cy="12" r="3"></circle>
								</svg>
							</button>
						</td>
					</tr>
					<tr><td class="fieldLabel">Wiederholung</td><td><input type="password" id="pw2" name="new_password2"></td></tr>
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
	<script>
		const pw1 = document.getElementById('pw1');
		const pw2 = document.getElementById('pw2');
		const toggleBtn = document.getElementById('toggleBtn');
		const eyeIcon = document.getElementById('eyeIcon');
		
		toggleBtn.addEventListener('click', () => {
			const isPassword = pw1.type === 'password';

			// Typ umschalten
			pw1.type = isPassword ? 'text' : 'password';
			pw2.type = pw1.type;
			  
			// Icon anpassen (Beispiel: Auge offen / Auge mit Querstrich)
			if (isPassword) {
				// SVG für "Auge offen" (Standard)
				eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
			} else {
				// Zurück zum normalen Auge
				eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
			}
		});
	</script>
</html>
