<!--SE STAI VEDENDO QUESTO CODICE SUL BROWSER SIGNIFICA CHE NON HAI APERTO IL SERVER LOCALHOST CON CUI VEDERLO:
1: scarica l'estenzione vscode 'PHP server'
2: tasto destro in index.php (nel codice)
3: clicca PHP Server: serve project
ora dovrebbe funge <3
-->


<?php
session_start();
$redirect = 'index.php';

function splitFileContent($content)
{
    preg_match('/<style>(.*?)<\/style>/s', $content, $matchesCss);
    preg_match('/<body>(.*?)<script>/s', $content, $matchesHtml);
    preg_match('/<script>(.*?)<\/script>/s', $content, $matchesJs);

    $css = isset($matchesCss[1]) ? trim($matchesCss[1]) : '';
    $html = isset($matchesHtml[1]) ? trim($matchesHtml[1]) : '';
    $js = isset($matchesJs[1]) ? trim($matchesJs[1]) : '';

    return [$html, $css, $js];
}
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
    <link rel="stylesheet" href="footer.css">

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
                <img src="assets/images/up-arrow.png" class="scroll-arrow">
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
                <div class="slideshow">
                    <?php for ($i = 0; $i < 3; $i++) { ?>
                    <div class="slideshow-object-right">
                        <div class="slideshow-item">button</div>
                        <div class="slideshow-item">rainbow</div>
                        <div class="slideshow-item">mac</div>
                        <div class="slideshow-item">form</div>
                        <div class="slideshow-item">input</div>
                        <div class="slideshow-item">grayscale</div>
                        <div class="slideshow-item">simple</div>
                        <div class="slideshow-item">input</div>
                        <div class="slideshow-item">colorful</div>
                        <div class="slideshow-item">windows</div>
                    </div>
                    <?php } ?>
                </div>
                <div class="slideshow">
                    <?php for ($i = 0; $i < 3; $i++) { ?>
                    <div class="slideshow-object-left">
                        <div class="slideshow-item">modern</div>
                        <div class="slideshow-item">pixel</div>
                        <div class="slideshow-item">minimal</div>
                        <div class="slideshow-item">delete</div>
                        <div class="slideshow-item">red</div>
                        <div class="slideshow-item">slider</div>
                        <div class="slideshow-item">hover</div>
                        <div class="slideshow-item">blue</div>
                        <div class="slideshow-item">circle</div>
                        <div class="slideshow-item">add</div>
                    </div>
                    <?php } ?>
                </div>
                <div class="slideshow">
                    <?php for ($i = 0; $i < 3; $i++) { ?>
                    <div class="slideshow-object-right">
                        <div class="slideshow-item">animated</div>
                        <div class="slideshow-item">gradient</div>
                        <div class="slideshow-item">radio</div>
                        <div class="slideshow-item">text</div>
                        <div class="slideshow-item">switch</div>
                        <div class="slideshow-item">bold</div>
                        <div class="slideshow-item">loader</div>
                        <div class="slideshow-item">checkbox</div>
                        <div class="slideshow-item">card</div>
                        <div class="slideshow-item">3d</div>
                    </div>
                    <?php } ?>
                </div>
                <div class="slideshow">
                    <?php for ($i = 0; $i < 3; $i++) { ?>
                    <div class="slideshow-object-left">
                        <div class="slideshow-item">tooltip</div>
                        <div class="slideshow-item">icon</div>
                        <div class="slideshow-item">green</div>
                        <div class="slideshow-item">svg</div>
                        <div class="slideshow-item">download</div>
                        <div class="slideshow-item">editor</div>
                        <div class="slideshow-item">2d</div>
                        <div class="slideshow-item">white</div>
                        <div class="slideshow-item">space</div>
                        <div class="slideshow-item">menu</div>
                    </div>
                    <?php } ?>
                </div>
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
                <img src="assets/images/up-arrow.png" class="activec-arrow">
                <div class="activec-container">
                    <span class="activec-box-title">Active challenge submissions</span>
                    <div class="activec-box">

                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="assets/scripts/title-wave2.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/index.js"></script>
</html>