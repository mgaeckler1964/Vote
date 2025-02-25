<?php require_once( "includes/components/login.php" ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Test";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>
		
		<?php
			$serverName = $_SERVER["SERVER_NAME"];

			$config = "../includes/tools/config." . $serverName . ".php";
			if( !is_file( $config ) )
				echo( "<p>$config nicht geladen.</p>" );

			if( $database != "PG" && $database != "MYSQL"  && $database != "ORA" )
				echo "<p>Ungültiger Datenbanktyp $database.</p>";
			
			if( $database == "PG" )
			{
				if( !function_exists("pg_connect") )
					echo "<p>PostgreSQL-modul nicht verfügbar.</p>";
				if( !function_exists("pg_query_params") )
					echo "<p>PostgreSQL-modul veraltet ( PHP 5.1 erforderlich ).</p>";
			}
			else if( $database == "MYSQL" )
			{
				if( !function_exists("mysql_connect") )
					echo "<p>MySQL-modul nicht verfügbar.</p>";
			}
			else if( $database == "ORA" )
			{
				if( !function_exists("ociplogon") )
					echo "<p>Oracle-modul nicht verfügbar.</p>";
			}
			if( !function_exists("hash") && !function_exists("mhash") )
				echo "<p>Warnung: PECL-modul (1.1 erforderlich) und mhash nicht verfügbar oder veraltet. Keine Kennwortverschlüsselung.</p>";

			$dbConnect = openDatabase();
			if( !$dbConnect )
				echo "<p>Kann keine Verbindung zur Datenbank herstellen.</p>";
			else
			{
				echo "<p>Verbindung zur Datenbank OK.</p>";
		
				if( $database == "PG" )
				{
					$version = pg_version( $dbConnect );
					echo "<p>PostgreSQL Client={$version['client']} Server={$version['server']} Protocol={$version['protocol']}</p>";
				}
				else if( $database == "MYSQL" )
				{
					echo "<p><b>MySQL Server</b> " . mysql_get_server_info($dbConnect) . "</p>";
					echo "<p><b>MySQL Client</b> " . mysql_get_client_info() .           "</p>";
					echo "<p><b>MySQL Prot</b> "   . mysql_get_proto_info ($dbConnect) . "</p>";
					echo "<p><b>MySQL Host</b> "   . mysql_get_host_info  ($dbConnect) . "</p>";
				}
				else if( $database == "ORA" )
				{
					$version = oci_server_version( $dbConnect );
					echo "<p>Oracle Server Version $version</p>";
					$version = oci_client_version( $dbConnect );
					echo "<p>Oracle Client Version $version</p>";
				}
			}
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
