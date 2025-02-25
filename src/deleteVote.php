<?php
	require_once( "includes/components/login.php" ); 
	include_once( "includes/tools/commontools.php" ); 

	$vote_id = $_GET["vote_id"];

	$queryResult = queryDatabase( 
		$dbConnect,
		"delete from vote_options ".
		"where vote_id = $1",
		array( $vote_id )
	);

	if( isset( $queryResult ) && !is_object($queryResult) )
	{
		$queryResult = queryDatabase( 
			$dbConnect,
			"delete from elections ".
			"where vote_id = $1",
			array( $vote_id )
		);
	}

	if( isset( $queryResult ) && !is_object($queryResult) )
	{
		$queryResult = queryDatabase( 
			$dbConnect,
			"delete from votes ".
			"where vote_id = $1",
			array( $vote_id )
		);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Abstimmung Löschen";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php
			include( "includes/components/headerlines.php" );

			if( isset( $queryResult ) && !is_object($queryResult) )
			{
		?>
			<p>Abstimmung gelöscht.</p>
		<?php
			}
			else
				include "../includes/components/error.php";
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
