<?php
	require_once( "includes/components/login.php" ); 

	if( array_key_exists( "id", $_GET ) )
	{
		$id = $_GET["id"];
		$group = getUser( $dbConnect, $id );
		$email = $group['email'];
		$groupMembers = getGroupMembers( $id );
		$userIds = getAllUserIDs();
	}
	else
	{
		$id = "";
		$email = "";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../support/styles.css">
		<?php
			$title = "Gruppe Erfassen";
			include_once( "includes/components/defhead.php" );

		?>
	</head>
	<body class="center">
		<?php
			include( "includes/components/headerlines.php" );
		?>

		<form action="groupedit2.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo $id;?>">
			<table>
				<tr><td class="fieldLabel">Name</td><td><input type="text" required="required" name="uiemail" value="<?php echo htmlspecialchars($email); ?>"></td></tr>
				<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldLabel">&nbsp;</td>
					<td>
						<input type="submit" value="Speichern">
						<?php
							echo "<input type='button' onClick='window.history.back();' value='Abbruch'>";
						?>
					</td>
				</tr>
			</table>
		</form>
		<?php
			if( $id && is_array( $groupMembers ) && is_array( $userIds ) )
			{
				echo( "<hr><table>" );
				forEach( $groupMembers as $theMember )
					echo( "<tr><td>{$theMember['email']}</td><td><a href='delFromGroup.php?id=$id&oldUserId={$theMember['id']}'>Löschen</a></td></tr>" );
				echo( "</table>" );

				echo( "<form name='addUserForm' action='add2group.php'>" );
				echo( "<input type='hidden' name='id' value='$id'>" );
				echo( "<select onChange='document.addUserForm.submit()' name='newUserId'>" );
				echo( "<option value='0'>Benutzer/Gruppe auswählen zum Hinzufügen</option>" );
				forEach( $userIds as $theUser )
					echo( "<option value='{$theUser['id']}'>{$theUser['email']}</option>" );
				
				echo( "</select>" );
				echo( "</form>" );
			}
		?>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
