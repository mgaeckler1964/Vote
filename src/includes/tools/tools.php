<?php
	function getVote( $dbConnect, $id )
	{
		$vote = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select user_id, name, question, start_time, end_time, code, mode ".
			"from votes ".
			"where vote_id=$1",
			array( $id )
		);
		if( $queryResult && !is_object( $queryResult ) )
		{
			$vote = fetchQueryRow( $queryResult );
			$vote["question"] = urldecode($vote["question"]);
		}
		
		
		return $vote;
	}

	function getVoteOptions( $dbConnect, $id )
	{
		$voteOptions = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select vote_id, option_id, text ".
			"from vote_options ".
			"where vote_id=$1 ".
			"order by option_id",
			array( $id )
		);

		if( $queryResult && !is_object( $queryResult ))
		{
			while( $queryRecord = fetchQueryRow( $queryResult ) )
			{
				$queryRecord["text"] = urldecode($queryRecord["text"]);
				$voteOptions[] = $queryRecord;
			}
		}
		else
			$voteOptions = $queryResult;

		return $voteOptions;
	}

	function getVoteOption( $dbConnect, $id )
	{
		$queryResult = queryDatabase(
			$dbConnect,
			"select vote_id, option_id, text ".
			"from vote_options ".
			"where option_id=$1",
			array( $id )
		);

		if( $queryResult && !is_object( $queryResult ))
		{
			while( $queryRecord = fetchQueryRow( $queryResult ) )
				$voteOption = $queryRecord;

			$voteOption["text"] = urldecode($voteOption["text"]);
		}
		else
			$voteOption = $queryResult;

		return $voteOption;
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
			{
				$queryRecord["name"] = urldecode($queryRecord["name"]);
				$elections[] = $queryRecord;
			}
		}
		else
			$elections = $queryResult;

		return $elections;
	}
	function getElectionCount( $dbConnect, $id )
	{
		$counter = 0;
		$queryResult = queryDatabase(
			$dbConnect,
			"select count(*) as counter ".
			"from elections ".
			"where vote_id=$1",
			array( $id )
		);

		if( $queryResult && !is_object( $queryResult ))
		{
			while( $queryRecord = fetchQueryRow( $queryResult ) )
				$counter = $queryRecord['counter'];
		}
		else
			$counter = $queryResult;

		return $counter;
	}
?>
