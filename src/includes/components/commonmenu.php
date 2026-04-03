<style>
	div.popupMenu
	{
		display:none;
		visibility:hidden;
		position: absolute;
		background-color: #000000;
	}
</style>
<script>
	function showMenu(theMenuId)
	{
		var theMenu = document.getElementById( theMenuId );
		theMenu.style.display = "block";
		theMenu.style.visibility = "visible";
	}
	function hideMenu(theMenuId)
	{
		var theMenu = document.getElementById( theMenuId );
		theMenu.style.display = "none";
		theMenu.style.visibility = "hidden";
	}
	popup1 = "popup1";
</script>
<?php
	if( isset( $menu ) ) {
		$leftID = 1;
		echo( "<div class='Menu'>" );

		if( array_key_exists('left', $menu ) ) {
			foreach( $menu['left'] as $mentry ) {
				if( array_key_exists("href", $mentry) )
					$href = $mentry["href"];
				else
					$href = "#";
					
				$doPopup = array_key_exists('submenu', $mentry );
				echo( "<div class='MenuLeftEntry'" );
				if( $doPopup )
					echo( " onmouseover='showMenu(\"popup{$leftID}\");' onmouseout='hideMenu(\"popup{$leftID}\");'" );

				$label = htmlspecialchars($mentry['label'], ENT_QUOTES, 'ISO-8859-1');
				echo( "><a href='{$href}'>{$label}</a>" );
					if( $doPopup ) {
						$subMenu = $mentry['submenu'];
						echo( "<div class='popupMenu' id='popup1'>" );
							foreach($subMenu as $submentry) {
								if($submentry['label']=='-')
									echo("<hr>");
								else
								{
									if( array_key_exists("href", $submentry) )
										$href = $submentry["href"];
									else
										$href = "#";
									$label = htmlspecialchars($submentry['label'], ENT_QUOTES, 'ISO-8859-1');
									echo( "<a href='{$href}'>{$label}</a><br>" );
								}
							}
						echo( "</div>" );
						$leftID++;
					}
				echo( "</div>" );
			}
		}
		if(!array_key_exists('right', $menu) ) {
			$menuRight = array();
		
			if( isset( $actUser ) )
				$menuRight[] = array( "href" => "logout.php", "label" => "Abmelden" );
		
			if( isset( $actUser ) && !$actUser['guest'] ) {
				if( defined('SELF_REGISTER') && SELF_REGISTER!=0 )
					$menuRight[] = array( "href" => "admin/useredit.php?profile=1", "label" => "Profil ändern" );
				else
					$menuRight[] = array( "href" => "password.php", "label" => "Kennwort ändern" );
			}
			else
			{
				if( is_file( "login.php" ) )
					$menuRight[] = array( "href" => "login.php", "label" => "Anmelden" );
				if( defined('SELF_REGISTER') && SELF_REGISTER!=0 )
					$menuRight[] = array( "href" => "admin/useredit.php?register=1", "label" => "Registrieren" );
			}
			if( isset( $actUser ) && $actUser['administrator'] )
				$menuRight[] = array( "href" => "admin/index.php", "label" => "Administration" );
			
			$menu['right'] = $menuRight;
		}
		
		foreach( $menu['right'] as $mentry )
		{
			echo( "<div class='MenuRightEntry'>" );
				$label = htmlspecialchars($mentry['label'], ENT_QUOTES, 'ISO-8859-1');
				echo( "<a href='{$mentry['href']}'>{$label}</a>" );
			echo( "</div>" );
		}

		echo( "</div><hr style='clear:right;'>" );
	}
?>
