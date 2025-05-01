<?php
//qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnips - Explorer</title>
    <link rel="stylesheet" href="explorer.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
    <link rel="stylesheet" href="navbar.css">
</head>

<body>
    <div class="navbar">
        <div class="left-navbar">
            <a href="index.php" class="navbar-title">CSSnips</a>
            <div class="nbutton-div">
                <button class="nbutton" onclick="location.href='explorer.php'" type="button">
                    <span>Explore</span>
                    <img src="assets/search.png" class="nicon">
                </button>
                <button class="nbutton" onclick="location.href = 'challenges.html'" type="button">
                    <span>Challenges</span>
                    <img src="assets/target.png" class="nicon">
                </button>
                <button class="nbutton" onclick="location.href = 'creator.php'" type="button">
                    <span>Create</span>
                    <img src="assets/add.png" class="nicon">
                </button>
            </div>
        </div>
        <div class="right-navbar">
            <div class="nbutton-div">
                <?php
                /*se trova in questa sessione un username settato significa che l'utente ha già una sessione attiva e vede logout,
                se no gli fa vedere il bottone login che runna openLogin() che è una funzione che sta nel file login.js che mostra
                il blocco con id #page da display: none; a display: block;*/
                if (isset($_SESSION['username'])) {
                    echo "<button class='nbutton' type='button' onclick='location.href=\"logout.php?redirect=explorer.php\";'>
                                <span>Logout (" . $_SESSION['username'] . ")</span>
                            </button>";
                } else {
                    echo '<button class="nbutton" type="button" onclick="openLogin(event);">
                                <span>Login</span>
                            </button>';
                    echo '<button class="nbutton" type="button" onclick="openSignup(event);">
                                <span>Signup</span>
                            </button>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="center-div" id="login-center-div">
        <div class="form-page" id="login-page">
            <p class="form-title">Login</p>
            <div class="form-server-error-container" id="login-server-error">
                <?php
                if (isset($_SESSION['login_error'])) {
                    echo "<span>" . $_SESSION['login_error'] . "</span>";
                    echo "<script>
                        window.addEventListener('load', function() {
                            openLogin(event);
                            showLoginServerError();
                        });
                    </script>";
                    unset($_SESSION['login_error']);
                }
                ?>
            </div>
            <form action="login.php?redirect=explorer.php" method="POST" class="form-form"
                onsubmit="return submitLoginForm(this);" novalidate>
                <div class="form-input-and-error-container">
                    <div class="form-input-container">
                        <input type="text" class="form-input" placeholder="Username or email" name="username"
                            spellcheck="false">
                        <img src="assets/email.png" class="form-icon">
                    </div>
                    <div class="form-error-box">
                        <img src="assets/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
                <div class="form-input-and-error-container">
                    <div class="form-input-container">
                        <input type="password" class="form-input" placeholder="Password" name="password"
                            spellcheck="false">
                        <img src="assets/lock.png" class="form-icon form-show-password" onclick="showPassword(this);"
                            title="Show password">
                    </div>
                    <div class="form-error-box">
                        <img src="assets/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
                <div class="form-remember-forgot">
                    <label class="checkbox-container">
                        <input type="checkbox" class="checkbox" id="checkbox" name="remember">
                        <div class="display-checkbox"></div>
                        <label class="checkbox-label remember" for="checkbox">Remember me</label>
                    </label>
                    <a class="form-forgot-password" href="">Forgot password?</a>
                </div>
                <input type="submit" class="form-button" value="Login">
            </form>
            <p class="form-switch-form">Don't have an account? <span onclick="openSignup(event);">Signup</span></p>
        </div>
    </div>
    <div class="center-div" id="signup-center-div">
        <div class="form-page" id="signup-page">
            <p class="form-title">Sign up</p>
            <div class="form-server-error-container" id="signup-server-error">
                <?php
                if (isset($_SESSION['signup_error'])) {
                    echo "<span>" . $_SESSION['signup_error'] . "</span>";
                    echo "<script>
                        window.addEventListener('load', function() {
                            openSignup(event);
                            showSignupServerError();
                        });
                    </script>";
                    unset($_SESSION['signup_error']);
                }
                ?>
            </div>
            <form action="signup.php?redirect=explorer.php" method="POST" class="form-form"
                onsubmit="return submitSignupForm(this);" novalidate>
                <div class="form-input-and-error-container">
                    <div class="form-input-container">
                        <input type="text" class="form-input" placeholder="Username" name="username" spellcheck="false">
                        <img src="assets/user.png" class="form-icon">
                    </div>
                    <div class="form-error-box">
                        <img src="assets/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
                <div class="form-separator">
                    <div class="form-input-and-error-container">
                        <div class="form-input-container">
                            <input type="email" class="form-input form-input-1" placeholder="Email" name="email"
                                spellcheck="false">
                            <img src="assets/email.png" class="form-icon">
                        </div>
                        <input type="email" class="form-input form-input-2" placeholder="Confirm email"
                            name="confirmEmail" spellcheck="false">
                        <div class="form-error-box">
                            <img src="assets/exclamation.png" class="form-icon form-error-icon">
                            <span class="form-error-text"></span>
                        </div>
                    </div>
                </div>
                <div class="form-separator">
                    <div class="form-input-and-error-container">
                        <div class="form-input-container">
                            <input type="password" class="form-input form-input-1" placeholder="Password"
                                name="password" spellcheck="false">
                            <img src="assets/lock.png" class="form-icon form-show-password"
                                onclick="showPassword(this);" title="Show password">
                        </div>
                        <input type="password" class="form-input form-input-2" placeholder="Confirm password"
                            name="confirmPassword" spellcheck="false">
                        <div class="form-error-box">
                            <img src="assets/exclamation.png" class="form-icon form-error-icon">
                            <span class="form-error-text"></span>
                        </div>
                    </div>
                </div>
                <input type="submit" class="form-button" value="Sign up">
            </form>
            <p class="form-switch-form">Already have an account? <span onclick="openLogin(event);">Login</span></p>
        </div>
    </div>





    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="title-container">
            <span class="title">Explorer</span>
            <span class="subtitle">Search for the perfect snippet to include in your project</span>
        </div>
        <form action="explorer.php" method="GET" class="search-form" id="search-form">
            <div class="search-bar">
                <input placeholder="Search for elements / tags / usernames" type="search" class="search-input"
                    name="search" spellcheck="false" <?php
                    if (isset($_GET['search'])) {
                        echo 'value="' . $_GET['search'] . '"';
                    }
                    ?>>
                <img src="assets/search.png" class="search-icon"
                    onclick="document.getElementById('search-form').submit();">
            </div>
        </form>
        <div class="search-output-div">
            <?php
            $search = $_GET['search'] ?? '';
            $dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or -1;
            if ($dbcon != -1) { //se la connessione è correttamente stabilita
                $q1 = "SELECT * FROM snips";
                $result = pg_query($dbcon, $q1);
                if ($search != '') {
                    $q1 = "SELECT *,
                        CASE
                            WHEN EXISTS (
                                SELECT 1 FROM unnest(tags) AS tag
                                WHERE tag ILIKE $1
                            ) THEN 3
                            WHEN element_type ILIKE $1 THEN 2
                            WHEN creator ILIKE $1 THEN 1
                            ELSE 0
                        END AS relevance
                        FROM snips
                        WHERE EXISTS (
                            SELECT 1 FROM unnest(tags) AS tag
                            WHERE tag ILIKE $1
                        )
                        OR element_type ILIKE $1
                        OR creator ILIKE $1
                        ORDER BY relevance DESC;";
                    $result = pg_query_params($dbcon, $q1, array($search));
                }
                echo '<p class="search-results">' . pg_num_rows($result) . ' results</p>';
                if (pg_num_rows($result) > 0) {
                    echo '<div class="search-output">';
                    while ($tuple = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                        echo '<div class="output-snip">';
                        echo '<iframe id="output-snip-frame-' . $tuple['id'] . '" class="output-preview"></iframe>';
                        echo '<script>
                            window.addEventListener("load", function() {
                                assignIFrame("output-snip-frame-' . $tuple['id'] . '", "' . $tuple['html'] . '", "' . $tuple['css'] . '", "' . htmlspecialchars($tuple['js']) . '");
                            });
                        </script>';
                        echo '<div class="info">';
                        echo '<span>' . htmlspecialchars($tuple['creator']) . '</span>';
                        echo '<p>' . htmlspecialchars($tuple['views']) . ' views</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>Error connecting to databse</p>';
            }



            ?>
        </div>
    </div>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>
<script src="assets/scripts/explorer.js"></script>
</html>