<?php
	/*
		select the database type:
		MYSQL:	Connect to MySQL
		PG:		Connect to PostgresSQL
		ORA:	Connect to Oracle
	*/
	$database = "ORA";
	$database = "MYSQL";
	$database = "PG";
	
	
	/*
		connect strings für die datenbank:
	*/

	$postgresDB		= "dbname=xxxxx user=xxxxx password=xxxxx";
	$mysqlHost		= "xxxxx";
	$mysqlUser		= "xxxxx";
	$mysqlDB		= "xxxxx";
	$mysqlPassword	= "xxxxx";
	$oraUser		= "xxxxx";
	$oraPassword	= "xxxxx";
	$oraConnection	= "xxxxx";

	$serverName = $_SERVER["SERVER_NAME"];

	$config = "includes/tools/config." . $serverName . ".php";
	if( is_file( $config ) )
		include_once( $config );

	$config = "../includes/tools/config." . $serverName . ".php";
	if( is_file( $config ) )
		include_once( $config );
		
	/*
		application specific constants
	*/
	define( "APPLICATION_NAME", "Abstimmungen" );
	define( "APPLICATION_COPYRIGHT", "&copy; 2025 by <a href='http://www.gaeckler.at/' target='_blank'>Martin G&auml;ckler</a>" );
	
	$backupTables = array( "user_tab", "group_member", "user_login_prot", "strassen", "terminals", "adressen" );
	
	date_default_timezone_set('Europe/Vienna');
?>
