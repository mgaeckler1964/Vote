<?php require_once( "includes/components/login.php" ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Restore/Import";
			include_once( "includes/components/defhead.php" );

			function restoreTable( $tableName, $file, &$numRecords, $minRec = 0, $maxRec = -1 )
			{
				global $database;
				
				$maxID = 0;
				$idIndex = 0;

				$counter = 0;
				$dbConnect = openDatabase();
				if( $dbConnect )
				{
					if( $minRec <= 0 )
						queryDatabase( $dbConnect, "delete from " . $tableName );

					$fieldNames = fgetcsv( $file, 100000, ';', '"' );
					$insert = "insert into " . $tableName . "(";
					for( $i=0; $i<count($fieldNames); $i++ )
					{
						if( $i )
							$insert .= ',';
						$insert .= $fieldNames[$i];
						if( strtoupper( $fieldNames[$i] ) == "ID" )
							$idIndex = $i;
					}
					$insert .= ') values (';
					for( $i=1; $i<=count($fieldNames); $i++ )
					{
						if( $i > 1 )
							$insert .= ',';
						$insert .= "\$" . $i;
					}
					$insert .= ')';
					
					echo $insert;

					while( !feof( $file ) )
					{
						$data = fgetcsv( $file, 100000, ';', '"' );
						if( count( $data ) == 1 && $data[0] == '>>>END' )
							break;
						if( count( $data ) == count( $fieldNames ) )
						{
							if( (!$minRec     || $counter >= $minRec)
							&&  ( $maxRec < 0 || $counter < $maxRec ) )
							{
								foreach( $data as &$value ) {
									$value = stripslashes( $value );
								}
	
								if( $data[$idIndex] > $maxID )
									$maxID = $data[$idIndex];
	
								if( $database == "MYSQL" )
								{
									$select = "select * from " . $tableName;
									$rec = queryDatabase( $dbConnect, $select );
									for( $i=0; $i<mysql_num_fields( $rec ); $i++ )
									{
										if( mysql_field_type( $rec, $i ) == "date" )
										{
											$fieldName = mysql_field_name( $rec, $i );
											for( $j=0; $j<count( $fieldNames ); $j++ )
											{
												if( $fieldNames[$j] == $fieldName )
												{
													$dates = explode( ".", $data[$j] );
													$data[$j] = $dates[2] ."-" . $dates[1] ."-". $dates[0];
													break;
												}
											}
										}
									}
								}
								else if( $database == "PG" )
								{
									$select = "select * from " . $tableName;
									$rec = queryDatabase( $dbConnect, $select );
									for( $i=0; $i<pg_num_fields( $rec ); $i++ )
									{
										if( pg_field_type( $rec, $i ) == "date" || pg_field_type( $rec, $i ) == "int4")
										{
											$fieldName = pg_field_name( $rec, $i );
											for( $j=0; $j<count( $fieldNames ); $j++ )
											{
												if( $fieldNames[$j] == $fieldName )
												{
													if( $data[$j] == "" )
														$data[$j] = null;
													break;
												}
											}
										}
									}
								}
	
								$result = queryDatabase( $dbConnect, $insert, $data );
								if( !$result || is_object( $result) )
								{
									$error = $result;
									include "../includes/components/error.php";
									break;
								}
							}
							$counter++;
						}
					}
				}
				
				$numRecords = $counter;
				if( $maxRec >= 0 && $maxRec < $counter )
					$counter = $maxRec;

				echo "<br>$counter Datensätze gespeichert";
				if( $counter >= $numRecords )
					echo( " - Tabelle fertig importiert" );
				
				return $maxID;
			}
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			$tableCount = 0;
			$filename = array_key_exists( "backupFile", $_FILES ) ? $_FILES["backupFile"]["tmp_name"] : $_GET["filename"];
			$chunkSize = $_REQUEST["chunkSize"];
			$tableName = $_REQUEST["tableName"];
			$minRec = array_key_exists("minRec", $_GET) ? $_GET["minRec"] : 0;
			$maxRec = array_key_exists("maxRec", $_GET) ? $_GET["maxRec"] : -1;
			$numRecords = 0;

			if( is_uploaded_file( $filename ) && $chunkSize && $tableName )
			{
				move_uploaded_file( $filename, "./" . $tableName . ".csv" );
				$filename = $tableName . ".csv";
			}

			$file = fopen( $filename, "r" );
			if( !$file )
				echo "Kann $file nicht öffnen.";
			else
			{
				$dbConnect = openDatabase();

				if( !$tableName )
				{
					for( $i=count( $backupTables )-1; $i>=0; $i-- )
						queryDatabase( $dbConnect, "delete from " . $backupTables[$i] );

					while( !feof( $file ) )
					{
						$buffer = fgetcsv( $file, 100000, ';', '"' );
						if( count( $buffer ) == 1 )
						{
							$mark = substr( $buffer[0], 0, 3 );
							if( $mark == ">>>" )
							{
								$tableCount++;
								$tableName = substr( $buffer[0], 3 );
								$maxId = restoreTable( $tableName, $file, $numRecords );
								if( $tableName == "user_tab" )
								{
									if( $database != "MYSQL" )
										queryDatabase( $dbConnect, "drop sequence idSeq" );
									if( $database == "PG" )
										queryDatabase( $dbConnect, "create sequence idSeq start " . ($maxId+1) );
									else if( $database == "ORA" )
										queryDatabase( $dbConnect, "create sequence idSeq start with " . ($maxId+1) );
									
								}
							}
						}
					}

					echo( "<b>${tableCount} Tabelle(n) importiert</b>" );
					fclose( $file );
				}
				else
				{
					if( !isset( $minRec ) || $minRec == "" )
						$minRec = 0;
					if( !isset( $maxRec ) || $maxRec == "" )
						$maxRec = -1;

					if( $chunkSize && $maxRec < 0 )
						$maxRec = $minRec + $chunkSize;

					restoreTable( $tableName, $file, $numRecords, $minRec, $maxRec );
					fclose( $file );
					
					if( $chunkSize )
					{
						if( $numRecords > $maxRec )
						{
							$minRec = $maxRec;
							$maxRec = $minRec + $chunkSize;
							$nextURL = "restore2.php?tableName=" . $tableName . "&minRec=" . $minRec . "&maxRec=" . $maxRec . "&chunkSize=" . $chunkSize . "&filename=" . $filename;
							echo( "<br><a href='{$nextURL}'>&gt;&gt;&nbsp;Nächstes</a> (fals nicht automatisch)<br><a href='index.php'>&gt;&gt;&nbsp;Abbrechen</a>" );
							echo( "<script language='JavaScript'>document.location.replace( '{$nextURL}' );</script>" );
							
						}
						else if( !is_uploaded_file( $filename ) )
							unlink( $filename );
					}
				}
			}
		?>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
		
