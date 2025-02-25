<?php
	function getVote( $dbConnect, $id )
	{
		$vote = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select user_id, name, question, start_time, end_time ".
			"from votes ".
			"where vote_id=$1",
			array( $id )
		);
		if( $queryResult && !is_object( $queryResult ) )
			$vote = fetchQueryRow( $queryResult );
		
		
		return $vote;
	}

	function getVoteOptions( $dbConnect, $id )
	{
		$voteOptions = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select vote_id, option_id, text ".
			"from vote_options ".
			"where vote_id=$1",
			array( $id )
		);

		if( $queryResult && !is_object( $queryResult ))
		{
			while( $queryRecord = fetchQueryRow( $queryResult ) )
				$voteOptions[] = $queryRecord;
		}
		else
			$voteOptions = $queryResult;

		return $voteOptions;
	}

	function getElections( $dbConnect, $id )
	{
		$elections = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select elect_id, vote_id, option_id, name, the_time ".
			"from elections ".
			"where vote_id=$1",
			array( $id )
		);

		if( $queryResult && !is_object( $queryResult ))
		{
			while( $queryRecord = fetchQueryRow( $queryResult ) )
				$elections[] = $queryRecord;
		}
		else
			$elections = $queryResult;

		return $elections;
	}
?>
