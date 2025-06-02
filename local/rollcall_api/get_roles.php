<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$roles = $DB->get_records('role');

// Prepare array to return
$data = [];

foreach ($roles as $role) {
    $data[] = [
        'id' => $role->id,
        'name ' => $role->name ,
        'shortname' => $role->shortname,
        'description' => $role->description,
        'sortorder' => $role->sortorder,
        'archetype' => $role->archetype,
    ];
}
// Output as JSON
echo json_encode(['roles' => $data], JSON_PRETTY_PRINT);
