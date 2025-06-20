<?php
require_once(__DIR__ . '/../../config.php'); // Load Moodle environment
header('Content-Type: text/html');

global $DB, $CFG;

//Override prefix because tables are not actually prefixed
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
    'attendance_statuses',
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

$batchSize = 200;

foreach ($baseTables as $baseTable) {
    $moodleTable = $prefix . $baseTable;
    $pgTable = "mdl_" . $baseTable;

    echo "<strong>🔄 Migrating <code>$moodleTable</code> ➝ <code>$pgTable</code></strong><br>";

    if (!in_array($moodleTable, $availableTables)) {
        echo "⚠ Table <code>$moodleTable</code> not found.<br><hr>";
        continue;
    }

    $columns = $DB->get_columns($moodleTable);
    if (!$columns) {
        echo "✗ No columns found for <code>$moodleTable</code>.<br><hr>";
        continue;
    }

    $columnNames = array_keys((array)$columns);
    $columnList = implode(", ", array_map(fn($col) => "\"$col\"", $columnNames));

    // ✅ Get max ID already inserted into PostgreSQL
    $res = pg_query($pg_conn, "SELECT MAX(id) AS max_id FROM \"$schema\".\"$pgTable\"");
    $row = pg_fetch_assoc($res);
    $maxId = $row['max_id'] ?? 0;

    // ✅ Fetch only new records from Moodle
    $records = $DB->get_records_sql("SELECT * FROM {{$moodleTable}} WHERE id > $maxId ORDER BY id ASC");

    if (!empty($records)) {
        $batch = [];
        $counter = 0;

        foreach ($records as $record) {
            $values = [];

            foreach ($columnNames as $col) {
                $value = $record->$col ?? null;
                $escaped = is_null($value) ? 'NULL' : pg_escape_literal($pg_conn, $value);
                $values[] = $escaped;
            }

            $batch[] = '(' . implode(", ", $values) . ')';
            $counter++;

            // ✅ Execute batch if batch size reached
            if (count($batch) === $batchSize) {
                $valuesBlock = implode(",\n", $batch);
                $sql = "INSERT INTO \"$schema\".\"$pgTable\" ($columnList)
                    VALUES $valuesBlock
                    ON CONFLICT DO NOTHING";

                pg_query($pg_conn, $sql);
                echo "✔ Inserted batch of $batchSize into <code>$pgTable</code><br>";
                $batch = [];
            }
        }

        // ✅ Insert remaining rows (if any)
        if (!empty($batch)) {
            $valuesBlock = implode(",\n", $batch);
            $sql = "INSERT INTO \"$schema\".\"$pgTable\" ($columnList)
                    VALUES $valuesBlock
                    ON CONFLICT DO NOTHING";

            pg_query($pg_conn, $sql);
            echo "✔ Inserted remaining " . count($batch) . " rows into <code>$pgTable</code><br>";
        }
    } else {
        echo "⚠ No new records found in <code>$moodleTable</code><br>";
    }

    echo "<hr>";
}

pg_close($pg_conn);
echo "<strong>✅ Migration complete.</strong>";
?>
