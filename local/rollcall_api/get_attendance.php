<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$attendence = $DB->get_records('attendance');

// Prepare array to return
$data = [];

foreach ($attendence as $attend) {
    $data[] = [
        'id' => $attend->id,
        'course   ' => $attend->course,
        'name' => $attend->name,
        'grade' => $attend->grade,
        'timemodified' => date('Y-m-d H:i:s', $attend->timemodified),    
        'intro' => $attend->intro,
        'introformat' => $attend->introformat,
        'subnet' => $attend->subnet,
        'sessiondetailspos' => $attend->sessiondetailspos,
        'showsessiondetails' => $attend->showsessiondetails,
        'showextrauserdetails' => $attend->showextrauserdetails

    ];
}
// Output as JSON
echo json_encode(['attendence' => $data], JSON_PRETTY_PRINT);
