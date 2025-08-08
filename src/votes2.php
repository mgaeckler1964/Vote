<?php
	if( !headers_sent() )
		header('Content-Type: text/html; charset=ISO-8859-1');

	include_once( "includes/components/login.php" ); 
	include_once( "includes/tools/commontools.php" ); 

	if( !isset( $page ) )
	{
		if( array_key_exists( "page", $_GET ) )
			$page = $_GET["page"];
		if( !isset($page) )
			$page=0;
	}

	if( !isset( $voteName ) )
	{
		if( array_key_exists( "voteName", $_GET ) )
			$voteName = $_GET["voteName"];
	}

	$hitsPerPage = isMobileClient() ? 3 : 20;

	if( isset( $voteName ) )
	{
		$queryResult = queryDatabase( 
			$dbConnect,
			"select * ".
			"from votes ".
			"where upper(name) like upper($1) ".
			"order by name",
			array( urlencode($voteName)."%" )
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
				if( $i >= $page*$hitsPerPage && $i<($page+1)*$hitsPerPage )
				{
					echo "<tr class=\"".($i%2?"even":"odd")."\"><td>".($i+1)."</td><td>";

					echo "<a href='voteEdit.php?vote_id={$vote['vote_id']}'>". htmlspecialchars(urldecode($vote['name']), ENT_QUOTES, 'ISO-8859-1') ."</a>";
					echo "</td>";
					$start = formatTimeStamp($vote['start_time']);
					$end = formatTimeStamp($vote['end_time']);
					echo "<td>{$start}</td>";
					echo "<td>{$end}</td>";
					$canWrite = true;
					if( !$actUser['administrator'] && $vote["user_id"] != $actUser['id'] )
					{
						$canWrite = false;
					}
								
					if( $canWrite )
					{
						echo "<td><a href='deleteVote.php?vote_id={$vote['vote_id']}' onClick='if( confirm( \"Wirklich?\" ) ) return true; else return false;'>Löschen</a></td>";
					}

					echo "</tr>\n";
				}
				$i++;
			}
			echo "</table>\n";
			echo "<p class='pager'>";
			if( $page )
				echo "<a href='javascript:prevPage();'>&lt;&lt;</a> ";
			echo "Seite " .($page+1). " von " . floor(($i-1)/$hitsPerPage+1) . ". - $i Abstimmung(en) gefunden. ";
			if( ($page+1) <= floor(($i-1)/$hitsPerPage) )
				echo "<a href='javascript:nextPage();'>&gt;&gt;</a>";
			echo "</p>\n";
		}
	}
?>