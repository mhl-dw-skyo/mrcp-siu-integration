<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from modules table
$modules = $DB->get_records('modules');

// Prepare array to return
$data = [];

foreach ($modules as $mod) {
    $data[] = [
        'id' => $mod->id,
        'name' => $mod->name,
        'cron' => $mod->cron,
        'lastcron' => $mod->lastcron,
        'search' => $mod->search,
        'visible' => $mod->visible
    ];
}
// Output as JSON
echo json_encode(['modules' => $data], JSON_PRETTY_PRINT);
