<?php
// No headers if run from CLI
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
}

if (session_status() == PHP_SESSION_NONE) {
    // Session handling in CLI can be tricky and might require specific configurations
    // or manual session ID management. For this test, we'll assume it works or focus on tests
    // that don't heavily rely on cross-script CLI session persistence if it becomes an issue.
    // A simple session_start() might work if the save path is writable and consistent.
    ini_set('session.save_path', sys_get_temp_dir()); // Ensure a writable path for CLI
    session_start();
}

echo json_encode($_SESSION);
echo PHP_EOL; // Add a newline for better CLI readability
?>
