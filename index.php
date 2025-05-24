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
                        <div class="slideshow-item">forza</div>
                        <div class="slideshow-item">magica</div>
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
                    <span class="activec-box-title">Challenge of the Month</span>
                    <?php
                    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
                    if ($dbcon != -1) {
                        $q1 = "SELECT * FROM challenges WHERE date_end >= CURRENT_DATE;";
                        $result = pg_query($dbcon, $q1);
                        if ($tuple = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                            $dataf = new DateTime($tuple['date_end']);
                            $datag = new DateTime();
                            $diff = $datag->diff($dataf);
                            $fill = $diff->format('%a') * 3.22;
                            $name = $tuple["name"];
                            echo'<div class="activec-box" onclick="location.href = \'challenge_selected.php?name='.$name.'\' " type="button">
                                    <div class="contest-month">'. $name .'</div>';
                            echo '<div class="contest-content">';
                            ?>
                            <div class="data">
                                <div class="range">
                                    <div class="fill" id="fill" style="width: <?php echo $fill; ?>%;"></div>
                                </div>
                                <p class="data-days"><?php echo $diff->format('%ad %hh %im left'); ?></p>
                            </div>
                            <button class="actions-button" onclick="location.href ='creator.php'">
                                <div class='actions-svg'>
                                    <svg  viewBox="0 0 512.000000 512.000000">
                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                    fill="#efffe1" stroke="none">
                                    <path d="M2305 5113 c-671 -72 -1279 -393 -1707 -903 -301 -358 -488 -764 -571 -1240 -19 -109 -21 -162 -21 -410 0 -248 2 -301 21 -410 94 -540 320 -987 693 -1374 383 -398 866 -650 1430 -749 109 -19 162 -21 410 -21 313 0 407 10 653 73 881 228 1600 947 1828 1828 63 246 73 340 73 653 0 248 -2 301 -21 410 -99 564 -352 1047 -749 1430 
                                            -386 372 -837 600 -1368 691 -97 17 -172 22 -376 24 -140 2 -273 1 -295 -2z m565 -418 c486 -78 869 -273 1210 -615 298 -297 494 -648 584 -1040 70 -309 70 -651 0 -960 -121 -532 -453 -1006 -931 -1328 -184 -124 -463 -243 -693 -296 -309 -70 -651 -70 -960 0 -532 121 -1006 453 -1328 931 -124 184 -243 463 -296 693 -70 309 -70 651 0 960 90 392 286 743 584 1040 377 378 808 578 1365 634 79 8 372 -4 465 -19z"/>
                                    <path d="M2470 3748 c-24 -13 -58 -40 -74 -61 l-31 -39 -3 -434 -3 -434 -417 0 c-414 0 -418 0 -462 -22 -24 -13 -57 -40 -74 -61 -26 -32 -31 -49 -34 -105 -5 -88 23 -144 93 -187 l48 -30 424 -3 423 -3 0 -418 0 -418 23 -44 c73 -137 281 -137 354 0 l23 44 0 418 0 418 423 3 424 3 48 30 c70 43 98 99 93 187 -3 56 -8 73 -34 105 -17 21 -50 48 -74 61 -44 22 -48 22 -462 22 l-417 0 -3 434 -3 434 -31 39 c-39 50 -105 83 -164 83 -28 0 -63 -9 -90 -22z"/>
                                    </g>
                                    </svg>
                                </div>
                                <span>Create</span>
                            </button>
                            </div>
                            <div class="contest-background-target">
                                <?php echo '<img src="'. $tuple["image"].'" class="nicon-target">'?>
                            </div>
                            <?php
                            echo '</div>';
                             }}else {echo '<p>Error connecting to databse</p>';} ?>
                </div>
                <img src="assets/images/up-arrow.png" class="activec-arrow">
                <div class="activec-container">
                    <span class="activec-box-title">Challenge Snips</span>
                    <div class="activec-snips-box">
                        <div class="slidesnip">
                            <?php for ($i = 0; $i < 3; $i++) { ?>
                            <div class="slidesnip-object-left">
                                <div class="search-output">
                                <?php
                                    if ($dbcon != -1) { //se la connessione è correttamente stabilita
                                        $q2 = "SELECT * FROM snips_with_likes WHERE challenge_of='$name' AND challenge_likes IS NOT NULL ORDER BY challenge_likes DESC";
                                        $result2 = pg_query($dbcon, $q2);
                                        while ($tuple = pg_fetch_array($result2, NULL, PGSQL_ASSOC)) {
                                            $fileLocation = $tuple['file_location'];
                                            if (file_exists(__DIR__ . "\\snippets\\" . $fileLocation)) {
                                                $fileContent = file_get_contents(filename: __DIR__ . "\\snippets\\" . $fileLocation); //search for the file in the server
                                
                                                list($html, $css, $js) = splitFileContent($fileContent); //split file content into html, css, js
                                                
                                                echo '<div class="output-snip">';
                                                echo '<div class= "snip-info">';
                                                $snip_likes=$tuple['challenge_likes'];
                                                $challenge_name=$tuple['challenge_of'];
                                                $snip_name=$tuple['id'];
                                                echo '</div>';
                                                echo '<div class="output-snip-opener" onclick="location.href = \'snippet.php?name=' . $fileLocation . '\';">';
                                                echo '<span>View code</span>';
                                                echo '</div>';
                                                echo '<iframe id="output-snip-frame-' . $tuple['id'] . '" class="output-preview"></iframe>';
                                                /*here the strat is to create a js snippet containing the json data of the file divided into html css and js
                                                and then retrieve it with another script that finds the first one and parses it into a js json object, to then place it inside the iframe*/
                                                echo '<script id="snippet-data-' . $tuple['id'] . '" type="application/json">';
                                                echo json_encode(['html' => $html, 'css' => $css, 'js' => $js], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                                                echo '</script>';

                                                echo '<script>
                                                    document.addEventListener("DOMContentLoaded", function() {
                                                        const data = JSON.parse(document.getElementById("snippet-data-' . $tuple['id'] . '").textContent);
                                                        assignIFrame("output-snip-frame-' . $tuple['id'] . '", data.html, data.css, data.js);
                                                    });
                                                </script>';
                                                echo '<div class="info">';
                                                echo '<div class="info-creator">';
                                                echo '<div class="info-pfp"></div>';
                                                echo '<span>' . htmlspecialchars($tuple['creator']) . '</span>';
                                                echo '</div>';
                                                echo '<div class="info-views">';
                                                echo '<p class="info-text">' . htmlspecialchars($tuple['views']);
                                                echo '<p class="info-subtext"> views</p>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                            } else {
                                                echo 'Your server files aren\' synched with the database: file \'' . $fileLocation . '\' is missing';
                                            }
                                        }
                                    } else {
                                        echo '<p>Error connecting to databse</p>';
                                    }
                                ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
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