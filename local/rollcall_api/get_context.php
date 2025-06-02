<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$context = $DB->get_records('context');

// Prepare array to return
$data = [];

foreach ($context as $con) {
    $data[] = [
        'id' => $con->id,
        'contextlevel ' => $con->contextlevel ,
        'instanceid' => $con->instanceid,
        'path' => $con->path,
        'depth' => $con->depth,
        'locked' => $con->locked
    ];
}
// Output as JSON
echo json_encode(['context' => $data], JSON_PRETTY_PRINT);
