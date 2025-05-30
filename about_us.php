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
    <link rel="stylesheet" href="css/navbar.css"> <!-- Navigation bar [la barra in alto] -->
    <link rel="stylesheet" href="css/login-signup.css"> <!-- Login and signup -->
    <link rel="stylesheet" href="css/checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css"> <!-- Font -->
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/about_us.css">

</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div id="about-us" class="first-container">
            <div class="title-container">
                <span class="title">About us</span>
            </div>
            <div class="first-info-container">
                <h1 class="subtitle">We're a group of three brilliant minds, each with different passions, big dreams, and even bigger flaws.

 Emanuele is our five-a-side football mastermind, always ready to score... and talk a whole lot of nonsense. He bought an iPhone worth more than his wardrobe, which makes him an easy target for the other two Android slaves.

Livio is a natural-born coder, the kind who can write code with his eyes closed – unfortunately, he rarely opens them to study. He constantly loses at chess, but claims it's just to protect our egos. Sure, Livio. Sure.

Giuseppe is our in-house film critic: he believes buying Blu-rays for €100 is an "investment," even though streaming exists. But hey, he’s a beast at university and his grades prove it. So maybe he’s got a point?

Together, we’re an unlikely but tight-knit team, capable of building serious projects... even if we often sound like we’re characters from a sitcom.</h1>
            </div>
        </div>
        <div id="contact-us" class="second-container">
            <div class="title-container">
                <span class="title">Contact us</span>
            </div>
            <div class="second-info-container">
                <h1 class="subtitle"> Livio Boccongelli livioboccod@gmail.com</h1>
                <h1 class="subtitle"> Emanuele Cola emanuele.cola2003@gmail.com</h1>
                <h1 class="subtitle"> Giuseppe Ciccone giuseppeciccone2003@gmail.com</h1>
            </div>
        </div>
        <img src="assets/images/juan_il_cavallo.jpg" >
    </div>

    <?php include 'footer-code.php'; ?> <!--FOOTER-->
</body>
<script src="scripts/login.js"></script>
<script src="scripts/signup.js"></script>
<script src="scripts/index.js"></script>
</html>