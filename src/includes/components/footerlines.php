<hr>
<p class="footerLines">
	<?php
		startSession();
		
		echo( APPLICATION_NAME . " " . APPLICATION_COPYRIGHT . " " );
		if( isset($_SESSION) && array_key_exists('time', $_SESSION) )
			echo date('Y m d H:i:s', $_SESSION['time']) . " ";
		if( array_key_exists( "HTTP_USER_AGENT", $_SERVER ) )
		{
			print_r( $_SERVER["HTTP_USER_AGENT"] );
		}
		if( isset($actUser) )
		{
			echo(" " . $actUser["email"]);
		}
	?>
</p>