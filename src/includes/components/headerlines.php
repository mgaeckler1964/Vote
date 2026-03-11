<?php 
	include("commonheaderlines.php"); 

	$menuLeft = array (
		array( "href" => "index.php", "label" => "Start" ),
		array( "href" => "votes.php", "label" => "Abstimmungen" ),
		array( "href" => "new.php", "label" => "Neu" ),
		array( "href" => "impressum.php", "label" => "Impressum" )
	);
	$menu = array( "left" => $menuLeft );
	include("commonmenu.php" );
?>
