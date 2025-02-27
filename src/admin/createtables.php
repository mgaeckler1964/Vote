//<?php require_once( "includes/components/login.php" ); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Tabellen Erstellen";
			include_once( "includes/components/defhead.php" );
		?>
	</head>
	<body>
		<?php include( "includes/components/headerlines.php" ); ?>
		
		<?php
			require_once("includes/tools/createUserTables.php" );

			$dbConnect = openDatabase();
			if( !$dbConnect )
				echo "<p>Kann keine Verbindung zur Datenbank herstellen.</p>";
			else
			{
				echo "<p>Verbindung zur Datenbank OK.</p>";
				$error = createUserTables( $dbConnect, $database );
				if( $error > "" )
					echo( $error );


				$query = "drop table votes";
					
				$result = queryDatabase( $dbConnect, $query );
				if( !$result )
				{
					echo "<p>Votes konnte nicht gelöscht werden.</p>\n";
				}
				$query = "create table votes ( ".
						"vote_id		int				not null 	primary key, ".
						"user_id		int				not null, ".
						"name			varchar(255)	not null, ".
						"question		varchar(255)	not null, ".
						"start_time		int				not null, ".
						"end_time		int				not null, ".
						"code			varchar(8), ".
						"mode			int ".
					")";
				
				$result = queryDatabase( $dbConnect, $query );
				if( !$result )
				{
					echo "<p>Votes konnte nicht erstellt werden.</p>\n";
				}

				$query = "drop table vote_options";
					
				$result = queryDatabase( $dbConnect, $query );
				if( !$result )
				{
					echo "<p>Options konnte nicht gelöscht werden.</p>\n";
				}
				$query = "create table vote_options ( ".
						"option_id		int				not null    primary key, ".
						"vote_id		int				not null, ".
						"text			varchar(255)	not null ".
					")";
				
				$result = queryDatabase( $dbConnect, $query );
				if( !$result )
				{
					echo "<p>Options konnte nicht erstellt werden.</p>\n";
				}

				$query = "drop table elections";
					
				$result = queryDatabase( $dbConnect, $query );
				if( !$result )
				{
					echo "<p>Elections konnte nicht gelöscht werden.</p>\n";
				}
				$query = "create table elections ( ".
						"elect_id		int				not null, ".
						"vote_id		int				not null, ".
						"option_id		int				not null, ".
						"name			varchar(255)	not null, ".
						"the_time		int				not null".
					")";
				
				$result = queryDatabase( $dbConnect, $query );
				if( !$result )
				{
					echo "<p>Elections konnte nicht erstellt werden.</p>\n";
				}
			}

		?>
		<p>Tabellenerstellung fertig.</p>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>