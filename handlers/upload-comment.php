<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['username'])) {
    postError("You have to be logged in to do this");
}

if (!isset($_POST['content']) || !isset($_POST['childOf']) || !isset($_POST['snippet'])) {
    postError("Missing or invalid comment information");
}

$creator = $_SESSION['username'];
$content = $_POST['content'];
$childOf = $_POST['childOf'];
$snippet = $_POST['snippet'];
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or postError('Error connecting to databse');
if ($dbcon) {
    $q1 = "SELECT * from snips where file_location = $1";
    $result = pg_query_params($dbcon, $q1, array($snippet));
    $tuple = pg_fetch_array($result, null, PGSQL_ASSOC);
    if (!$tuple) {
        postError('The snippet you are trying to comment on doesn\'t exist');
    }
    
    $q2 = '';
    $data = null;
    if ($childOf != -1) {
        $q2 = "INSERT INTO comments (creator, post_name, content, child_of) VALUES ($1, $2, $3, $4)";
        $data = pg_query_params($dbcon, $q2, array($creator, $snippet, $content, $childOf));
    } else {
        $q2 = "INSERT INTO comments (creator, post_name, content) VALUES ($1, $2, $3)";
        $data = pg_query_params($dbcon, $q2, array($creator, $snippet, $content));
    }
    
    if (!$data) {
        postError('Error during comment process');
    }

    echo json_encode(['success' => true]);
    exit();
}

function postError($error)
{
    echo json_encode(['success' => false, 'error' => $error]);
    exit;
}
?>