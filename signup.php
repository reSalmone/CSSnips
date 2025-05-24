<?php
header('Content-Type: application/json');
session_start();
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    signUpError("Wrong request method.");
}

//se salvamo le info del POST in delle variabili (vuote se non se trovano)
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$emailConfirm = $_POST['confirmEmail'] ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['confirmPassword'] ?? ''; 

if (empty($username) || empty($password) || empty($email) || empty($emailConfirm) || empty($passwordConfirm)) {
    signUpError("Registration form is not complete, it's missing 1 or more inputs");
} else if ($email != $emailConfirm) {
    signUpError("Email and email confirmation do not match");
} else if ($password != $passwordConfirm) {
    signUpError("Password and password confirmation do not match");
} else if (!preg_match('/^[A-Za-z0-9]{3,16}$/', $username)) {
    signUpError("Invalid username, must be:\n\t1: at least 3 characters\n\t2: not more than 16 characters");
} else if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    signUpError("Invalid email, use: 'test@example.net'");
} else if (!preg_match('/^(?=.*\d)[A-Za-z\d!@#$%^&*()_+={}\[\]:;<>,.?\/\\|-]{8,32}$/', $password)) {
    signUpError("Invalid password, must be:\n\t1: at least 8 characters\n\t2: not more than 16 characters\n\t3: at least 1 number");
}

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or signUpError("Connection to database refused");
if ($dbcon) { //se la connessione è correttamente stabilita
    $q1 = "SELECT * from users where username ILIKE $1";
    $q2 = "SELECT * from users where email ILIKE $1";

    $resultUsername = pg_query_params($dbcon, $q1, array($username));
    $tupleUsername = pg_fetch_array($resultUsername, null, PGSQL_ASSOC);
    if ($tupleUsername) {
        signUpError("Username already exists");
    }

    $resultEmail = pg_query_params($dbcon, $q2, array($email));
    $tupleEmail = pg_fetch_array($resultEmail, null, PGSQL_ASSOC);
    if ($tupleEmail) {
        signUpError("Email is already registered");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $q3 = "insert into users (username, email, password) values ($1, $2, $3)";
    $data = pg_query_params($dbcon, $q3, array($username, $email, $hashed_password));
    if (!$data) {
        signUpError("Error during registration process");
    }

    $_SESSION['username'] = $username;
    session_regenerate_id();
    echo json_encode(['success' => true, 'redirect' => $redirect]);
    exit();
}

function signUpError($error) {
    echo json_encode(['success' => false, 'error' => $error]);
    exit();
}
?>