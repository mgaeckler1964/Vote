<hr>
<p class="bottomMenu">
	<?php if( is_file( "index.php" ) && !isset($adminMenu) ) { ?>
		<a href="index.php">&gt;&gt; Adminmen&uuml;</a>
	<?php } ?>
		
	<?php if( is_file( "app.php" ) && !isset($appMenu) ) { ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<a href="app.php">&gt;&gt; <?php echo(APPLICATION_NAME) ?> konfigurieren</a>
	<?php } ?>
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