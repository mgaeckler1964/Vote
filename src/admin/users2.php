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

	if( !isset( $userName ) )
	{
		if( array_key_exists( "userName", $_GET ) )
			$userName = $_GET["userName"];
	}

	$hitsPerPage = isMobileClient() ? 3 : 20;

	if( isset( $userName ) )
	{
		$queryResult = queryDatabase( 
			$dbConnect,
			"select * ".
			"from user_tab ".
			"where upper(nachname) like upper($1) ".
			"and is_group is null ".
			"order by nachname, vorname",
			array( urlencode($userName)."%" )
		);
		if( isset( $queryResult ) && !is_object($queryResult) )
		{
			$i = 0;
			echo "<hr><table>\n";
			echo "<tr><th>Nr.</th><th>Name</th>";
			echo "<th>E-Mail</th>";
			echo "<th>Letzter Login</th>";
			echo "<th>Funktion</th>";
			echo "</tr>\n";
			while( $user = fetchUser( $queryResult ) )
			{
				if( $i >= $page*$hitsPerPage && $i<($page+1)*$hitsPerPage )
				{
					echo "<tr class=\"".($i%2?"even":"odd")."\"><td>".($i+1)."</td><td>";

					echo "<a href='useredit.php?id={$user['id']}'>". htmlspecialchars($user['fullname'], ENT_QUOTES, 'ISO-8859-1') ."</a>";
					echo "</td>";
					echo "<td>". htmlspecialchars($user['email'], ENT_QUOTES, 'ISO-8859-1') ."</td>";
					echo "<td>{$user['lastlogin']}</td>";
						
					if( $user['id'] != 1 )
						echo "<td><a href='deleteUser.php?id={$user['id']}' onClick='if( confirm( \"Wirklich?\" ) ) return true; else return false;'>Löschen</a></td>";

					echo "</tr>\n";
				}
				$i++;
			}
			echo "</table>\n";
			echo "<p class='pager'>";
			if( $page )
				echo "<a href='javascript:prevPage();'>&lt;&lt;</a> ";
			echo "Seite " .($page+1). " von " . floor(($i-1)/$hitsPerPage+1) . ". - $i Benutzer gefunden. ";
			if( ($page+1) <= floor(($i-1)/$hitsPerPage) )
				echo "<a href='javascript:nextPage();'>&gt;&gt;</a>";
			echo "</p>\n";
		}
	}
?>