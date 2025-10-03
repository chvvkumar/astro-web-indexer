<?php
require_once __DIR__ . '/../includes/init.php';

// Only accept POST requests
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON data from request body
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Get the folder to reindex
$folder = isset($data['folder']) ? trim($data['folder'], '/') : '';
$force = isset($data['force']) && $data['force'] ? true : false;

// Security check: prevent command injection
if (preg_match('/[;&|<>]/', $folder)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid folder path']);
    exit;
}

// Prepare the Docker command
$command = 'docker-compose exec -T python python /app/reindex.py';

// Add the folder path (use root if empty)
$command .= ' "/fits/' . $folder . '"';

// Add optional parameters
if ($force) {
    $command .= ' --force';
}

// Add the --skip-cleanup flag to avoid removing files that are not in this specific folder
$command .= ' --skip-cleanup';

// Execute the command
$output = [];
$return_var = 0;
exec($command . ' 2>&1', $output, $return_var);

// Check the result
if ($return_var !== 0) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Reindexing failed',
        'command' => $command,
        'output' => implode("\n", $output),
        'code' => $return_var
    ]);
    exit;
}

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Reindexing completed successfully',
    'folder' => $folder,
    'output' => implode("\n", $output)
]);
