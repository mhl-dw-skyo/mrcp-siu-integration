<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_modules table
$course_modules = $DB->get_records('course_modules');

// Prepare array to return
$data = [];

foreach ($course_modules as $mod) {
    $data[] = [
        'id' => $mod->id,
        'course' => $mod->course,
        'module' => $mod->module,
        'instance ' => $mod->instance ,
        'section' => $mod->section,
        'idnumber ' => $mod->idnumber ,
        'added' => $mod->added,
        'score' => $mod->score,
        'indent' => $mod->indent,
        'visible' => $mod->visible,
        'search' => $mod->search,
        'visible' => $mod->visible,
        'visibleoncoursepage' => $mod->visibleoncoursepage,
        'visibleold' => $mod->visibleold,
        'groupmode' => $mod->groupmode,
        'groupingid ' => $mod->groupingid ,
        'completion' => $mod->completion,
        'completiongradeitemnumber' => $mod->completiongradeitemnumber,
        'completionview' => $mod->completionview,
        'completionexpected' => $mod->completionexpected,
        'completionpassgrade' => $mod->completionpassgrade,
        'showdescription' => $mod->showdescription,
        'availability' => $mod->availability,
        'deletioninprogress' => $mod->deletioninprogress,
        'downloadcontent' => $mod->downloadcontent,
        'lang' => $mod->lang
    ];
}
// Output as JSON
echo json_encode(['course modules' => $data], JSON_PRETTY_PRINT);
