<img src="support/logo.gif" align="right" height="128">
<?php
	if( $title )
		echo "<h1>$title</h1>";
	else
		echo "<h1>" . APPLICATION_NAME  . "</h1>";
?>
<hr style="clear:right;">
<div class="Menu"><div class="MenuLeftEntry">
	<a href="index.php">Home</a>
</div><div class="MenuLeftEntry">
	<a href="votes.php">Votes</a>
</div><div class="MenuLeftEntry">
	<a href="new.php">Neu</a>
</div><div class="MenuLeftEntry">
	<a href="impressum.php">Impressum</a>
</div>
<?php if( isset( $actUser ) ) { ?>
	<div class="MenuRightEntry">
		<a href="logout.php">Logout</a>
	</div>
<?php } ?>
<?php if( isset( $actUser ) && !$actUser['guest'] ) { ?>
	<div class="MenuRightEntry">
		<a href="password.php">Kennwort ändern</a>
	</div>
<?php } ?>
<?php if( isset( $actUser ) && $actUser['administrator'] ) { ?>
	<div class="MenuRightEntry">
		<a href="admin/index.php">Administration</a>
	</div>
<?php } ?>
</div>
<hr style="clear:right;">
