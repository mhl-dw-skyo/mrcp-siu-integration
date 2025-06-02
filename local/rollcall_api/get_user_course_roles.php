<?php
// File: local/rollcall_api/get_user_course_roles.php

define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../config.php');
// require_login();
header('Content-Type: application/json');

global $DB, $CFG;

$users = $DB->get_records('user', ['deleted' => 0]);
$response = [];

foreach ($users as $user) {
    if ($user->id <= 2) continue;

    $courses = $DB->get_records_sql("
        SELECT c.id AS courseid, c.fullname, c.shortname
        FROM {user_enrolments} ue
        JOIN {enrol} e ON ue.enrolid = e.id
        JOIN {course} c ON c.id = e.courseid
        WHERE ue.userid = :userid
    ", ['userid' => $user->id]);

    $courseData = [];

    foreach ($courses as $course) {
        // Get role (assumes one main role per course context)
        $context = context_course::instance($course->courseid);
        $role = $DB->get_record_sql("
            SELECT r.shortname
            FROM {role_assignments} ra
            JOIN {role} r ON ra.roleid = r.id
            WHERE ra.userid = :userid AND ra.contextid = :contextid
            LIMIT 1
        ", [
            'userid' => $user->id,
            'contextid' => $context->id
        ]);
        $rolename = $role ? $role->shortname : 'none';

        // Get group names
        $groups = $DB->get_records_sql("
            SELECT g.name
            FROM {groups_members} gm
            JOIN {groups} g ON gm.groupid = g.id
            WHERE gm.userid = :userid AND g.courseid = :courseid
        ", [
            'userid' => $user->id,
            'courseid' => $course->courseid
        ]);
        $groupnames = array_map(fn($g) => $g->name, $groups);

        $courseData[] = [
            'courseid' => $course->courseid,
            'shortname' => $course->shortname,
            'fullname' => $course->fullname,
            'role' => $rolename,
            'groups' => array_values($groupnames)
        ];
    }

    $response[] = [
        'userid' => $user->id,
        'fullname' => fullname($user),
        'email' => $user->email,
        'courses' => $courseData
    ];
}

echo json_encode($response);
