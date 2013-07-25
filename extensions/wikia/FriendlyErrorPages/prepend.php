<?php

include __DIR__ . DIRECTORY_SEPARATOR . 'FriendlyErrorPages.class.php';
register_shutdown_function( array( 'FriendlyErrorPages', 'shutdownFunction' ) );