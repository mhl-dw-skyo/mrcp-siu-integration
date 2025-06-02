<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course table
$courses = $DB->get_records('course');

// Prepare array to return
$data = [];

foreach ($courses as $course) {
    $data[] = [
        'id' => $course->id,
        'category ' => $course->category ,
        'sortorder ' => $course->sortorder ,
        'fullname' => $course->fullname,
        'shortname ' => $course->shortname ,
        'idnumber ' => $course->idnumber ,
        'summary' => $course->summary,
        'summaryformat' => $course->summaryformat,
        'format' => $course->format,
        'showgrades' => $course->showgrades,
        'newsitems' => $course->newsitems,
        'startdate' => date('Y-m-d H:i:s', $course->startdate),
        'enddate' => date('Y-m-d H:i:s', $course->enddate),
        'relativedatesmode' => $course->relativedatesmode,
        'marker' => $course->marker,
        'maxbytes' => $course->maxbytes,
        'legacyfiles' => $course->legacyfiles,
        'showreports' => $course->showreports,
        'visible' => $course->visible,
        'visibleold' => $course->visibleold,
        'downloadcontent' => $course->downloadcontent,
        'groupmode' => $course->groupmode,
        'groupmodeforce' => $course->groupmodeforce,
        'defaultgroupingid' => $course->defaultgroupingid,
        'lang' => $course->lang,
        'calendartype' => $course->calendartype,
        'theme' => $course->theme,
        'timecreated' => $course->timecreated,
        'timemodified' => $course->timemodified,
        'requested' => $course->requested,
        'enablecompletion' => $course->enablecompletion,
        'completionnotify' => $course->completionnotify,
        'cacherev' => $course->cacherev,
        'originalcourseid ' => $course->originalcourseid ,
        'showactivitydates' => $course->showactivitydates,
        'showcompletionconditions' => $course->showcompletionconditions,
        'pdfexportfont' => $course->pdfexportfont,
    ];
}
// Output as JSON
echo json_encode(['courses' => $data], JSON_PRETTY_PRINT);
