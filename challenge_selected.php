<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
$redirect = 'challenges_selected.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - Challenge</title>
    <link rel="stylesheet" href="data.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="challenge_selected.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="navbar.css">

</head>

<body>
    <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
    <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="contest-box">

            <div class="contest-info-box">
                <div class="contest-title">Challenge of the Month!</div>
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
                    <?php
                }
            } else {
                echo '<p>Error connecting to databse</p>';
            }
            ?>
                <!--  <img src="assets/images/trophy.png" class="nicon-trophy">    -->
                <div class="contest-background-target">
                    <img src="assets/images/pixel_botton.png" class="nicon-target">
                </div>
            </div>
            <div class="challenge-container">
                <div class="carosello">
                    
                    <div class="active-challenge-box">
                        <div class="title-active-challenge-box">Button 1</div>
                    </div>
                    <div class="active-challenge-box">
                        <div class="title-active-challenge-box">Button 1</div>
                    </div>
                    <div class="active-challenge-box">
                        <div class="title-active-challenge-box">Button 1</div>
                    </div>
                    <div class="active-challenge-box">
                        <div class="title-active-challenge-box">Button 1</div>
                    </div>
                </div>
            </div>

        </div>
        
        

    </div>


</body>

<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
</html>