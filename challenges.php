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
                    echo'<div class="contest-box" onclick="location.href = \'challenge_selected.php\' " type="button">
                            <div class="contest-month">Challenge of the Month!</div>';
                    echo '<div class="contest-content">';
                    echo '<div class="contest-content-title">' . $tuple["name"] . '</div>';
                    echo '<div class="contest-content-subtitle">' . $tuple["description"] . '</div>';
                    echo '</div>'; ?>
                    <div class="data">
                        <div class="range">
                            <div class="fill" id="fill" style="width: <?php echo $fill; ?>%;"></div>
                        </div>
                        <p class="data-days"><?php
                        echo $diff->format('%ad %hh %im left');
                        ?></p>
                    </div>
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
                            echo'<div class="active-challenge-box">
                                    <div class="title-active-challenge-box">'.
                                    $tuple["name"]
                                .'</div>
                                <img src="'.$tuple["image"].'" class="background-active-challenge-box">
                                </div>';
                        }
                    } else {
                        echo '<p>Error connecting to databse</p>';
                    }
                    ?>
                </div>
                <img src="assets/images/scroll-arrow-right.png" id='s-right' class="scroll-button right">
            </div>
        </div>
    </div>

</body>
<script src="assets/scripts/challenges.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>