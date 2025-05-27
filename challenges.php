<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'challenges.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - Challenges</title>
    <link rel="stylesheet" href="data.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="challenges.css">
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
                    echo'<div class="contest-box" onclick="location.href = \'challenge_selected.php?name='.$tuple["name"].'\' " type="button">
                            <div class="contest-month">Challenge of the Month!</div>';
                    echo '<div class="contest-content">';
                    echo '<div class="contest-content-title">' . $tuple["name"] . '</div>';
                    echo '<div class="contest-content-subtitle">' . $tuple["description"] . '</div>';
                    echo '</div>'; ?>
                    <div class="contest-content-two">
                    <div class="data">
                        <div class="range">
                            <div class="fill" id="fill" style="width: <?php echo $fill; ?>%;"></div>
                        </div>
                        <p class="data-days"><?php
                        echo $diff->format('%ad %hh %im left');
                        ?></p>
                    </div>
                    <button class="actions-button" onclick="event.stopPropagation(); <?php echo 'location.href =\'creator.php?challenge='.urlencode($name) .'\''?>">
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
                </div>
                <?php
                }
            } else {
                echo '<p>Error connecting to databse</p>';
            }
        ?>
        <div class="other-challenges">
            <div class="contest-month">Other Challenges</div>
            <div class="challenge-container">
                <img src="assets/images/scroll-arrow-left.png" id='s-left' class="scroll-button left">
                <div id="s-carosello" class="carosello">
                    <?php
                    $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
                    if ($dbcon != -1) { //se la connessione è correttamente stabilita
                        $q1 = "SELECT * FROM challenges WHERE date_end < CURRENT_DATE;";
                        $result = pg_query($dbcon, $q1);
                        while ($tuple = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
                            echo'<div class="active-challenge-box" onclick="location.href =\'challenge_selected.php?name='.$tuple["name"].'\'">
                                    <div class="title-active-challenge-box">'.$tuple["name"].'</div>
                                    <div class="subtitle-active-challenge-box">Terminated</div>';

                            if($tuple["type"]=="Style"){
                                echo '<img src="'.$tuple["image"].'" class="background-active-challenge-box">';
                                }
                            else{
                                echo'<div class="contest-background-button">';
                                echo '<img src="'. $tuple["image"].'" class="nicon-target">';
                                echo '</div>';
                                }
                                echo '</div>';
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
<script src="assets/scripts/challenges.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>