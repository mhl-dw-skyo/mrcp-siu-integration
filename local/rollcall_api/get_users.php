<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$users = $DB->get_records('user');

// Prepare array to return
$data = [];

foreach ($users as $user) {
    $data[] = [
        'id' => $user->id,
        'auth ' => $user->auth ,
        'confirmed' => $user->confirmed,
        'policyagreed' => $user->policyagreed,
        'deleted' => $user->deleted,
        'suspended' => $user->suspended,
        'mnethostid' => $user->mnethostid,
        'username' => $user->username,
        'password' => $user->password,
        'idnumber' => $user->idnumber,
        'firstname' => $user->firstname,
        'lastname' => $user->lastname,
        'email' => $user->email,
        'emailstop' => $user->emailstop,
        'phone1' => $user->phone1,
        'phone2' => $user->phone2,
        'institution' => $user->institution,
        'department' => $user->department,
        'address' => $user->address,
        'city' => $user->city,
        'country' => $user->country,
        'lang' => $user->lang,
        'calendartype' => $user->calendartype,
        'theme' => $user->theme,
        'timezone' => $user->timezone,
        'firstaccess' => $user->firstaccess,
        'lastaccess' => $user->lastaccess,
        'lastlogin' => $user->lastlogin,
        'currentlogin' => $user->currentlogin,
        'lastip' => $user->lastip,
        'secret' => $user->secret,
        'picture' => $user->picture,
        'description' => $user->description,
        'descriptionformat' => $user->descriptionformat,
        'mailformat' => $user->mailformat,
        'maildigest' => $user->maildigest,
        'maildisplay' => $user->maildisplay,
        'autosubscribe' => $user->autosubscribe,
        'trackforums' => $user->trackforums,
        'timecreated' => date('Y-m-d H:i:s', $user->timecreated),
        'timemodified' => date('Y-m-d H:i:s', $user->timemodified),
        'trustbitmask' => $user->trustbitmask,
        'imagealt' => $user->imagealt,
        'lastnamephonetic' => $user->lastnamephonetic,
        'firstnamephonetic' => $user->firstnamephonetic,
        'middlename' => $user->middlename,
        'alternatename' => $user->alternatename,
        'moodlenetprofile' => $user->moodlenetprofile,
    ];
}
// Output as JSON
echo json_encode(['users' => $data], JSON_PRETTY_PRINT);
