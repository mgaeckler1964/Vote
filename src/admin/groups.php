<?php require_once( "includes/components/login.php" ); ?>
<?php

	if( array_key_exists( "groupName", $_POST ) )
		$groupName = $_POST["groupName"];

	$page=0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = "Gruppen";
			include_once( "includes/components/defhead.php" );
		?>

		<script language="JavaScript">
			var page = <?php echo $page; ?>;
			var groupName = "<?php echo $groupName; ?>";
			

			function showPage()
			{
				var xmlhttp;    

				if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp=new XMLHttpRequest();
				}
				else
				{// code for IE6, IE5
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						document.getElementById("searchResult").innerHTML=xmlhttp.responseText;
					}
				}
				xmlhttp.open("GET","groups2.php?page="+page+"&groupName="+groupName,true);
				xmlhttp.send();
			}
			function prevPage()
			{
				if( page > 0 )
				{
					page--;
					showPage();
				}
			}
			function nextPage()
			{
				page++;
				showPage();
			}
		</script>
	</head>
	<body class="personen">
		<?php include( "includes/components/headerlines.php" ); ?>

		<form name="searchForm" action="groups.php" method="post">
			<table>
				<tr><td class="fieldLabel">Name</td><td><input type="text" name="groupName" value="<?php if( isset( $groupName ) ) echo htmlspecialchars($groupName, ENT_QUOTES, 'ISO-8859-1'); ?>"></td></tr>
				<tr><td class="fieldLabel">&nbsp;</td><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldLabel"></td>
					<td>
						<input type="submit" value="Suche">
					</td>
				</tr>
			</table>
		</form>
		
		<div id="searchResult">
			<?php include( "groups2.php" );  ?>
		</div>

		<p><a href="groupedit.php">&gt;&gt; Neue Gruppe</a></p>
		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
			
