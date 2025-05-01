<?php
session_start();
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
session_destroy();
header('Location: ' . $redirect);
exit();
?>