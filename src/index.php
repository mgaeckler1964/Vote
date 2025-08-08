<?php include_once( "includes/components/login.php" ); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME;
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			echo("<h2>Aktive Abstimmungen</h2>");

			if( is_file( "templates/index.html" ) )
				include_once( "templates/index.html" );


			$queryResult = queryDatabase( 
				$dbConnect,
				"select * ".
				"from votes ".
				"where start_time < $1 ".
				"order by start_time",
				array( time() )
			);
			if( isset( $queryResult ) && !is_object($queryResult) )
			{
				$i = 0;
				echo "<hr><table>\n";
				echo "<tr><th>Nr.</th><th>Name</th>";
				echo "<th>Start</th>";
				echo "<th>Ende</th>";
				echo "<th>Aktion</th>";
				echo "</tr>\n";
		
				while( $vote = fetchQueryRow( $queryResult ) )
				{
					$code = $vote['code'];
					if( !$code )
					{
						$vote_id = $vote['vote_id'];
						$end_time = $vote['end_time'];

						if( $end_time >= time() )
							$nextPage = "dovote.php";
						else
							$nextPage = "result.php";
	
						echo "<tr class=\"".($i%2?"even":"odd")."\"><td>".($i+1)."</td><td>";
		
						echo "<a href='{$nextPage}?vote_id={$vote_id}'>". htmlspecialchars(urldecode($vote['name']), ENT_QUOTES, 'ISO-8859-1') ."</a>";
						echo "</td>";
						$start = formatTimeStamp($vote['start_time']);
						$end = formatTimeStamp($end_time);
						echo "<td>{$start}</td>";
						echo "<td>{$end}</td>";
						echo "<td><a href='result.php?vote_id={$vote_id}' >Ergebniss</a></td>";
			
						echo "</tr>\n";
	
						$i++;
					}
				}
				echo "</table>\n";
			}

			include( "includes/components/footerlines.php" );
		?>
	</body>
</html>
		
