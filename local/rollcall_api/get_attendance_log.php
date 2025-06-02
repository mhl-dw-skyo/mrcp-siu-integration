<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$attendance_log = $DB->get_records('attendance_log');

// Prepare array to return
$data = [];

foreach ($attendance_log as $log) {
    $data[] = [
        'id' => $log->id,
        'sessionid ' => $log->sessionid ,
        'studentid' => $log->studentid,
        'statusid' => $log->statusid,
        'statusset' => $log->statusset,
        'timetaken' => date('Y-m-d H:i:s', $log->timetaken),
        'takenby' => $log->takenby,
        'remarks' => $log->remarks,
        'ipaddress' => $log->ipaddress,  
    ];
}
// Output as JSON
echo json_encode(['attendance log' => $data], JSON_PRETTY_PRINT);
