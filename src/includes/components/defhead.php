<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="support/styles.css">
<?php
	include_once( "includes/tools/commontools.php" );

	if( $title )
		echo "<title>$title</title>\n";
	else
		echo "<title>" . APPLICATION_NAME . "</title>\n";
		
	if( isMobileClient() )
		echo "<link rel='stylesheet' type='text/css' href='support/mobile.css'>\n";
?>
