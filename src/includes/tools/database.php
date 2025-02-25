<?php
	require_once( "config.php" );
	
	class errorClass
	{
		public $errorText = "";
		public $errorDetail = "";
		
		function __construct( $errorText, $errorDetail = "" )
		{
			$this->errorText = $errorText;
			if( is_string( $errorDetail ) )
				$this->errorDetail = $errorDetail;
			else
				$this->setErrorDetail( $errorDetail );
		}
		
		public function setErrorDetail( $var )
		{
			ob_start();
			var_dump( $var );
			$this->errorDetail = ob_get_clean();
		}
	};
	function openDatabase()
	{
		global $database, $postgresDB, $mysqlHost, $mysqlUser, $mysqlDB, $mysqlPassword, $oraUser, $oraPassword, $oraConnection;
		
		if( $database == "PG" )
		{
			$dbConn = pg_connect($postgresDB);
			if( !$dbConn )
				$dbConn = new errorClass( "Keine Verbindung zur Datenbank " .$postgresDB );
			else
			{
				pg_set_client_encoding( $dbConn, 'WIN' );
				queryDatabase( $dbConn, "set datestyle to german" );
			}
		}
		else if( $database == "MYSQL" )
		{
			$dbConn = mysql_connect( $mysqlHost, $mysqlUser, $mysqlPassword );
			if( !$dbConn )
				$dbConn = new errorClass( "Keine Verbindung mit der Datenbank " . $mysqlHost ." ". $mysqlUser ." ". $mysqlPassword );
			else
			{
				$success = mysql_select_db( $mysqlDB, $dbConn );
				if( !$success )
				{
					mysql_close( $dbConn );
					$dbConn = new errorClass( "Datenbank (" . $mysqlDB . ") nicht auswählbar. ". mysql_error($dbConn) );
				}
			}
		}
		else if( $database == "ORA" )
		{
			$dbConn = ociplogon( $oraUser, $oraPassword, $oraConnection );
			if( !$dbConn )
			{
				$e = ocierror();
				$dbConn = new errorClass( "Keine Verbindung mit der Datenbank " . $oraConnection . " ".$e['message'], $e );
			}
			else
			{
				queryDatabase( $dbConn, "alter session set nls_date_format='dd.mm.yyyy'" );
			}
		}
			
		return $dbConn;
	}
	

	function oracleEscapeString( $param )
	{
		$newParam = "";

		for( $i=0; $i<strlen( $param ); $i++ )
		{
			$c = substr( $param, $i, 1 );
			$newParam .= $c;
			if( $c == "'" )
				$newParam .= $c;
		}
		
		return $newParam;
	}
	function mergeParams( $query, $params )
	{
		$param = "";
		$paramFound = false;
		$inString = false;
		$newQuery = "";

		for( $i=0; $i<strlen( $query ); $i++ )
		{
			$c = substr( $query, $i, 1 );
			if( $c=='$' && !$inString )
			{
				$paramFound = true;
				$param = "";
			}
			else if( $c>="0" && $c<="9" && $paramFound )
				$param .= $c;
			else if( $paramFound )
			{
				$newQuery .= "'";
				$newQuery .= $params[$param-1];
				$newQuery .= "'";
				$newQuery .= $c;
				$paramFound = false;
			}
			else if( $c == '"' || $c == "'" )
				$inString = !$inString;
			else
				$newQuery .= $c;
		}
		
		if( $paramFound && $param )
		{
			$newQuery .= "'";
			$newQuery .= $params[$param-1];
			$newQuery .= "'";
		}

// echo "<br>$query<br>$newQuery<br>";
		return $newQuery;
	}
	function queryDatabase( $dbConn, $query, $params=null )
	{
		global $database;
		
		if( $database == "PG" )
		{
			if( $params )
				$queryResult = pg_query_params( $dbConn, $query, $params );
			else
				$queryResult = pg_query( $dbConn, $query );
			if( !$queryResult )
				$queryResult = new errorClass( "SQL Befehl (" . $query .") nicht ausführbar. ". pg_last_error($dbConn) ." encoding: ". pg_client_encoding($dbConn), $params );
		}
		else if( $database == "MYSQL" )
		{
			if( $params )
			{
				for( $i=0; $i<count($params); $i++ )
				{
					if( get_magic_quotes_gpc() )
						$params[$i] = stripslashes( $params[$i] );
					
					$params[$i] = mysql_real_escape_string( $params[$i], $dbConn );
				}
					
				$query = mergeParams( $query, $params );
			}

			$queryResult = mysql_query( $query, $dbConn );
			$error = mysql_error( $dbConn );
			if( !$queryResult )
				$queryResult = new errorClass( "SQL Befehl (" . $query .") nicht ausführbar. ". mysql_error($dbConn), $params );
		}
		else if( $database == "ORA" )
		{
			if( $params )
			{
				for( $i=0; $i<count($params); $i++ )
				{
					if( get_magic_quotes_gpc() )
						$params[$i] = stripslashes( $params[$i] );
					
						$params[$i] = oracleEscapeString( $params[$i] );
				}
					
				$query = mergeParams( $query, $params );
			}

//echo $query;				
			$queryResult = ociparse( $dbConn, $query );
			if( !$queryResult )
			{
				$e = ocierror();
				$queryResult = new errorClass( "SQL Befehl (" . $query .") nicht ausführbar. ". $e['message'], $e );
			}
			else
			{
				$flg = ociexecute( $queryResult );
				if( !$flg )
				{
					$queryResult = false;
					$e = ocierror();
					$queryResult = new errorClass( "SQL Befehl (" . $query .") nicht ausführbar. ". $e['message'], $e );
				}
			}
		}
			
		return $queryResult;
	}
	
	function fetchQueryRow( $queryResult )
	{
		global $database;

		if( $database == "PG" )
		{
			$row = pg_fetch_assoc( $queryResult );
		}
		else if( $database == "MYSQL" )
		{
			$fields = mysql_num_fields( $queryResult );
			$row = mysql_fetch_assoc( $queryResult );
			
			if( $row )
			{
				for( $i=0; $i<$fields; $i++ )
				{
					$type = mysql_field_type( $queryResult, $i );
					if( $type == "date" )
					{
						$name = mysql_field_name( $queryResult, $i );
						$value = $row[$name];
						if( $value && $value != "0000-00-00" )
						{
							$dates = explode( "-", $value );
							$row[$name] = $dates[2].".".$dates[1].".".$dates[0];
						}
						else
							$row[$name] = "";
					}
				}
			}
		}
		else if( $database == "ORA" )
		{
			if( ocifetch( $queryResult ) )
			{
				$row = array();
				$numFields = ocinumcols($queryResult);
				for( $i=1; $i<=$numFields; $i++ )
				{
					$row[strtolower(ocicolumnname($queryResult, $i))] = ociresult($queryResult, $i);
				}
			}
		}
		
// print_r( $row );
		return $row;
	}
	
	function getNextID( $dbConn, $tableName, $idField )
	{
		global	$database;

		if( $database == "PG" )
			$result = queryDatabase( $dbConn, "select nextval( 'idSeq' )" );
		else if( $database == "ORACLE" )
			$result = queryDatabase( $dbConn, "select idSeq.nextval from dual" );
		else if( $database == "MYSQL" )
			$result = queryDatabase( $dbConn, "select max( " . $idField . " ) as nextval from " . $tableName );
		if( $result && !is_object( $result ) )
		{
			$row = fetchQueryRow( $result );
			if( $row )
			{
				$nextID = $row['nextval'];
				if( $database == "MYSQL" )
					$nextID += 1;
			}
		}
		else
			$nextID = $result;
		
		return $nextID;
	}
?>
