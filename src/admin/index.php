<?php require_once( "includes/components/login.php" ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
<html>
	<head>
		<?php include_once( "includes/components/defhead.php" ); ?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>

		<p><a href="test.php">Test</a></p>
		<p><a href="phpinfo.php">PHP Info</a></p>
		<hr>
		<p><a href="users.php">Benutzerliste</a></p>
		<p><a href="useredit.php">Neuer Benutzer</a></p>
		<p><a href="groups.php">Gruppenliste</a></p>
		<p><a href="groupedit.php">Neue Gruppe</a></p>
		<hr>
		<?php if( isset( $backupTables ) ) { ?>
			<p><a href="backup.php">Backup</a> (ganze Datenbank oder einzelne Tabelle)</p>
			<p><a href="restore.php">Restore</a> (ganze Datenbank oder einzelne Tabelle)</p>
			<hr>
		<?php } ?>
		<?php if( is_file( "createtables.php" ) ) { ?>
			<p><a href="createtables.php">Tabellen erstellen</a></p>
		<?php } ?>
		<?php if( is_file( "migrate.php" ) ) { ?>
			<p><a href="migrate.php">Tabellen migrieren</a></p>
		<?php } ?>
		<hr>
		<p><a href="../index.php">Hauptmenü</a></p>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
