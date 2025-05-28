<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !isset($_POST['username']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$myusername = $_SESSION['username'];
$username = $_POST['username'];
$action = $_POST['action'];

if (empty($myusername)) {
    echo json_encode(['success' => false, 'message' => 'MyUsername is empty']);
    exit;
}
if (empty($username)) {
    echo json_encode(['success' => false, 'message' => 'Target username is empty']);
    exit;
}

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");

if (!$dbcon) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

if ($action === 'follow') {
    $query01 = "UPDATE users SET following = '{}' WHERE username = '$myusername' AND following IS NULL";
    $query02 = "UPDATE users SET followers = '{}' WHERE username ILIKE '$username' AND followers IS NULL";
    $res01 = pg_query($query01);
    $res02 = pg_query($query02);

    if (!$res01 || !$res02) {
        echo json_encode(['success' => false, 'message' => 'Failed to initialize arrays']);
        exit;
    }

    $query1 = "UPDATE users SET following = array_append(following, '$username') WHERE username = '$myusername'";
    $query2 = "UPDATE users SET followers = array_append(followers, '$myusername') WHERE username ILIKE '$username'";
    $res1 = pg_query($query1);
    $res2 = pg_query($query2);

    if ($res1 && $res2) {
        echo json_encode(['success' => true, 'newLabel' => 'Following', 'newClass' => 'following']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to follow user']);
    }

} elseif ($action === 'unfollow') {
    $query1 = "UPDATE users SET following = array_remove(following, '$username') WHERE username = '$myusername'";
    $query2 = "UPDATE users SET followers = array_remove(followers, '$myusername') WHERE username ILIKE '$username'";
    $res1 = pg_query($query1);
    $res2 = pg_query($query2);


    if ($res1 && $res2) {
        echo json_encode(['success' => true, 'newLabel' => 'Follow', 'newClass' => 'follow']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to unfollow user']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

pg_close($dbcon);
?>
