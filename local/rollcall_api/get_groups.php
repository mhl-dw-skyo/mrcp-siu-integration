<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$groups = $DB->get_records('groups');

// Prepare array to return
$data = [];

foreach ($groups as $group) {
    $data[] = [
        'id' => $group->id,
        'courseid  ' => $group->courseid  ,
        'idnumber' => $group->idnumber,
        'name' => $group->name,
        'description' => $group->description,
        'descriptionformat' => $group->descriptionformat,
        'enrolmentkey' => $group->enrolmentkey,
        'picture' => $group->picture,
        'visibility' => $group->visibility,
        'participation' => $group->participation,
        'timecreated' => date('Y-m-d H:i:s', $group->timecreated),
        'timemodified' => date('Y-m-d H:i:s', $group->timemodified),    
    ];
}
// Output as JSON
echo json_encode(['groups' => $data], JSON_PRETTY_PRINT);
