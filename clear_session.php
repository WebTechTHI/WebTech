<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clear all session variables
session_destroy(); // Destroy the session
echo "Session cleared.\n";
?>
