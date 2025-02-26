<hr>
<p class="bottomMenu">
	<a href="index.php">&gt;&gt; Adminmenü</a>
</p>
<p class="footerLines">
	<?php
		echo( APPLICATION_NAME . " " . APPLICATION_COPYRIGHT . " " );
		if( array_key_exists( "HTTP_USER_AGENT", $_SERVER ) )
		{
			print_r( $_SERVER["HTTP_USER_AGENT"] );
		}
		if( isset($actUser) )
		{
			echo(" ");
			print_r($actUser);
		}
	?>
</p>