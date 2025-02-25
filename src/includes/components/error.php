<p>
	<?php
		if( isset( $error ) )
		{
			if( is_object( $error ) )
			{
				echo( $error->errorText . "<br>" );
				echo( $error->errorDetail . "<br>" );
			}
			else if( is_string( $error ) )
				echo( $error );
			else
				var_dump( $error );
		}
		else
			echo( "Unbekannter Fehler" );
	?>
</p>