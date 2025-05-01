<?php
session_start();
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    signUpError("Wrong request method.");
}

//se salvamo le info del POST in delle variabili (vuote se non se trovano)
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or signUpError("Connection to database refused");
if ($dbcon) { //se la connessione è correttamente stabilita
    $q1 = "SELECT * from users where username = $1";
    $q2 = "SELECT * from users where email = $1";

    $resultUsername = pg_query_params($dbcon, $q1, array($username));
    $tupleUsername = pg_fetch_array($resultUsername, null, PGSQL_ASSOC);
    if ($tupleUsername) {
        signUpError("Username already exists");
    }

    $resultEmail = pg_query_params($dbcon, $q2, array($email));
    $tupleEmail = pg_fetch_array($resultEmail, null, PGSQL_ASSOC);
    if ($tupleEmail) {
        signUpError("Email already exists");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $q3 = "insert into users (username, email, password) values ($1, $2, $3)";
    $data = pg_query_params($dbcon, $q3, array($username, $email, $hashed_password));
    if (!$data) {
        signUpError("Error during registration process");
    }

    $_SESSION['username'] = $username;
    session_regenerate_id();
    header('Location: $redirect');
    exit();
}

function signUpError($error) {
    global $redirect;
    $_SESSION['signup_error'] = "$error";
    header('Location: ' . $redirect);
    exit();
}
?>