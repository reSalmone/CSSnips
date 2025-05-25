<?php
session_start();
$redirect = 'index.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnips - About Us</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="navbar.css"> <!-- Navigation bar [la barra in alto] -->
    <link rel="stylesheet" href="login-signup.css"> <!-- Login and signup -->
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css"> <!-- Font -->
    <link rel="stylesheet" href="footer.css">

</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <img src="assets/images/juan_il_cavallo.jpg" >
    </div>

    <?php include 'footer-code.php'; ?> <!--FOOTER-->
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/index.js"></script>
</html>