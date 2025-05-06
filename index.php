<!--SE STAI VEDENDO QUESTO CODICE SUL BROWSER SIGNIFICA CHE NON HAI APERTO IL SERVER LOCALHOST CON CUI VEDERLO:
1: scarica l'estenzione vscode 'PHP server'
2: tasto destro in index.php (nel codice)
3: clicca PHP Server: serve project
ora dovrebbe funge <3
-->


<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'index.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnips - Home</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="navbar.css"> <!-- Navigation bar [la barra in alto] -->
    <link rel="stylesheet" href="login-signup.css"> <!-- Login and signup -->
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css"> <!-- Font -->
</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="first-container">
            <div class="title-container">
                <span class="title">CSSnips</span>
                <span class="subtitle">Free open source snippet library to<br>easily create and share HTML/CSS code
                    instantly</span>
                <img src="assets/up-arrow.png" class="scroll-arrow">
            </div>
            <canvas id="first-container-canvas"></canvas>
        </div>
        <div class="second-container">
            <div class="title-container">
                <span class="secondary-title">Try out our new code editor</span>
            </div>
            <div class="snippet">
                <div class="preview-box">
                    <a href="creator.php" class="snippet-title">Click to start creating</a>
                </div>
                <div class="code-box">

                </div>
            </div>
        </div>
        <div class="third-container">
            <div class="title-container">
                <span class="secondary-title">Explore hundreds of open-source elements</span>
                <span class="secondary-subtitle">Search through them with our tag system</span>
            </div>
            <div class="slideshow-container">
                <div class="slideshow"></div>
                <div class="slideshow"></div>
            </div>
        </div>
        <div class="fourth-container">
            <div class="title-container">
                <span class="secondary-title">UI challenges</span>
                <span class="secondary-subtitle">Participate / vote in challenging UI prompts</span>
            </div>
            <div class="activec">
                <div class="activec-container">
                    <span class="activec-box-title">Active challenge</span>
                    <div class="activec-box">
                        <span class="activec-image"></span>
                        <span class="activec-title"></span>
                    </div>
                </div>
                <img src="assets/up-arrow.png" class="activec-arrow">
                <div class="activec-container">
                    <span class="activec-box-title">Active challenge submissions</span>
                    <div class="activec-box">

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="assets/scripts/title-wave2.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>