<?php require_once( "includes/components/login.php" ); ?>
<?php

	if( array_key_exists( "voteName", $_GET ) )
		$voteName = $_GET["voteName"];

	$page=0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Strict//EN">

<html>
	<head>
		<?php
			$title = APPLICATION_NAME;
			include_once( "includes/components/defhead.php" );
		?>
		<script language="JavaScript">
			var page = <?php echo $page; ?>;
			var voteName = "<?php echo $voteName; ?>";
			

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
				xmlhttp.open("GET","votes2.php?page="+page+"&voteName="+voteName,true);
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

		<h2>Abstimmungen bearbeiten</h2>

		<form name="searchForm" action="votes.php" method="get">
			<table>
				<tr><td class="fieldLabel">Name</td><td><input type="text" name="voteName" value="<?php if( isset( $voteName ) ) echo $voteName; ?>"></td></tr>
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
			<?php include( "votes2.php" );  ?>
		</div>

		<?php include( "includes/components/footerlines.php" ); ?>
	</body>
</html>
			
