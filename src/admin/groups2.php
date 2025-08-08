<?php
	if( !headers_sent() )
		header('Content-Type: text/html; charset=ISO-8859-1');

	include_once( "includes/components/login.php" ); 
	include_once( "../includes/tools/commontools.php" ); 

	if( !isset( $page ) )
	{
		if( array_key_exists( "page", $_GET ) )
			$page = $_GET["page"];
		if( !isset($page) )
			$page=0;
	}

	if( !isset( $groupName ) )
	{
		if( array_key_exists( "groupName", $_GET ) )
			$groupName = $_GET["groupName"];
	}

	$hitsPerPage = isMobileClient() ? 3 : 20;

	if( isset( $groupName ) )
	{
		$queryResult = queryDatabase( 
			$dbConnect,
			"select * ".
			"from user_tab ".
			"where upper(email) like upper($1) ".
			"and is_group is not null ".
			"order by email",
			array( $groupName."%" )
		);
		if( isset( $queryResult ) && !is_object($queryResult) )
		{
			$i = 0;
			echo "<hr><table>\n";
			echo "<tr><th>Nr.</th><th>Name</th>";
			echo "<th>Funktion</th>";
			echo "</tr>\n";
			while( $group = fetchQueryRow( $queryResult ) )
			{
				if( $i >= $page*$hitsPerPage && $i<($page+1)*$hitsPerPage )
				{
					echo "<tr class=\"".($i%2?"even":"odd")."\"><td>".($i+1)."</td><td>";

					echo "<a href='groupedit.php?id={$group['id']}'>" . htmlspecialchars(urldecode($group['email']), ENT_QUOTES, 'ISO-8859-1') . "</a>";
					echo "</td>";
						
					echo "<td><a href='deleteUser.php?id={$group['id']}' onClick='if( confirm( \"Wirklich?\" ) ) return true; else return false;'>Löschen</a></td>";

					echo "</tr>\n";
				}
				$i++;
			}
			echo "</table>\n";
			echo "<p class='pager'>";
			if( $page )
				echo "<a href='javascript:prevPage();'>&lt;&lt;</a> ";
			echo "Seite " .($page+1). " von " . floor(($i-1)/$hitsPerPage+1) . ". - $i Gruppe(n) gefunden. ";
			if( ($page+1) <= floor(($i-1)/$hitsPerPage) )
				echo "<a href='javascript:nextPage();'>&gt;&gt;</a>";
			echo "</p>\n";
		}
	}
?>