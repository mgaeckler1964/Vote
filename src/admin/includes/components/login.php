<?php
	include "../includes/components/login.php";
	if( !$actUser['administrator'] )
	{
		echo( "Keine Berechtigung" );
		exit;
	}
?>
