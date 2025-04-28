<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    loginError("Wrong request method.");
}

//se salvamo le info del POST in delle variabili (vuote se non se trovano)
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or loginError("Connection to database refused");
if ($dbcon != -1) { //se la connessione è correttamente stabilita
    $q1 = "select * from users where username = $1";
    $result = pg_query_params($dbcon, $q1, array($username));
    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $hashed_password = $tuple['password'];
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $tuple['username'];
            session_regenerate_id();
            header('Location: index.php');
            exit();
        } else {
            loginError("Incorrect password");
        }
    } else {
        loginError("Username not found");
    }
}

function loginError($error) {
    $_SESSION['login_error'] = "$error";
    header("Location: index.php");
    exit();
}
?>