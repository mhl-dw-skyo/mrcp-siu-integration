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
    'attendence' => 'mdl_attendence',
    'attendence_log' => 'mdl_attendence_log',
    'attendence_sessions' => 'mdl_attendence_sessions',
    'context' => 'mdl_context',
    'course' => 'mdl_course',
    'course_categories' => 'mdl_course_categories',
    'course_modules' => 'mdl_course_modules',
    'enrol' => 'mdl_enrol',
    'groups' => 'mdl_groups',
    'groups_members' => 'mdl_groups_members',
    'role' => 'mdl_role',
    'role_assignments' => 'mdl_role_assignments',
    'user' => 'mdl_user',
    'user_enrolments' => 'mdl_user_enrolments'
];

// Loop through each table pair
foreach ($tableMap as $moodleTable => $pgTable) {
    echo "<strong>Migrating $moodleTable ➝ $pgTable</strong><br>";

    // Get column names
    $columns = $DB->get_columns($moodleTable);
    if (!$columns) {
        echo "✗ Could not retrieve columns for $moodleTable.<br><hr>";
        continue;
    }

    $columnNames = array_keys($columns);
    $columnList = implode(", ", $columnNames);

    // Fetch all records from Moodle
    $records = $DB->get_records_sql("SELECT $columnList FROM $moodleTable");

    if (!empty($records)) {
        foreach ($records as $record) {
            $values = [];
            $updates = [];

            foreach ($columnNames as $col) {
                $value = $record->$col;
                $escaped = is_null($value) ? 'NULL' : pg_escape_literal($pg_conn, $value);
                $values[] = $escaped;

                if ($col !== 'id') {
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
        echo "No data found in $moodleTable or an error occurred.<br>";
    }

    echo "<hr>";
}

pg_close($pg_conn);
?>
