<?php require_once( "includes/components/login.php" ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Restore/Import";
			include_once( "includes/components/defhead.php" );
		?>
		<script language="JavaScript">
			function checkForm()
			{
				if( document.restoreForm.tableName.value == '-' )
				{
					alert( "Bitte Tabelle auswählen!" );
					return false;
				}
				if( document.restoreForm.backupFile.value == '' )
				{
					alert( "Bitte Datei auswählen!" );
					return false;
				}

				if( document.restoreForm.tableName.value == '' )
					return confirm( "Achtung:\nDies löscht die ganze Datenbank!!!\nForfahren?" );
				
				return true;
			}
		</script>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>
		
		<form action="restore2.php" enctype="multipart/form-data" method="POST" name="restoreForm" onSubmit="return checkForm();">
			Datei: <input type="file" name="backupFile"><br>

			Tabelle: <select name="tableName">
				<option value="-" selected>Bitte auswählen</option>
				<option value="">Alle Tabellen</option>
				<?php
					foreach( $backupTables as $tableName )
						echo "<option value='$tableName'>$tableName</option>\n";
				?>
			</select><br>
	
			Splitgröße: <input type="text" name="chunkSize"> (nur bei einer einzelnen Tabelle)<br>
			
			<br>&nbsp;<br><input type="submit" value="Restore">
		</form>
		<hr>
		<p>Tabellenformate:</p>
		<?php
			foreach( $backupTables as $tableName )
			{
				$queryResult = queryDatabase( $dbConnect, "select * from " . $tableName );
				if( $queryResult && !is_object($queryResult) )
				{
					$queryResult = fetchQueryRow( $queryResult );
					if( $queryResult )
					{
						echo "<pre><b><u>$tableName:</u></b>\n";

						$first = true;
						echo "<b>";
						foreach( $queryResult as $fieldName => $value )
						{
							if( !$first )
								echo( ";" );
							$first = false;
							echo( $fieldName );
						}
						echo( "</b>\n" );
						$first = true;
						foreach( $queryResult as $fieldName => $value )
						{
							if( !$first )
								echo( ";" );
							$first = false;
							echo( $value );
						}
						echo( "\n" );

						echo( "</pre>" );
					}
				}
			}
		?>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
