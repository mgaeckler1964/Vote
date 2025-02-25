<?php require_once( "includes/components/login.php" );?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
	<head>
		<?php
			$title = "Backup/Export";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>
		
		<form action="backup2.php" method="POST">
			Tabelle: <select name="tableName">
				<option value="" selected>Alle Tabellen</option>
				<?php
					foreach( $backupTables as $tableName )
						echo "<option value='$tableName'>$tableName</option>\n";
				?>
			</select>
			
			<br>&nbsp;<br><input type="submit" value="Backup">
		</form>
		<hr>
		<table>
		<?php
			foreach( $backupTables as $tableName ) {
				$queryResult = queryDatabase( $dbConnect, "select count(*) as counter from " .$tableName );
				if( $queryResult && !is_object($queryResult) ) {
					$queryResult = fetchQueryRow( $queryResult );
					if( $queryResult )
						echo "<tr><th>$tableName</th><td>{$queryResult['counter']} Datensätze</td></tr>\n" ;
				}
			}
		?>
		</table>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
