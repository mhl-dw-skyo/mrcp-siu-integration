<?php
require_once(__DIR__ . '/../../config.php'); // Load Moodle environment
header('Content-Type: text/html');

global $DB;

// PostgreSQL connection
$pg_conn = pg_connect("host=my-roll-call-test-db.cve4g4mk6wmm.eu-central-1.rds.amazonaws.com dbname=test_db user=postgres password=Bh4]#z9rX5");

if (!$pg_conn) {
    die("PostgreSQL connection failed.");
}

// Define Moodle → PostgreSQL table mappings
$tableMap = [
    'mdl_attendance' => 'mdl_attendance',
    'mdl_attendance_log' => 'mdl_attendance_log',
    'mdl_attendance_sessions' => 'mdl_attendance_sessions',
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

// Target PostgreSQL schema
$schema = 'institute_1';

foreach ($tableMap as $moodleTable => $pgTable) {
    echo "<strong>Migrating <code>$moodleTable</code> ➝ <code>$pgTable</code></strong><br>";

    // Get Moodle table columns
    $columns = $DB->get_columns($moodleTable);
    if (!$columns) {
        echo "✗ No columns found for $moodleTable.<br><hr>";
        continue;
    }

    $columnNames = array_keys((array)$columns);
    $columnList = implode(", ", array_map(fn($col) => "\"$col\"", $columnNames));

    // Fetch all records from Moodle
    $records = $DB->get_records_sql("SELECT * FROM {{$moodleTable}}");

    if (!empty($records)) {
        foreach ($records as $record) {
            $values = [];
            $updates = [];

            foreach ($columnNames as $col) {
                $value = $record->$col ?? null;
                $escaped = is_null($value) ? 'NULL' : pg_escape_literal($pg_conn, $value);
                $values[] = $escaped;

                if ($col !== 'id') {
                    $updates[] = "\"$col\" = EXCLUDED.\"$col\"";
                }
            }

            $valuesList = implode(", ", $values);
            $updateClause = implode(", ", $updates);

            // Prepare SQL with schema + table name
            $sql = "INSERT INTO \"$schema\".\"$pgTable\" ($columnList)
                    VALUES ($valuesList)
                    ON CONFLICT (id) DO UPDATE SET $updateClause";

            $pgResult = pg_query($pg_conn, $sql);

            if ($pgResult) {
                echo "✔ Row inserted/updated in <code>$pgTable</code><br>";
            } else {
                echo "✗ PostgreSQL error in $pgTable: " . pg_last_error($pg_conn) . "<br>";
            }
        }
    } else {
        echo "⚠ No records found in $moodleTable.<br>";
    }

    echo "<hr>";
}

pg_close($pg_conn);
echo "<strong>✅ Migration complete.</strong>";
?>
