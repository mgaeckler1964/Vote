<?php
	$isMobile = 0;

	function isMobileClient()
	{
		global $isMobile;
		
		if( $isMobile === 0 )
		{
			$isMobile = false;
			if( isset( $_SERVER ) && array_key_exists( "HTTP_USER_AGENT", $_SERVER ) )
			{
				$agent = $_SERVER["HTTP_USER_AGENT"];
				$isMobile = (stripos( $agent, "Mobil" ) !== false) || (stripos( $agent, "Android" ) !== false);
			}
		}

		return $isMobile;
	}

	// ----------------------------------------------------------------------------------------------------------------------
	// DB schema
	// ----------------------------------------------------------------------------------------------------------------------

	function hasTable( $dbConnect, $tableName )
	{
		$queryResult = queryDatabase(
			$dbConnect,
			"select count(*) ".
			"from information_schema.tables ".
			"where upper(table_name) = upper($1)",
			array( $tableName )
		);
		if( $queryResult && !is_object( $queryResult ) )
		{
			$rec = fetchQueryRow( $queryResult );
		}
		return current($rec) > 0;
	}
	
	function hasUserTable($dbConnect)
	{
		return hasTable( $dbConnect, "user_tab" );
	}

	function createUserTableWarning($dbConnect)
	{
		if( !hasUserTable($dbConnect) ) { ?>
			<div style="background-color:#FF0000; color:#FFFF00; font-size:18pt; text-align:center;">Benutzertabelle nicht vorhanden. Erzeugen Sie erst alle Tabellen.</div>
		<?php }
	}
	
	// ----------------------------------------------------------------------------------------------------------------------
	// session
	// ----------------------------------------------------------------------------------------------------------------------

	function startSession()
	{
		if( !isset($_SESSION ) )
		{
			//session_cache_limiter('private');
			session_cache_limiter('');
			session_start();
		}
		if( !array_key_exists( "time", $_SESSION ) )
			$_SESSION['time'] = time();
	}

	function readRequestSetting( $requestName, $sessionName, $request, $default )
	{
		if( array_key_exists( $requestName, $request ) )
		{
			$result = $request[$requestName];
			$_SESSION[$sessionName] = $result;
			
		}
		else if( array_key_exists( $sessionName, $_SESSION ) )
			$result = $_SESSION[$sessionName];
		else
			$result = $default;
		
		return $result;
	}
	
	// ----------------------------------------------------------------------------------------------------------------------
	// date/time
	// ----------------------------------------------------------------------------------------------------------------------

	date_default_timezone_set("Europe/Vienna");

	function createDate( $dateString )
	{
		$date = array();
		$dates = explode( '.', $dateString );
		if( count( $dates ) >= 3 )
			$date['day'] = trim($dates[count( $dates )-3]);
		else
			$date['day'] = 0;
		if( count( $dates ) >= 2 )
			$date['month'] = trim($dates[count( $dates )-2]);
		else
			$date['month'] = 0;

		if( count( $dates ) >= 1 )
			$date['year'] = trim($dates[count( $dates )-1]);
		else
			$date['year'] = 0;
		
		return $date;
	}
	
	function compareDate( $date1, $date2 )
	{
		if( $date1['year'] < $date2['year'] )
			return -1;
		else if( $date1['year'] > $date2['year'] )
			return 1;
		else if( $date1['month'] < $date2['month'] )
			return -1;
		else if( $date1['month'] > $date2['month'] )
			return 1;
		else if( $date1['day'] < $date2['day'] )
			return -1;
		else if( $date1['day'] > $date2['day'] )
			return 1;
			
		return 0;
	}
	function compareDateStrings( $date1, $date2 )
	{
		return compareDate( createDate( $date1 ), createDate( $date2 ) );
	}
	function formatTimeStamp( $timestamp )
	{
		return date( "d. m. Y H:i:s", $timestamp );
	}
	function formatHtmlTimeStamp( $timestamp )
	{
		return date( "Y-m-d", $timestamp )."T".date( "H:i", $timestamp );
	}
	
	// ----------------------------------------------------------------------------------------------------------------------
	// formControls
	// ----------------------------------------------------------------------------------------------------------------------

	function createField( $fName, $fType, $fValue, $readOnly, $optional, $autofocus=false, $size=-1, $maxLen=-1 )
	{
		if( $autofocus )
			$autofocus = "autofocus";
		else
			$autofocus = null;
		
		if( !is_numeric($size) )
			$size = -1;
		if( !is_numeric($maxLen) )
			$maxLen = -1;

		if( $fType == "number" )
		{
			if( !is_numeric($fValue) )
				$fValue = null;
			if( $size < 0 )
				$size = 16;
			if( $maxLen < 0 )
				$maxLen = 16;
		}
		else
			$fValue = htmlspecialchars($fValue, ENT_QUOTES, 'ISO-8859-1');
		$required = $optional ? "" : "required";
		if( $readOnly )
			echo $fValue;
		else
		{
			if( $size >= 0 )
				$size = "size=".$size;
			else
				$size = "";
			
			if( $maxLen >= 0 )
				$maxLen = "maxlength=".$maxLen;
			else
				$maxLen = "";

			echo "<input type='{$fType}' name='{$fName}' value='{$fValue}' {$required} ${size} ${maxLen}  ${autofocus}>";
		}
	}
	
	function createMemo( $fName, $fValue, $readOnly, $optional, $maxLen=-1, $cols=-1, $rows=-1 )
	{
		if( !is_numeric($rows) )
			$rows = -1;
		if( !is_numeric($cols) )
			$cols = -1;
		if( !is_numeric($maxLen) )
			$maxLen = -1;

		$fValue = htmlspecialchars($fValue, ENT_QUOTES, 'ISO-8859-1');
		$required = $optional ? "" : "required";
		if( $readOnly )
			echo $fValue;
		else
		{
			if( $rows >= 0 )
				$rows = "rows=".$rows;
			else
				$rows = "";
			
			if( $cols >= 0 )
				$cols = "cols=".$cols;
			else
				$cols = "";
			
			if( $maxLen >= 0 )
				$maxLen = "maxlength=".$maxLen;
			else
				$maxLen = "";

			echo "<textarea name='{$fName}' {$required} ${rows} ${cols} ${maxLen}>{$fValue}</textarea>";
		}
	}

	// ----------------------------------------------------------------------------------------------------------------------
	// password
	// ----------------------------------------------------------------------------------------------------------------------

	function mgMd5Hash( $password )
	{
		if( function_exists( "hash" ) )
			$password = hash( "md5", $password );
		else if( function_exists( "mhash" ) )
			$password = bin2hex( mhash( MHASH_MD5, $password ) );

		return $password;
	}
	
	function createPasswordStyle() { ?>
		<style>
			#toggleBtn {
				position: relative;
				top: 15px;
				transform: translateY(-50%);
				background: none;
				border: none;
				cursor: pointer;
				color: #666;
			}
			#toggleBtn:hover { color: #000; }
		</style>
	<?php }

	function createPasswordScript($pw1,$pw2,$toogleBtn,$eyeIcon) { ?>
		<script>
			const pw1 = document.getElementById('<?php echo $pw1;?>');
			const pw2 = document.getElementById('<?php echo $pw2;?>');
			const toggleBtn = document.getElementById('<?php echo $toogleBtn;?>');
			const eyeIcon = document.getElementById('<?php echo $eyeIcon;?>');
			
			toggleBtn.addEventListener('click', () => {
				const isPassword = pw1.type === 'password';
		
				// Typ umschalten
				pw1.type = isPassword ? 'text' : 'password';
				pw2.type = pw1.type;
				  
				// Icon anpassen (Beispiel: Auge offen / Auge mit Querstrich)
				if (isPassword) {
					// SVG für "Auge offen" (Standard)
					eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
				} else {
					// Zurück zum normalen Auge
					eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
				}
			});
		</script>
	<?php }

	function createPasswordInput($id,$name,$toggleBtn=null,$eyeIcon=null) { ?>
		<input type="password" id="<?php echo $id;?>" name="<?php echo $name;?>">
		<?php if( $toggleBtn && $eyeIcon ) { ?>
			<button type="button" id="<?php echo $toggleBtn;?>" aria-label="Passwort anzeigen">
				<svg id="<?php echo $eyeIcon;?>" xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
					viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
					<circle cx="12" cy="12" r="3"></circle>
				</svg>
			</button>
		<?php }
	}
	
	// ----------------------------------------------------------------------------------------------------------------------
	// user
	// ----------------------------------------------------------------------------------------------------------------------

	function fullname2Html( $full_name )
	{
		$full_name = str_replace( " ", "&nbsp;", $full_name );
		
		return $full_name;
	}
	
	function fetchUser( $queryResult )
	{
		global $dbConnect;

		$user = fetchQueryRow( $queryResult );
		if( $user )
		{
			$user['nachname'] = urldecode($user['nachname']);
			$user['vorname'] = urldecode($user['vorname']);
			$user['strasse'] = urldecode($user['strasse']);
			$user['postfach'] = urldecode($user['postfach']);
			$user['land'] = urldecode($user['land']);
			$user['plz'] = urldecode($user['plz']);
			$user['ort'] = urldecode($user['ort']);
			$user['email'] = urldecode($user['email']);
			
			if( $user['nachname'] )
				$user['fullname']=$user['nachname'];
			else
				$user['fullname']=$user['email'];

			if( $user['vorname'] )
				$user['fullname'] .= ", " . $user['vorname'];

			$queryResult = queryDatabase(
				$dbConnect,
				"select max( logindate) as lastlogin from user_login_prot where userid=$1",
				array( $user['id'] )
			);
			if( $queryResult && !is_object( $queryResult ) )
			{
				$queryResult = fetchQueryRow( $queryResult );
				if( $queryResult && $queryResult['lastlogin'] )
					$user['lastlogin'] = formatTimeStamp($queryResult['lastlogin']);
				else
					$user['lastlogin'] = "";
			}
			else
				var_dump( $queryResult );
		}
		
		return $user;
	}
	function getUser2( $dbConnect, $id, $email=null )
	{
		if( !$email )
			$email = "__dummyMailNeverUsed__";

		if( !$id )
			$id=0;
		$user = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select nachname, vorname, strasse, postfach, land, plz, ort, email, ".
				"password, id, administrator, guest, loginenabled ".
			"from user_tab ".
			"where id=$1 or email=$2",
			array( $id, $email )
		);
		if( $queryResult && !is_object( $queryResult ) )
			$user = fetchUser( $queryResult );
		
		
		return $user;
	}
	function getUser( $dbConnect, $id, $email=null )
	{
		$user = getUser2( $dbConnect, $id, $email );
		if( !is_array($user) || !array_key_exists( "loginenabled", $user ) ) {
			$user = getUser2( $dbConnect, $id, urlencode($email) );
		}
		
		return $user;
	}
	function getGuest( $dbConnect )
	{
		$user = array();
		$queryResult = queryDatabase(
			$dbConnect,
			"select nachname, vorname, strasse, postfach, land, plz, ort, email, ".
				"password, id, administrator, guest, loginenabled ".
			"from user_tab ".
			"where guest='X' and loginenabled='X'"
		);
		if( $queryResult && !is_object( $queryResult ) )
			$user = fetchUser( $queryResult );
		
		
		return $user;
	}
	function getUserCount( $dbConnect )
	{
		$userCount = 0;
		$queryResult = queryDatabase( $dbConnect, "select count(*) as usercount from user_tab " );
		if( $queryResult && !is_object( $queryResult ) )
		{
			$queryResult = fetchQueryRow( $queryResult );
			if( $queryResult )
				$userCount = $queryResult['usercount'];
		}

		return $userCount;
	}
	function checktUser4register( $dbConnect, $email, $remoteip )
	{
		$userCount = 0;
		$queryResult = queryDatabase( $dbConnect, "select count(*) as usercount from user_tab where email=$1", array(urlencode($email)) );
		if( $queryResult && !is_object( $queryResult ) )
		{
			$queryResult = fetchQueryRow( $queryResult );
			if( $queryResult )
				$userCount = $queryResult['usercount'];
		}
		if( $userCount==0 )
		{
			$queryResult = queryDatabase( $dbConnect, "select count(*) as usercount from user_tab where remoteip=$1 and cr_time > $2", array($remoteip, time()-86400 ) );
			if( $queryResult && !is_object( $queryResult ) )
			{
				$queryResult = fetchQueryRow( $queryResult );
				if( $queryResult )
				{
					$userCount = $queryResult['usercount'];
					if( $userCount < 5 )		// up to 5 users per day and remote ip is ok
						$userCount = 0;
				}
			}
		}

		return $userCount;
	}
	
	function getGuestCount( $dbConnect )
	{
		$userCount = 0;
		$queryResult = queryDatabase( $dbConnect, "select count(*) as guestcount from user_tab where loginenabled = 'X' and guest = 'X'" );
		if( $queryResult && !is_object( $queryResult ) )
		{
			$queryResult = fetchQueryRow( $queryResult );
			if( $queryResult )
				$userCount = $queryResult['guestcount'];
		}

		return $userCount;
	}
	
	function loginUser( $dbConnect, $email, $password, $lastUserID = 0 )
	{
		$user = getUser( $dbConnect, 0, $email );
		if( !$user['id'] )
		{
			$user = "Unbekannter Benutzer#2" . $email;
		}
		else if( $user['password'] && $user['password'] != mgMd5Hash($password) )
		{
			$user = "Falsches Kennwort#2";
		}
		else if( !$user['administrator'] && !$user['loginenabled'] )
		{
			$user = "Anmeldung nicht erlaubt";
		}
		if( $lastUserID && is_array( $user ) && count( $user ) && $lastUserID != $user['id'] )
			queryDatabase(
				$dbConnect,
				"insert into user_login_prot ( userid, logindate, remoteip ) values( $1, $2, $3 )", 
				array( $user['id'], time(), $_SERVER['REMOTE_ADDR'] )
			);
		
		return $user;
	}
	function getDbUserTable()
	{
		global $dbConnect;

		$queryResult = queryDatabase( $dbConnect, "select * from user_tab where is_group is null" );
		if( $queryResult && !is_object( $queryResult ))
		{
			$userTab = array();
			while( $queryRecord = fetchQueryRow( $queryResult ) )
				$userTab[] = $queryRecord;
		}
		else
			$userTab = $queryResult;

		return $userTab;
	}
	function getAllUserIDs()
	{
		global $dbConnect;

		$queryResult = queryDatabase( $dbConnect, "select id, email, is_group from user_tab order by email" );
		if( $queryResult && !is_object( $queryResult ))
		{
			$userTab = array();
			while( $queryRecord = fetchQueryRow( $queryResult ) ) {
				$queryRecord["email"] = urldecode($queryRecord["email"]);
				$userTab[] = $queryRecord;
			}
		}
		else
			$userTab = $queryResult;

		return $userTab;
	}
	function deleteUser( $theUserID )
	{
		global $dbConnect, $actUser;

		$error = false;
		if( !$actUser['administrator'] )
			$error = new errorClass( "Permission denied" );
		else if( $theUserID == 1 )
			$error = new errorClass( "Permission denied", "Cannot delete root" );
		else if( $theUserID == $actUser['id'] )
			$error = new errorClass( "Permission denied", "Cannot delete yourself" );
		else
		{
			$queryResult = queryDatabase( $dbConnect, "delete from group_member where groupId = $1 or member = $2", array( $theUserID, $theUserID ) );
			if( !is_object($queryResult)  )
				$queryResult = queryDatabase( $dbConnect, "delete from user_login_prot where userid = $1", array( $theUserID ) );
			if( !is_object($queryResult)  )
				$queryResult = queryDatabase( $dbConnect, "delete from user_tab where id = $1", array( $theUserID ) );
			
			if( is_object($queryResult)  )
				$error = $queryResult;
		}

		return $error;
	} 

	// ----------------------------------------------------------------------------------------------------------------------
	// groups
	// ----------------------------------------------------------------------------------------------------------------------

	function getGroupMembers( $id )
	{
		global $dbConnect;

		$queryResult = queryDatabase( $dbConnect, "select u.id, u.email from user_tab u, group_member g where g.member = u.id and g.groupId = $1", array( $id ) );
		if( $queryResult && !is_object( $queryResult ))
		{
			$groupMembers = array();
			while( $queryRecord = fetchQueryRow( $queryResult ) ) {
				$queryRecord["email"] = urldecode($queryRecord["email"]);
				$groupMembers[] = $queryRecord;
			}
		}
		else
			$groupMembers = $queryResult;

		return $groupMembers;
	}
	
	function addUser2Group( $groupId, $member )
	{
		global $dbConnect;

		$error = false;
		$queryResult = queryDatabase( $dbConnect, "insert into group_member( groupId, member ) values ( $1, $2 )", array( $groupId, $member ) );
		if( is_object( $queryResult ) )
			$error = $queryResult;
		
		return $error;
	}
	function deleteUserFromGroup( $groupId, $member )
	{
		global $dbConnect;

		$error = false;
		$queryResult = queryDatabase( $dbConnect, "delete from group_member where groupId =$1 and member = $2", array( $groupId, $member ) );
		if( is_object( $queryResult ) )
			$error = $queryResult;
		
		return $error;
	}
	function getDirectMemberships( $theId )
	{
		global $dbConnect;

		$queryResult = queryDatabase( $dbConnect, "select groupId from group_member where member = $1", array( $theId ) );
		if( $queryResult && !is_object( $queryResult ))
		{
			$groups = array();
			while( $queryRecord = fetchQueryRow( $queryResult ) )
				$groups[] = $queryRecord['groupId'];
		}
		else
			$groups = $queryResult;

		return $groups;
	}
	function getAllMemberships( $theID )
	{
		$memberShips = getDirectMemberShips( $theID );
		forEach( $memberShips as $groupID )
		{
			$newMemberShips = getDirectMemberShips( $groupID );
			forEach( $newMemberShips as $newGroup )
			{
				if( array_search( $newGroup, $memberShips ) === FALSE )
					$memberShips[] = $newGroup;
			}
		}
		
		return $memberShips;
	}
?>
