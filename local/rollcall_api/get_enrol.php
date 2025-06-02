<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$enrols = $DB->get_records('enrol');

// Prepare array to return
$data = [];

foreach ($enrols as $enrol) {
    $data[] = [
        'id' => $enrol->id,
        'enrol' => $enrol->enrol,
        'status' => $enrol->status,
        'courseid ' => $enrol->courseid ,
        'sortorder' => $enrol->sortorder,
        'name' => $enrol->name,
        'enrolperiod' => $enrol->enrolperiod,
        'enrolstartdate' => $enrol->enrolstartdate,
        'enrolenddate' => $enrol->enrolenddate,
        'expirynotify' => $enrol->expirynotify,
        'expirythreshold' => $enrol->expirythreshold,
        'notifyall' => $enrol->notifyall,
        'password' => $enrol->password,
        'cost' => $enrol->cost,
        'currency' => $enrol->currency,
        'roleid ' => $enrol->roleid ,
        'customint1' => $enrol->customint1,
        'customint2' => $enrol->customint2,
        'customint3' => $enrol->customint3,
        'customint4' => $enrol->customint4,
        'customint5' => $enrol->customint5,
        'customint6' => $enrol->customint6,
        'customint7' => $enrol->customint7,
        'customint8' => $enrol->customint8,
        'customchar1' => $enrol->customchar1,
        'customchar2' => $enrol->customchar2,
        'customchar3' => $enrol->customchar3,
        'customdec1' => $enrol->customdec1,
        'customdec2' => $enrol->customdec2,
        'customtext1' => $enrol->customtext1,
        'customtext2' => $enrol->customint8,
        'customtext3' => $enrol->customtext3,
        'customtext4' => $enrol->customtext4,
        'timecreated' => date('Y-m-d H:i:s', $enrol->timecreated),
        'timemodified' => date('Y-m-d H:i:s', $enrol->timemodified)
    ];
}
// Output as JSON
echo json_encode(['enrol' => $data], JSON_PRETTY_PRINT);
