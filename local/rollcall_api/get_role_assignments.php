<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$role_assignments = $DB->get_records('role_assignments');

// Prepare array to return
$data = [];

foreach ($role_assignments as $role_assignment) {
    $data[] = [
        'id' => $role_assignment->id,
        'roleid ' => $role_assignment->roleid ,
        'contextid' => $role_assignment->contextid,
        'userid' => $role_assignment->userid,
        'timemodified' => date('Y-m-d H:i:s', $role_assignment->timemodified),
        'modifierid' => $role_assignment->modifierid,
        'component' => $role_assignment->component,
        'itemid' => $role_assignment->itemid,
        'sortorder' => $role_assignment->sortorder,
    ];
}
// Output as JSON
echo json_encode(['role assignment' => $data], JSON_PRETTY_PRINT);
