<?php
//session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'challenges.php';

function splitFileContent($content){
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
    <title>CSSnip - Challenges</title>
    <link rel="icon" href="assets/images/icon.png">
    <link rel="stylesheet" href="css/data.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="css/login-signup.css">
    <link rel="stylesheet" href="css/checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="css/challenges.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">

</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="first-container">
            <div class="title-container">
                <img src="assets/images/target.png" class="nicon">
                <span class="title">Challenges</span>
            </div>
            <div class="subtitle-container">
                <span class="subtitle">Try to be the best CSSniper </span>
            </div>
        </div>
        <?php
            $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
            if ($dbcon != -1) { //se la connessione è correttamente stabilita
                $q1 = "SELECT * FROM challenges WHERE date_end >= CURRENT_DATE;";
                $result = pg_query($dbcon, $q1);

                if ($tuple = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {

                    $dataf = new DateTime($tuple['date_end']);
                    $datag = new DateTime();
                    $diff = $datag->diff($dataf);
                    $fill = $diff->format('%a') * 3.22;
                    $name=$tuple["name"];
                    $description=$tuple["description"];
                    $image=$tuple["image"];
        ?>
                    <div class="contest-box" onclick="location.href = 'challenge_selected.php?name=<?=$name?>' " type="button">
                        <div class="contest-month">Challenge of the Month!</div>
                            <div class="contest-content">
                                <div class="contest-content-title"><?=$name?></div>
                                <div class="contest-content-subtitle"><?=$description?></div>
                            </div>
                        <div class="contest-content-two">
                            <div class="data">
                                <div class="range">
                                    <div class="fill" id="fill" style="width: <?php echo $fill; ?>%;"></div>
                                </div>
                                <p class="data-days"><?=$diff->format('%ad %hh %im left')?></p>
                            </div>
                            <button class="actions-button" onclick="event.stopPropagation(); location.href ='creator.php?challenge=<?=urlencode($name)?>'">
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
                            <img src="<?=$image?>" class="nicon-target">
                        </div>
                    </div>
        <?php
                }
            } else {
                echo '<p>Error connecting to database</p>';
            }
        ?>
        <div class="mid-container">
            <div id="id-leaderboard-container" class="leaderboard-container">
                <div class="contest-month">Leaderboard</div>
                <div id="id-user-output" class="user-output">
        <?php
                    if ($dbcon != -1) { //se la connessione è correttamente stabilita
                        $users_query = "SELECT * FROM users_with_likes WHERE user_challenge_likes > 0 ORDER BY user_challenge_likes DESC;";
                        $users_list = pg_query($dbcon, $users_query);
                        $rank=0;
                        $color_rank="";
                        
                        while ($tuple_user = pg_fetch_array($users_list, NULL, PGSQL_ASSOC)) {
                            $rank+=1;
                            $users_name= $tuple_user["username"];
                            $avatar = "https://robohash.org/" . urlencode($users_name) . ".png?set=set1&bgset=bg1";
                            if ($rank == 1) $color_rank="style='border: 2px solid gold; box-shadow: 0px 0px 15px gold;'";
                            elseif ($rank == 2) $color_rank= "style='border: 2px solid silver; box-shadow: 0px 0px 15px silver;'";
                            elseif ($rank == 3) $color_rank="style='border: 2px solid sienna; box-shadow: 0px 0px 15px sienna;'";
                            else $color_rank= "";
        ?>                    
                            <div class="user-box" <?=$color_rank?> onclick="location.href = 'account.php?username=<?=$users_name?>'">
                                <div class="user-info-box">
                                    <div class="user-name-box"><?=$rank?>° </div>
                                    <div class="info-creator">
                                        <div class="avatar-div">
                                            <img src="<?= $avatar ?>" alt="Avatar" class="avatar-img">
                                        </div>
                                    </div>
                                    <div class="user-name-box"><?=$users_name?></div>
                                </div>
                                <div class="user-challenge-likes-box"><?=$tuple_user["user_challenge_likes"]?> votes</div>
                            </div>
        <?php
                        }
                    } else {
                        echo '<p>Error connecting to databse</p>';
                    }
        ?>
                </div>
                    
            </div>
            <div class="activec-snips-box">
                <div class="contest-month">Snips</div>
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
                                        $snip_likes=$tuple['challenge_likes'];
                                        $challenge_name=$tuple['challenge_of'];
                                        $snip_name=$tuple['id'];
                                        $snip_views=$tuple['views'];
                                        $snip_creator=$tuple['creator'];
                                        $avatar = "https://robohash.org/" . urlencode($snip_creator) . ".png?set=set1&bgset=bg1";
        ?>
                                        <div class="output-snip">
                                            <div class= "snip-info">
                                            <!-- aggiungere informazioni in caso -->
                                            </div>
                                            <div class="output-snip-opener" onclick="location.href = 'snippet.php?name=<?=$fileLocation?>';">
                                                <span>View code</span>
                                            </div>
                                            <iframe id="output-snip-frame-<?=$snip_name?>-<?=$i?>" class="output-preview"></iframe>
                                        
                                            <!--here the strat is to create a js snippet containing the json data of the file divided into html css and js
                                            and then retrieve it with another script that finds the first one and parses it into a js json object, to then place it inside the iframe-->
                                            <script id="snippet-data-<?=$snip_name?>-<?=$i?>" type="application/json">
                                            <?php echo json_encode(['html' => $html, 'css' => $css, 'js' => $js], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>
                                            </script>
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function() {
                                                    const data = JSON.parse(document.getElementById("snippet-data-<?= $snip_name?>-<?=$i?>").textContent);
                                                    assignIFrame("output-snip-frame-<?=$snip_name?>-<?=$i?>", data.html, data.css, data.js);
                                                });
                                            </script>
                                            <div class="info">
                                                <div class="info-creator">
                                                    <div class="avatar-div">
                                                        <img src="<?= $avatar ?>" alt="Avatar" class="avatar-img">
                                                    </div>
                                                    <span><?=htmlspecialchars($snip_creator)?></span>
                                                </div>
                                                <div class="info-views">
                                                    <p class="info-text"><?= htmlspecialchars($snip_views) ?></p>
                                                    <div class='data-views-checkmark'>
                                                        <svg viewBox='0 0 256 256'>
                                                            <path
                                                                d='M31.8 148.4c0-23.1928 28.62-74.2 95.4-74.2s95.4 51.0178 95.4 74.2m-63.6 0a31.8 31.8 90 11-63.6 0 31.8 31.8 90 0163.6 0Z'
                                                                stroke-width='20px' fill='none'></path>
                                                        </svg>
                                                    </div> 
                                                </div>
                                            </div>
                                        </div>
        <?php
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


        <div class="other-challenges">
            <div class="contest-month">Other Challenges</div>
            <div class="challenge-container">
                <img src="assets/images/scroll-arrow-left.png" id='s-left' class="scroll-button left">
                <div id="s-carosello" class="carosello">
        <?php
                    if ($dbcon != -1) { //se la connessione è correttamente stabilita
                        $q1 = "SELECT * FROM challenges WHERE date_end < CURRENT_DATE ORDER BY date_end DESC;";
                        $result_old = pg_query($dbcon, $q1);
                        while ($tuple_old = pg_fetch_array($result_old, NULL, PGSQL_ASSOC)) {
                            $name_old=$tuple_old["name"];
                            $description_old=$tuple_old["description"];
                            $image_old=$tuple_old["image"];
                            $type_old=$tuple_old["type"];
        ?>
                            <div class="active-challenge-box" onclick="location.href ='challenge_selected.php?name=<?=$name_old?>'" onmouseenter="mostraOverlay(this,event)" onmouseleave="nascondiOverlay(this,event)">
                                <div class="title-active-challenge-box"><?=$name_old?></div>
                                <div class="subtitle-active-challenge-box">Terminated</div>
                                <!-- CONTROLLO SE LA CHALLENGE è STYLE O BUTTON -->
                                <?php 
                                if($type_old=="Style"){
                                ?>
                                    <img src="<?=$image_old?>" class="background-active-challenge-box">
                                <?php
                                }else{
                                ?>
                                    <div class="contest-background-button">
                                        <img src="<?=$image_old?>" class="nicon-target-two">
                                    </div>
                                <?php 
                                } 
                                ?>
                                <div class="active-challenge-info"><?=$description_old?></div>
                            </div>
        <?php
                        }
                    } else {
                        echo '<p>Error connecting to databse</p>';
                    }
        ?>
                </div>
                <img src="assets/images/scroll-arrow-right.png" id='s-right' class="scroll-button right">
            </div>
        </div>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>

</body>

<!--SCRIPTS-->
<script src="scripts/challenges.js"></script>
<script src="scripts/login.js"></script>
<script src="scripts/signup.js"></script>
<script src="scripts/index.js"></script>
<script>
    window.addEventListener('load', () => {
        document.querySelectorAll('.slidesnip-object-left').forEach(el => {
            el.classList.add('slide-in');
        });
    });
</script>

</html>