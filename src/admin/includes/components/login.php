<?php
	include "../includes/components/login.php";
	if( !$actUser['administrator'] )
	{
		echo( NO_PERM );
		exit;
	}
?>
