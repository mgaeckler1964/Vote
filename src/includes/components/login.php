<?php
	define( 'USER_ID', 'userID' );
	define( 'PASSWORD', "password" );

	header('Content-Type: text/html; charset=ISO-8859-1');

	include_once( __DIR__ . "/../tools/database.php" );
	include_once( __DIR__ . "/../tools/commontools.php" );

	if( isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) )
	{
		$tmpArray =	explode(':', base64_decode(substr($_SERVER['REDIRECT_HTTP_AUTHORIZATION'], 6)));
		list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = $tmpArray;
	}

	if( array_key_exists( USER_ID, $_COOKIE ) )
		$userId = $_COOKIE[USER_ID];

	if( array_key_exists( "email", $_REQUEST ) )
		$email = $_REQUEST["email"];
	else if( array_key_exists( "PHP_AUTH_USER", $_SERVER ) )
		$email = $_SERVER['PHP_AUTH_USER'];

	if( array_key_exists( "guest", $_POST ) )
		$guest = $_POST["guest"];

	if( array_key_exists( PASSWORD, $_POST ) )
	{
		$password = $_POST[PASSWORD];
		$source = "post";
	}
	else if( array_key_exists( PASSWORD, $_GET ) )
	{
		$password = $_GET[PASSWORD];
		$source = "get";
	}
	else if( array_key_exists( PASSWORD, $_COOKIE ) )
	{
		$password = $_COOKIE[PASSWORD];
		$source = "cookie";
	}
	else if( array_key_exists( "PHP_AUTH_PW", $_SERVER ) )
	{
		$password = $_SERVER['PHP_AUTH_PW'];
		$source = "server";
	}

 	$userOK = true;
	$guestCount = 0;
	
	$dbConnect = openDatabase();
	if( $dbConnect )
	{
		$guestCount = getGuestCount( $dbConnect );
		if( isset( $userId ) && $userId )
		{
			$user = getUser( $dbConnect, $userId );
			if( !$user['id'] )
			{
				$userOK = false;
				$error = "Unbekannter Benutzer#1";
			}
			else if( $user['password'] )
			{
				if( $user['password'] != mgMd5Hash($password) )
				{
					$userOK = false;
					$error = "Falsches Kennwort#1";
//					$error = "Falsches Kennwort#1 " . $user['password'] . " " . mgMd5Hash($password) . " " . $password . " " . $source;
				}
			}

			if( $userOK )
			{
				$actUser = $user;
				if( isset($password) )
					setcookie( PASSWORD, $password, 0, "/" );
			}
		}
		else if( isset( $email ) && $email )
		{
			$user = getUser( $dbConnect, 0, $email );
			if( !$user || !$user['id'] )
			{
				$userOK = false;
				$error = "Unbekannter Benutzer#2" . $email;
			}
			else if( $user['password'] )
			{
/*
				queryDatabase( $dbConnect,
					"update user_tab " .
					"set password = null " .
					"where id = $1",
					array( $user['id'] )
				);
*/
				if( $user['password'] != mgMd5Hash($password) )
				{
					$userOK = false;
					$error = "Falsches Kennwort#2";
				}
			}
			if( $userOK )
			{
				if( !$user['administrator'] && !$user['loginenabled'] )
				{
					$userOK = false;
					$error = "Anmeldung nicht erlaubt";
				}
				else
				{
					setcookie( USER_ID, $user['id'], 0, "/" );
					setcookie( PASSWORD, $password, 0, "/" );
					$actUser = $user;
					queryDatabase(
						$dbConnect,
						"insert into user_login_prot ( userid, logindate, remoteip ) values( $1, $2, $3 )", 
						array( $actUser['id'], time(), $_SERVER['REMOTE_ADDR'] )
					);
				}
			}
		}
		else if( isset( $guest ) && $guest )
		{
			$user = getGuest( $dbConnect );
			if( !$user['id'] )
			{
				$userOK = false;
				$error = "Unbekannter Benutzer#3" . $email;
			}
			else
			{
				setcookie( USER_ID, $user['id'], 0, "/" );
				if( isset($password) )
					setcookie( PASSWORD, $password, 0, "/" );
				$actUser = $user;
				queryDatabase(
					$dbConnect,
					"insert into user_login_prot ( userid, logindate, remoteip ) values( $1, $2, $3 )", 
					array( $actUser['id'], time(), $_SERVER['REMOTE_ADDR'] )
				);
			}
		}
		else
		{
			$userCount = getUserCount( $dbConnect );
			if( $userCount )
			{
				$userOK = false;
				$error = "Keine Anmeldeinformationen";
			}
			else
			{
				$actUser = array( "id" => 1, 'administrator' => 'X' );
			}
		
		}
	}
	else
		$actUser = array( "id" => 1, 'administrator' => 'X' );
	

	if( isset($ignoreCurrent) || (!$userOK && !isset( $tryLogin)) )
	{
		setcookie( PASSWORD, "", 0, "/" );
		setcookie( USER_ID, "", 0, "/" );
		?>
	
			<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">
	
			<html>
				<head>
					<?php
						$title = APPLICATION_NAME . " Anmeldung";
						include_once( "includes/components/defhead.php" );
					?>
					<?php if( $guestCount ) { ?>
						<script language="JavaScript">
							function loginGuest()
							{
								document.loginForm.guest.value = "X";
								document.loginForm.submit();
							}
						</script>
					<?php }?>
					<?php if( defined('SELF_REGISTER') && SELF_REGISTER!=0 ) { ?>
						<script language="JavaScript">
							function selfRegister()
							{
								window.open("admin/useredit.php?register=1");
							}
						</script>
					<?php }?>

				</head>
				<body>
					<?php
						include( "includes/components/headerlines.php" );

						if( is_file( "templates/login.html" ) )
							include_once( "templates/login.html" );

 					?>
					<form name="loginForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
						<?php
							if( $guestCount )
								echo( "<input type='hidden' value='' name='guest'>\n" );
						?>
						<input type="hidden" name="fromLogin" value="x">
						<p>
							<?php if( isset($error ) ) echo $error; ?><br>
							Bitte melden Sie sich an.
						</p>
						<table>
							<tr><td class="fieldLabel">Benutzername (E-Mail)</td><td><input type="email" required="required" autofocus="autofocus" name="email"></td></tr>
							<tr><td class="fieldLabel">Kennwort</td><td><input type="password" name="<?php echo PASSWORD; ?>"></td></tr>
							<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
							<tr>
								<td class="fieldLabel">&nbsp;</td>
								<td>
									<input type="submit" value="Anmelden">
									<?php
										if( $guestCount )
											echo( "<input type='button' Value='Gastzugang' onClick='loginGuest();'>\n" );
										if( defined('SELF_REGISTER') && SELF_REGISTER!=0 )
											echo( "<input type='button' Value='Registieren...' onClick='selfRegister();'>\n" );
									?>
								</td>
							</tr>
						</table>
					</form>
					<p>
						<a href="forgotPassword.php">Kennwort vergessen?</a>
					</p>
					<?php include( "includes/components/footerlines.php" ); ?>
				</body>
			</html>
		<?php 
		exit;
	}
	else
	{
		unset( $user );
		unset( $userId );
		unset( $userOK );
		unset( $email );
		unset( $password );
		unset( $error );
	}
?>
