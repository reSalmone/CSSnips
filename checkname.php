<?php
session_start();
header('Content-Type: application/json');

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or exit();
    if ($dbcon) {
        $q1 = "SELECT * from snips where file_location = $1";
    
        $result = pg_query_params($dbcon, $q1, array($name));
        $tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
        if (!$tuple) {
            echo json_encode(['success' => true]);
            exit();
        }
    }
}
echo json_encode(['success' => false]);
exit();
?>