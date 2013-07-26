<?php

/*
*** Friendly Error Pages ***

# Extension by Adam Karmiński adam.karminski@wikia-inc.com and Michał Mix Roszka mix@wikia-inc.com

# It is a file that is prepended to every PHP script. It registers custom shutdown function that displays user friendly error pages instead of X-Debug messages.
*/

include __DIR__ . DIRECTORY_SEPARATOR . 'FriendlyErrorPages.class.php';
register_shutdown_function( array( 'FriendlyErrorPages', 'shutdownFunction' ) );