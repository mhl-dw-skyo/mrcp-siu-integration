<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$groups = $DB->get_records('groups_members');

// Prepare array to return
$data = [];

foreach ($groups as $group) {
    $data[] = [
        'id' => $group->id,
        'groupid   ' => $group->groupid,
        'userid' => $group->userid,
        'timeadded' => date('Y-m-d H:i:s', $group->timeadded),    
        'component' => $group->component,
        'itemid' => $group->itemid

    ];
}
// Output as JSON
echo json_encode(['groups members' => $data], JSON_PRETTY_PRINT);
