<?php
require_once(__DIR__ . '/../../config.php'); // Load Moodle environment
header('Content-Type: text/html');

global $DB;

// PostgreSQL connection
$pg_conn = pg_connect("host=my-roll-call-test-db.cve4g4mk6wmm.eu-central-1.rds.amazonaws.com dbname=test_db user=postgres password=Bh4]#z9rX5");

if (!$pg_conn) {
    die("PostgreSQL connection failed.");
}

// Define source => target table pairs
$tableMap = [
    'mdl_attendence' => 'mdl_attendence',
    'mdl_attendence_log' => 'mdl_attendence_log',
    'mdl_attendence_sessions' => 'mdl_attendence_sessions',
    'mdl_context' => 'mdl_context',
    'mdl_course' => 'mdl_course',
    'mdl_course_categories' => 'mdl_course_categories',
    'mdl_course_modules' => 'mdl_course_modules',
    'mdl_enrol' => 'mdl_enrol',
    'mdl_groups' => 'mdl_groups',
    'mdl_groups_members' => 'mdl_groups_members',
    'mdl_role' => 'mdl_role',
    'mdl_role_assignments' => 'mdl_role_assignments',
    'mdl_user' => 'mdl_user',
    'mdl_user_enrolments' => 'mdl_user_enrolments'
];

// Loop through each table pair
foreach ($tableMap as $moodleTable => $pgTable) {
    echo "<strong>Migrating $moodleTable ➝ $pgTable</strong><br>";

    // Get columns dynamically (only works in Moodle if we use SQL)
    $columns = $DB->get_columns($moodleTable); // returns stdClass for each column
    $columnNames = array_keys((array)$columns);
    $columnList = implode(", ", $columnNames);

    // Fetch all records from Moodle
    $records = $DB->get_records_sql("SELECT $columnList FROM {{$moodleTable}}");

    if (!empty($records)) {
        foreach ($records as $record) {
            $values = [];
            $updates = [];

            foreach ($columnNames as $col) {
                $value = $record->$col;
                $escaped = is_null($value) ? 'NULL' : pg_escape_literal($pg_conn, $value);
                $values[] = $escaped;

                if ($col != 'id') {
                    $updates[] = "$col = EXCLUDED.$col";
                }
            }

            $valuesList = implode(", ", $values);
            $updateClause = implode(", ", $updates);

            $sql = "INSERT INTO institute1.$pgTable ($columnList)
                    VALUES ($valuesList)
                    ON CONFLICT (id) DO UPDATE SET $updateClause";

            $pgResult = pg_query($pg_conn, $sql);

            if ($pgResult) {
                echo "✓ Row inserted/updated in $pgTable.<br>";
            } else {
                echo "✗ Error in $pgTable: " . pg_last_error($pg_conn) . "<br>";
            }
        }
    } else {
        echo "No data found or error in $moodleTable.<br>";
    }

    echo "<hr>";
}

pg_close($pg_conn);
?>
