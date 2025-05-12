<?php
session_start();

if (!isset($_SESSION['username'])) {
    deleteError('You need to be logged to do this');
}

$creator = $_SESSION['username'];
$name = $_GET['name'];


if (file_exists(__DIR__ . "\\snippets\\" . $name)) {
    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or deleteError('Error connecting to databse');
    if ($dbcon) {
        $q1 = "SELECT * from snips where file_location = $1 and creator = $2";

        $resultFileName = pg_query_params($dbcon, $q1, array($name, $creator));
        $tupleFileName = pg_fetch_array($resultFileName, null, PGSQL_ASSOC);
        if ($tupleFileName) {

            $q2 = "DELETE FROM snips WHERE file_location = $1";
            $data = pg_query_params($dbcon, $q2, array($name));
            if (!$data) {
                deleteError('Error during post removal: ' . pg_last_error($dbcon));
            }

            $path = __DIR__ . "\\snippets\\" . $name;
            unlink($path);
            header("Location: explorer.php");
            exit();
        } else {
            deleteError('File not found in database (or your\'re not the owner)');
        }
    }
} else {
    deleteError('File not found in server files');
}

function deleteError($error)
{
    global $name;
    header("Location: snippet.php?name=" . $name);
    exit;
}
?>