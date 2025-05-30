<?php
session_start();
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
unset($_SESSION['username']);
header('Location: ' . $redirect);
exit();
?>