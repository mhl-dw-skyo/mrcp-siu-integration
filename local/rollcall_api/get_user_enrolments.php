<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$user_enrolments = $DB->get_records('user_enrolments');

// Prepare array to return
$data = [];

foreach ($user_enrolments as $user_enrolment) {
    $data[] = [
        'id' => $user_enrolment->id,
        'status ' => $user_enrolment->status ,
        'enrolid' => $user_enrolment->enrolid,
        'userid' => $user_enrolment->userid,
        'timestart' => date('Y-m-d H:i:s', $user_enrolment->timestart),
        'timeend' => date('Y-m-d H:i:s', $user_enrolment->timeend),
        'modifierid' => $user_enrolment->modifierid,
        'timecreated' => date('Y-m-d H:i:s', $user_enrolment->timecreated),
        'timemodified' => date('Y-m-d H:i:s', $user_enrolment->timemodified)
    ];
}
// Output as JSON
echo json_encode(['user enrolments' => $data], JSON_PRETTY_PRINT);
