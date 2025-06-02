<?php

require_once(__DIR__ . '/../../config.php');
// require_login(); // Enforces user authentication
header('Content-Type: application/json');

global $DB;

// Get all records from course_categories table
$categories = $DB->get_records('course_categories');

// Prepare array to return
$data = [];

foreach ($categories as $cat) {
    $data[] = [
        'id' => $cat->id,
        'name' => $cat->name,
        'idnumber' => $cat->idnumber,
        'description' => strip_tags($cat->description),
        'descriptionformat' => $cat->descriptionformat,
        'parent' => strip_tags($cat->parent),
        'sortorder' => $cat->sortorder,
        'coursecount' => $cat->coursecount,
        'visible' => $cat->visible,
        'visibleold' => $cat->visibleold,
        'timemodified' => date('Y-m-d H:i:s', $cat->timemodified),
        'depth' => $cat->depth,
        'path' => $cat->path,
        'theme' => $cat->theme,
    ];
}
// Output as JSON
echo json_encode(['categories' => $data], JSON_PRETTY_PRINT);
