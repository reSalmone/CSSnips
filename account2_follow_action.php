<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['username']) || !isset($_POST['username']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$my_username = $_SESSION['username'];
$username = $_POST['username'];
$action = $_POST['action'];

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");

if (!$dbcon) {
    echo json_encode(['success' => false, 'message' => 'Database connection error']);
    exit;
}

if ($action === 'follow') {
    $result = pg_query_params(
        $dbcon,
        "INSERT INTO follows (follower, following) VALUES ($1, $2) ON CONFLICT DO NOTHING",
        [$my_username, $username]
    );
    if ($result) {
        echo json_encode(['success' => true, 'newLabel' => 'Following', 'newClass' => 'following']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to follow user']);
    }
} elseif ($action === 'unfollow') {
    $result = pg_query_params(
        $dbcon,
        "DELETE FROM follows WHERE follower = $1 AND following = $2",
        [$my_username, $username]
    );
    if ($result) {
        echo json_encode(['success' => true, 'newLabel' => 'Follow', 'newClass' => 'follow']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to unfollow user']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
pg_close($dbcon);
?>
