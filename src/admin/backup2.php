<?php
	require_once( "includes/components/login.php" );

	function backupTable( $dbConnect, $tableName, $allTables )
	{
		$query = "select * from " . $tableName;
		$queryResult = queryDatabase( $dbConnect, $query );
		if( $queryResult && !is_object($queryResult)  )
		{
			$row = fetchQueryRow( $queryResult );
			if( $row )
			{
				if( $allTables )
					echo ">>>$tableName\n";
				$keys = array_keys( $row );
				for( $i=0; $i<count( $keys ); $i++ )
				{
					if( $i )
						echo ";";
					echo $keys[$i];
				}
				echo "\n";
				while( $row )
				{
					for( $i=0; $i<count( $keys ); $i++ )
					{
						if( $i )
							echo ";";
						echo '"' . addslashes($row[$keys[$i]]) . '"';
					}
					echo "\n";
					$row = fetchQueryRow( $queryResult );
				}
				if( $allTables )
					echo ">>>END\n";
			}
		}
	}

	if( array_key_exists( "tableName", $_POST ) )
		$tableName = $_POST['tableName'];

	header( "Content-Type: text/csv" );

	if( isset( $tableName ) && $tableName )
	{
		header( 'Content-Disposition: attachment; filename="'. $tableName .'.csv"' );
			
		backupTable( $dbConnect, $tableName, false );
	}
	else
	{
		header( 'Content-Disposition: attachment; filename="backup.csv"' );
			
		foreach( $backupTables as $tableName )
			backupTable( $dbConnect, $tableName, true );
	}
	exit();
?>
