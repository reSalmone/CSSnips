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
    $q1 = "SELECT likedsnippets FROM users WHERE username = $1";
    $res = pg_query_params($dbcon, $q1, array($username));

    if (!$res) {
        echo json_encode(['success' => false, 'error' => 'Error finding your liked snippets']);
        exit;
    }

    $row = pg_fetch_assoc($res);
    $liked = explode(',', trim($row['likedsnippets'], '{}'));

    $q3 = null;
    if (in_array($snippet, $liked)) {
        $liked = array_diff($liked, [$snippet]); //remove like
        $q3 = "UPDATE snips SET likes = likes - 1 WHERE file_location = $1;";
    } else {
        $liked[] = $snippet; //add like
        $q3 = "UPDATE snips SET likes = likes + 1 WHERE file_location = $1;";
    }

    $liked = array_filter($liked);
    $liked = array_values($liked);

    $newLiked = '{' . implode(',', $liked) . '}';
    if (empty($liked)) {
        $newLiked = '{}';
    }

    $q2 = "UPDATE users SET likedsnippets = $1 WHERE username = $2";
    $updated = pg_query_params($dbcon, $q2, array($newLiked, $username));

    //update the likes
    $data = pg_query_params($dbcon, $q3, array($snippet));

    //get the value
    $q4 = "SELECT likes, id FROM snips WHERE file_location = $1";
    $result = pg_query_params($dbcon, $q4, array($snippet));
    $tuple = pg_fetch_array($result, null, PGSQL_ASSOC);

    echo json_encode(['success' => true, 'value' => $tuple['likes'], 'id' => $tuple['id']]);
}