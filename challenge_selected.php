<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();


if (!isset($_GET['name'])) {
    header('Location: challenges.php');
}

$name = $_GET['name'] ?? '';
$redirect = 'challenge_selected.php';

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

if ($name != '') {
    $redirect = $redirect . '?name=' . $name;
}
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
$is_challenge_active= false;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - Challenge</title>
    <link rel="icon" href="assets/images/icon.png">
    <link rel="stylesheet" href="data.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="challenge_selected.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">

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
        <div class="contest-box">

            <?php
            if ($dbcon != -1) { //se la connessione è correttamente stabilita
                
                $q1 = "SELECT * FROM challenges WHERE name='$name';";
                $result = pg_query($dbcon, $q1);
                if ($tuple = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                    $dataf = new DateTime(datetime: $tuple['date_end']);
                    $datag = new DateTime();
                    $diff = $datag->diff(targetObject: $dataf);
                    $fill = $diff->format('%a') * 3.22;
                    $is_challenge_active= $datag<=$dataf;
                    echo'<div class="contest-info-box">';
                    if($is_challenge_active){
                        echo '<div class="contest-title">Challenge of the Month!</div>';
                    }else{
                        echo '<div class="contest-title">'.$name.' Challenge!</div>';
                    };
                    echo '<div class="contest-content">';
                    echo '<div class="contest-content-title">'. $tuple["name"] . '</div>';
                    echo '<div class="contest-content-subtitle">' . $tuple["description"] . '</div>';
                    echo '</div>';?>
                    <div class="contest-content-two">
                    <div class="data">
                        <?php if($is_challenge_active){
                            echo '<div class="range">';
                            echo '<div class="fill" id="fill" style="width:'.$fill.'%;"></div>';
                            echo '</div>';
                            echo '<p class="data-days">' .$diff->format('%ad %hh %im left').'</p>';
                        }else{
                            echo '<div class="range">';
                            echo '<div class="fill" id="fill" style="width:100%; background-color: var(--color5);"></div>';
                            echo '</div>';
                            echo '<p class="data-days" style="color: var(--color5);">Terminated '.$diff->format('%a days ago').'</p>';
                        }
                        ?>
                    </div>
                    <?php if($is_challenge_active){
                    echo '<button class="create-button" onclick="location.href =\'creator.php?challenge=' . urlencode($name) . '\'">
                            <div class=\'create-svg\'>
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
                    </button>';
                    }
                    ?>
                    </div>
                    <!--<div class="data-views-checkmark">
                        <svg viewBox='0 0 256 256'>
                                        <path
                                            d='M127.2 53C79.7 53 39.5 84.1 26.0 127.2 39.5 170.2 79.7 201.4 127.2 201.4 174.6 201.4 214.8 170.2 228.3 127.2 214.8 84.1 174.6 53 127.2 53Z M159.0 127.2C159.0 144.7 144.7 159 127.2 159 109.6 159 95.4 144.7 95.4 127.2 95.4 109.6 109.6 95.4 127.2 95.4 144.7 95.4 159.0 109.6 159.0 127.2Z'
                                            stroke-width='20px' fill='none'></path>
                                    </svg>
                    </div>-->
                    <!-- <img src="assets/images/trophy.png" class="nicon-trophy"> -->
                    <div class="contest-background-target">
                        <?php echo '<img src="'. $tuple["image"].'" class="nicon-target">'?>
                    </div>
                </div>
                <?php
                }
            } else {
                echo '<p>Error connecting to databse</p>';
            }
            ?>
            <div class="search-output-div">
                <?php
                    if ($dbcon != -1) { //se la connessione è correttamente stabilita
                        $q2 = "SELECT * FROM snips_with_likes WHERE challenge_of='$name' AND challenge_likes IS NOT NULL ORDER BY challenge_likes DESC";
                        $result2 = pg_query($dbcon, $q2);
                        echo '<div class="search-output">';
                        $rank=1;
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
                                if ($is_challenge_active){
                                    
                                    echo '<label class="data-like-save-container">';      
                                    if (isset($_SESSION['username'])) {
                                        if ($dbcon != -1) {
                                            $q3 = "SELECT * from users where username ILIKE $1";
                                            $result3 = pg_query_params($dbcon, $q3, array($_SESSION['username']));
                                            if($tuple3=(pg_fetch_array($result3, null, PGSQL_ASSOC))){
                                                $user_id=$tuple3['id'];
                                                $q_check = "SELECT * FROM user_snip_likes WHERE user_id = $1 AND snip_id = $2";
                                                $result_check = pg_query_params($dbcon, $q_check, array($user_id, $snip_name));
                                                $is_checked=pg_num_rows($result_check);
                                                $checkValue = $is_checked > 0 ? "checked" : "";
                                                echo "<input type='checkbox' data-snippet='".$snip_name."' " . $checkValue ." class='snip-like-checkbox'>";
                                                $snip_likes=$tuple['challenge_likes'];
                                            }
                                        }
                                    }else{
                                        echo "<input type='checkbox' onclick='event.preventDefault(); event.stopPropagation(); openLogin(event);'>";
                                    }     
                                    echo   '<div class="like-svg">
                                                <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none">
                                                <path d="M8 10V20M8 10L4 9.99998V20L8 20M8 10L13.1956 3.93847C13.6886 3.3633 14.4642 3.11604 15.1992 3.29977L15.2467 3.31166C16.5885 3.64711 17.1929 5.21057 16.4258 6.36135L14 9.99998H18.5604C19.8225 9.99998 20.7691 11.1546 20.5216 12.3922L19.3216 18.3922C19.1346 19.3271 18.3138 20 17.3604 20L8 20" 
                                                stroke="#efffe1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                        </label>';
                                    echo '<p class="info-like" id="data-liked-value-'.$snip_name.'">'.$snip_likes.'</p>';
  
                                }
                                else{
                                    echo '<p class="info-like">'.$snip_likes.' vote'.'</p>';
                                    if($rank==1){
                                        echo '<div class="rank-svg">
                                                <svg viewBox="0 0 64 64" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Flat"> <g id="Color"> <polygon fill="#a60416" points="45 28 27 28 27 56 36 50 45 56 45 28"></polygon> 
                                                // <polygon fill="#a60416" points="19 28 37 28 37 56 28 50 19 56 19 28"></polygon> <polygon fill="#212529" points="41 33 23 33 23 61 32 55 41 61 41 33"></polygon> <path d="M23,42.82a11.22,11.22,0,0,1,2.65.23c2,.87,3.68,3,6.35,3s4.35-2.08,6.35-3A11.22,11.22,0,0,1,41,42.82V33H23Z" fill="#111315"></path> <polygon fill="#dd051d" points="37 33 37 58.33 32 55 27 58.33 27 33 37 33"></polygon> <path d="M27,43.81c3.65,2.88,6.36,2.88,10,0V33H27Z" fill="#a60416"></path>
                                                // <path d="M50.55,23.5c0-2.11,1.57-4.44,1-6.34S48.2,14.24,47,12.6s-1.3-4.48-3-5.69-4.35-.42-6.32-1S34.11,3,32,3s-3.83,2.24-5.73,2.86-4.68-.14-6.32,1-1.75,4-3,5.69-3.85,2.59-4.49,4.56.95,4.23.95,6.34-1.57,4.44-.95,6.34S15.8,32.76,17,34.4s1.3,4.48,3,5.69,4.35.42,6.32,1S29.89,44,32,44s3.83-2.24,5.73-2.86,4.68.14,6.32-1,1.75-4,3-5.69,3.85-2.59,4.49-4.56S50.55,25.61,50.55,23.5Z" fill="#fccd1d"></path> <circle cx="32" cy="23.5" fill="#f9a215" r="14.5"></circle> 
                                                // <path d="M33.37,16l1.52,2.63a1.54,1.54,0,0,0,1.06.76L39,20a1.53,1.53,0,0,1,.85,2.56l-2.1,2.22a1.5,1.5,0,0,0-.4,1.22l.36,3a1.57,1.57,0,0,1-2.22,1.58l-2.81-1.27a1.6,1.6,0,0,0-1.32,0l-2.81,1.27A1.57,1.57,0,0,1,26.31,29l.36-3a1.5,1.5,0,0,0-.4-1.22l-2.1-2.22A1.53,1.53,0,0,1,25,20l3-.59a1.54,1.54,0,0,0,1.06-.76L30.63,16A1.59,1.59,0,0,1,33.37,16Z" fill="#fccd1d"></path> </g> </g> </g></svg>
                                        </div>';
                                    }elseif($rank==2){
                                        echo '<div class="rank-svg">
                                            <svg viewBox="0 0 64 64" fill="#000000"><g stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Flat"> <g id="Color"> <polygon fill="#212529" points="45 17 32 25 19 17 19 3 45 3 45 17"></polygon> <polygon fill="#dd051d" points="40 3 40 20.08 32 25 24 20.08 24 3 40 3"></polygon> 
                                            <path d="M32,25l6.49-4a21.36,21.36,0,0,0-13,0Z" fill="#a60416"></path> <circle cx="32" cy="41.5" fill="#fccd1d" r="19.5"></circle> <circle cx="32" cy="41.5" fill="#f9a215" r="14.5"></circle> <path d="M33.88,33.57a6.49,6.49,0,0,0-5.81,1.23,6.41,6.41,0,0,0-2.21,4.89H30c0-2.24,3.37-2.38,4-1,1,2.1-8,7-8,7v4H38v-4H34a7.07,7.07,0,0,0,4-7.54A6.16,6.16,0,0,0,33.88,33.57Z" fill="#fccd1d"></path> </g> </g> </g></svg>
                                        </div>';
                                    }elseif($rank==3){
                                        echo '<div class="rank-svg">
                                            <svg viewBox="0 0 64 64" fill="#000000"><g stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="Flat"> <g id="Color"> <polygon fill="#212529" points="45 17 32 25 19 17 19 3 45 3 45 17"></polygon> <polygon fill="#dd051d" points="40 3 40 20.08 32 25 24 20.08 24 3 40 3"></polygon> <path d="M32,25l6.49-4a21.36,21.36,0,0,0-13,0Z" fill="#a60416">
                                            </path> <circle cx="32" cy="41.5" fill="#fccd1d" r="19.5"></circle> <circle cx="32" cy="41.5" fill="#f9a215" r="14.5"></circle>
                                            <path d="M36.54,41.5A4.52,4.52,0,0,0,38.38,38c0-2.76-2.86-5-6.38-5s-6.37,2.24-6.37,5h3.92a2,2,0,0,1,3.9-.29c.17,1.23-.77,2.73-2,2.73v2.12c2.22,0,2.84,3.5.72,4.32A2,2,0,0,1,29.55,45H25.63c0,2.76,2.85,5,6.37,5s6.38-2.24,6.38-5A4.52,4.52,0,0,0,36.54,41.5Z" fill="#fccd1d"></path> </g> </g> </g></svg>
                                        </div>';
                                    }
                                }
                                echo '</div>';
                                $rank = $rank+1;
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
                        echo '</div>';
                    } else {
                        echo '<p>Error connecting to databse</p>';
                    }
                    ?>
            </div>
        </div>
        <?php include 'footer-code.php'; ?> <!--FOOTER-->
    </div>
</body>
<script src="assets/scripts/challenge_selected.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/explorer.js"></script>
</html>


                    