<?php
require_once(__DIR__ . '/../../config.php'); // Load Moodle environment
header('Content-Type: text/html');

global $DB, $CFG;

// 🛠️ Override prefix because tables are not actually prefixed
$CFG->prefix = '';


// ✅ PostgreSQL connection
$pg_conn = pg_connect("host=my-roll-call-test-db.cve4g4mk6wmm.eu-central-1.rds.amazonaws.com dbname=test_db user=postgres password=Bh4]#z9rX5");
if (!$pg_conn) {
    die("❌ PostgreSQL connection failed.<br>");
}

// ✅ Base table names (without prefix)
$baseTables = [
    'attendance',
    'attendance_log',
    'attendance_sessions',
    'context',
    'course',
    'course_categories',
    'course_modules',
    'enrol',
    'groups',
    'groups_members',
    'role',
    'role_assignments',
    'user',
    'user_enrolments'
];

// ✅ Actual prefix (in your case, it's empty)
$prefix = $CFG->prefix;

// ✅ PostgreSQL schema to insert into
$schema = 'institute_1';

// ✅ Get all available tables
$availableTables = $DB->get_tables();

foreach ($baseTables as $baseTable) {
    $moodleTable = $prefix . $baseTable; // final table name
    // $pgTable = $moodleTable;

    $pgTable = "mdl_" . $baseTable; // ✅ Fix: PostgreSQL table name


    echo "<strong>🔄 Migrating <code>$moodleTable</code> ➝ <code>$pgTable</code></strong><br>";

    if (!in_array($moodleTable, $availableTables)) {
        echo "⚠ Table <code>$moodleTable</code> not found in Moodle database.<br><hr>";
        continue;
    }

    $columns = $DB->get_columns($moodleTable);
    if (!$columns) {
        echo "✗ No columns found for <code>$moodleTable</code>.<br><hr>";
        continue;
    }

    $columnNames = array_keys((array)$columns);
    $columnList = implode(", ", array_map(fn($col) => "\"$col\"", $columnNames));

    // Fetch records
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

            $sql = "INSERT INTO \"$schema\".\"$pgTable\" ($columnList)
                    VALUES ($valuesList)
                    ON CONFLICT DO NOTHING";

            $pgResult = pg_query($pg_conn, $sql);

            if ($pgResult) {
                echo "✔ Row inserted/updated in <code>$pgTable</code><br>";
            } else {
                echo "✗ PostgreSQL error in <code>$pgTable</code>: " . pg_last_error($pg_conn) . "<br>";
            }
        }
    } else {
        echo "⚠ No records found in <code>$moodleTable</code><br>";
    }

    echo "<hr>";
}

pg_close($pg_conn);
echo "<strong>✅ Migration complete.</strong>";
?>
