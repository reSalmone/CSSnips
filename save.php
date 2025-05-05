<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !isset($_POST['snippet'])) {
    echo json_encode(['success' => false, 'error' => 'Brother how did you even get here']);
    exit;
}

$username = $_SESSION['username'];
$snippet = $_POST['snippet'];

//get current liked snippets
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");
if ($dbcon != -1) {
    $q1 = "SELECT savedsnippets FROM users WHERE username = $1";
    $res = pg_query_params($dbcon, $q1, array($username));

    if (!$res) {
        echo json_encode(['success' => false, 'error' => 'Error finding your liked snippets']);
        exit;
    }

    $row = pg_fetch_assoc($res);
    $saved = explode(',', trim($row['savedsnippets'], '{}'));

    $q3 = null;
    if (in_array($snippet, $saved)) {
        $saved = array_diff($saved, [$snippet]); //remove like
        $q3 = "UPDATE snips SET saved = saved - 1 WHERE file_location = $1;";
    } else {
        $saved[] = $snippet; //add like
        $q3 = "UPDATE snips SET saved = saved + 1 WHERE file_location = $1;";
    }

    $saved = array_filter($saved);
    $saved = array_values($saved);

    $newSaved = '{' . implode(',', $saved) . '}';
    if (empty($saved)) {
        $newSaved = '{}';
    }

    $q2 = "UPDATE users SET savedsnippets = $1 WHERE username = $2";
    $updated = pg_query_params($dbcon, $q2, array($newSaved, $username));

    //update the saves
    $data = pg_query_params($dbcon, $q3, array($snippet));

    //get the value
    $q4 = "SELECT saved FROM snips WHERE file_location = $1";
    $result = pg_query_params($dbcon, $q4, array($snippet));
    $tuple = pg_fetch_array($result, null, PGSQL_ASSOC);

    echo json_encode(['success' => true, 'value' => $tuple['saved']]);
}