<?php
session_start();
header('Content-Type: application/json');

if (isset($_POST['name'])) {
    $name = $_POST['name'];

    if (!preg_match('/^[A-Za-z0-9]{3,16}$/', $name)) {
        echo json_encode(['success' => false, 'error' => "Invalid username, must be:\n\t1: at least 3 characters\n\t2: not more than 16 characters"]);
        exit();
    }
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or exit();
    if ($dbcon) {
        $q1 = "SELECT * from users where username = $1";

        $result = pg_query_params($dbcon, $q1, array($name));
        $tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
        if (!$tuple) {
            echo json_encode(['success' => true]);
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'Username already taken']);
            exit();
        }
    }
}
echo json_encode(['success' => false]);
exit();
?>