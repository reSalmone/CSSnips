<?php
session_start();
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    loginError("Wrong request method.");
}

//se salvamo le info del POST in delle variabili (vuote se non se trovano)
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    loginError("Username and password are required.");
} else if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    loginError("Invalid username: usernames contain only letters numbers and underscores");
}

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or loginError("Connection to database refused");
if ($dbcon != -1) { //se la connessione è correttamente stabilita
    $q1 = "SELECT * from users where username ILIKE $1";
    $result = pg_query_params($dbcon, $q1, array($username));
    if ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $hashed_password = $tuple['password'];
        if (password_verify($password, hash: $hashed_password)) {
            $_SESSION['username'] = $tuple['username'];
            if (isset($_POST['remember'])) {
                $_SESSION['user'] = $username;
                $_SESSION['password'] = $password;
                $_SESSION['remember'] = true;
            } else {
                unset($_SESSION['user']);
                unset($_SESSION['password']);
                unset($_SESSION['remember']);
            }
            session_regenerate_id();
            header('Location: ' . $redirect);
            exit();
        } else {
            loginError("Incorrect password");
        }
    } else {
        loginError("Username not found");
    }
}

function loginError($error) {
    global $redirect;
    $_SESSION['login_error'] = "$error";
    header('Location: ' . $redirect);
    exit();
}
?>