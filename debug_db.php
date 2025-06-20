<?php
require_once 'db_verbindung.php'; // Assumes this is in the root or include path is set up

// No headers for CLI
if (php_sapi_name() !== 'cli') {
    header('Content-Type: application/json');
}

$response = ['status' => 'error', 'message' => 'Invalid action or parameters.'];

// Simulate $_GET using $argv for CLI
// $argv[0] is the script name.
// $argv[1] could be action=query
// $argv[2] could be sql=SELECT...
$cli_args = [];
if (php_sapi_name() === 'cli' && $argc > 1) {
    parse_str(implode('&', array_slice($argv, 1)), $cli_args);
} else {
    $cli_args = $_GET; // For potential future web access if environment changes
}


if (isset($cli_args['action'])) {
    global $conn; // From db_verbindung.php

    try {
        if ($cli_args['action'] === 'query' && isset($cli_args['sql'])) {
            $stmt = $conn->prepare($cli_args['sql']);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $stmt->close();
            $response = ['status' => 'success', 'data' => $data];
        } elseif ($cli_args['action'] === 'exec' && isset($cli_args['sql'])) {
            // For exec, we'll also use sql GET param for simplicity in CLI test
            $stmt = $conn->prepare($cli_args['sql']);
            $success = $stmt->execute();
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            if ($success) {
                $response = ['status' => 'success', 'affected_rows' => $affected_rows];
            } else {
                $response = ['status' => 'error', 'message' => $conn->error];
            }
        }
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
    if ($conn) { // Connection might fail
        $conn->close();
    }
}

echo json_encode($response);
echo PHP_EOL; // Add a newline for CLI
?>
