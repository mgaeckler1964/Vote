<?php
	function createUserTables( $dbConnect, $database )
	{
		$error = "";
		$query = "create table user_tab ( ".
			"id				int				not null	primary key, ".
			"nachname		varchar(128)	not null, ".
			"vorname		varchar(128)	not null, ".
			"strasse		varchar(255), ".
			"postfach		varchar(255), ".
			"land			varchar(16), ".
			"plz			varchar(16), ".
			"ort			varchar(64), ".
			"email			varchar(128)	not null, ".
			"password		varchar(32), ".
			"guest			varchar(1), ".
			"loginenabled	varchar(1), ".
			"administrator	varchar(1), ".
			"is_group		varchar(1), ".
			"remoteip		varchar(32)".
		")";
					
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>user_tab konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		if( $database == "PG" )
			$result = queryDatabase( $dbConnect, "create sequence idSeq start 2" );
		else if( $database == "ORA" )
			$result = queryDatabase( $dbConnect, "create sequence idSeq start with 2" );
		else if( $database == "MYSQL" || $database == "MYSQLi" )
			$result = true;
		else
			$result = false;
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>idSeq konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}
		
		$query = "insert into user_tab (id, nachname, vorname, email, administrator, loginenabled ) values ( 1, 'root', 'root', 'root@gaeckler.at', 'X', 'X' )";
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>root konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		$query = "create unique index userMailIdx on user_tab (email)";
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>userMailIdx konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		$query = "create table user_login_prot ( ".
			"userid			int				not null, ".
			"logindate		int				not null, ".
			"remoteip		varchar(32)		not null ".
		")";
					
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>user_login_prot konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		$query = "create table group_member ( ".
			"groupId		int				not null, ".
			"member			int				not null ".
		")";
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>group_member konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		$query = "create unique index groupMemberIdx on group_member ( groupId, member )";
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>groupMemberIdx konnte nicht erstellt werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		// Updates for existing database:
		$query = "alter table user_tab add column remoteip varchar(32)";
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>user_tab konnte nicht aktualisiert werden.</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		$query = "alter table user_tab add column cr_time int";
		$result = queryDatabase( $dbConnect, $query );
		if( !$result || is_object( $result ) )
		{
			$error .= "<p>user_tab konnte nicht aktualisiert werden (2).</p>\n";
			if( is_object( $result ) )
				$error .= "<p>". $result->errorText . "<br>" . $result->errorDetail . "</p>";
		}

		return $error;
	}
?>
