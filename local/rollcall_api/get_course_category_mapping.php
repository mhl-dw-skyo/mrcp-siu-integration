<?php

require_once(__DIR__ . '/../../config.php');
// require_login();
header('Content-Type: application/json');

global $DB;

$courses = $DB->get_records('course', null, '', 'id, fullname, shortname, category, startdate, enddate');
$response = [];

foreach ($courses as $course) {
    // Fetch course's category
    $final_cat = $DB->get_record('course_categories', ['id' => $course->category], '*');

    if (!$final_cat) continue;

    // Extract full path of category IDs
    $category_id_path = trim($final_cat->path, '/'); // e.g., 0/3/4/10
    $cat_ids = explode('/', $category_id_path);

    // Build name path and idnumber path
    $category_name_path = [];
    $category_idnumber_path = [];

    foreach ($cat_ids as $cat_id) {
        $cat = $DB->get_record('course_categories', ['id' => $cat_id], 'name, idnumber');
        if ($cat) {
            $category_name_path[] = $cat->name;
            $category_idnumber_path[] = $cat->idnumber ?: '';
        }
    }

    $response[] = [
        'course_id' => $course->id,
        'course_name' => $course->fullname,
        'shortname' => $course->shortname,
        'category_id_path' => $category_id_path,
        'category_name_path' => implode('/', $category_name_path),
        'category_idnumber_path' => implode('/', $category_idnumber_path),
        // 'start_date' => date('Y-m-d H:i:s', $course->startdate),
        // 'end_date' => date('Y-m-d H:i:s', $course->enddate),
        'start_date' => date('Y-m-d', $course->startdate),
        'end_date' => date('Y-m-d', $course->enddate)


    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);
