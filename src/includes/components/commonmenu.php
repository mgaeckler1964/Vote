<?php
	if( isset( $menu ) )
	{
		echo( "<div class='Menu'>" );

		if( array_key_exists('left', $menu ) )
		{
			foreach( $menu['left'] as $mentry )
			{
				echo( "<div class='MenuLeftEntry'>" );
					echo( "<a href='{$mentry['href']}'>{$mentry['label']}</a>" );
				echo( "</div>" );
			}
		}
		if(!array_key_exists('right', $menu) )
		{
			$menuRight = array();
		
			if( isset( $actUser ) )
				$menuRight[] = array( "href" => "logout.php", "label" => "Abmelden" );
		
			if( isset( $actUser ) && !$actUser['guest'] ) {
				if( defined('SELF_REGISTER') && SELF_REGISTER!=0 )
				{
					$menuRight[] = array( "href" => "admin/useredit.php?profile=1", "label" => "Profil &auml;ndern" );
				}
				else
				{
					$menuRight[] = array( "href" => "password.php", "label" => "Kennwort &auml;ndern" );
				}
			}
			else
			{
				$menuRight[] = array( "href" => "login.php", "label" => "Anmelden" );
				if( defined('SELF_REGISTER') && SELF_REGISTER!=0 )
				{
					$menuRight[] = array( "href" => "admin/useredit.php?register=1", "label" => "Registrieren" );
				}
			}
			if( isset( $actUser ) && $actUser['administrator'] ) {
				$menuRight[] = array( "href" => "admin/index.php", "label" => "Administration" );
			}
			
			$menu['right'] = $menuRight;
		}
		
		foreach( $menu['right'] as $mentry )
		{
			echo( "<div class='MenuRightEntry'>" );
				echo( "<a href='{$mentry['href']}'>{$mentry['label']}</a>" );
			echo( "</div>" );
		}

		echo( "</div><hr style='clear:right;'>" );
	}
?>
