<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$attendance_sessions = $DB->get_records('attendance_sessions');

// Prepare array to return
$data = [];

foreach ($attendance_sessions as $attendance_session) {
    $data[] = [
        'id' => $attendance_session->id,
        'attendanceid' => $attendance_session->attendanceid,
        'groupid' => $attendance_session->groupid,
        'sessdate' => $attendance_session->sessdate,
        'duration' => $attendance_session->duration,
        'lasttaken' => date('Y-m-d H:i:s', $attendance_session->lasttaken),
        'lasttakenby' => $attendance_session->lasttakenby,
        'timemodified' => date('Y-m-d H:i:s', $attendance_session->timemodified),
        'description' => $attendance_session->description,
        'descriptionformat' => $attendance_session->descriptionformat,
        'studentscanmark' => $attendance_session->studentscanmark,
        'allowupdatestatus' => $attendance_session->allowupdatestatus,
        'studentsearlyopentime' => $attendance_session->studentsearlyopentime,
        'autoassignstatus' => $attendance_session->autoassignstatus,
        'studentpassword' => $attendance_session->studentpassword,
        'subnet' => $attendance_session->subnet,
        'automark' => $attendance_session->automark,
        'automarkcompleted' => $attendance_session->automarkcompleted,
        'statusset' => $attendance_session->statusset,
        'absenteereport' => $attendance_session->absenteereport,
        'preventsharedip' => $attendance_session->preventsharedip,
        'preventsharediptime' => $attendance_session->preventsharediptime,
        'caleventid' => $attendance_session->caleventid,
        'calendarevent' => $attendance_session->calendarevent,
        'includeqrcode' => $attendance_session->includeqrcode,
        'rotateqrcode' => $attendance_session->rotateqrcode,
        'rotateqrcodesecret' => $attendance_session->rotateqrcodesecret,
        'automarkcmid' => $attendance_session->automarkcmid
    ];
}
// Output as JSON
echo json_encode(['attendance sessions' => $data], JSON_PRETTY_PRINT);
