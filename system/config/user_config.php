<?php

/* Purpose of file:
 * Provide config for the rest of the user-system
 */

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
//DB variables.
define('dbHost', "localhost");
define('db', "generic");
define('dbUsername', "root");
define('dbPassword', "");
define('UserTable', "users");
//Password salt.
define('salt', "");
//Session-specific.
define('sessionName', "default");
?>
