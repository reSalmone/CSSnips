<?php
    //qua in pratica con session_start() pija le info dell'ultima sessione da un file che si è salvato
    session_start();
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
    <div class="navbar">
        <div class="left-navbar">
            <a href="index.php" class="navbar-title">CSSnips</a>
            <div class="nbutton-div">
                <button class="nbutton" onclick="location.href='explorer.html'" type="button">
                    <span>Explore</span>
                    <img src="assets/search.png" class="nicon">
                </button>
                <button class="nbutton" onclick="location.href = 'challenges.html'" type="button">
                    <span>Challenges</span>
                    <img src="assets/target.png" class="nicon">
                </button>
                <button class="nbutton" onclick="location.href = 'test.html'" type="button">
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
                        echo "<button class='nbutton' type='button' onclick='location.href=\"logout.php\";'>
                                <span>Logout (" . $_SESSION['username'] . ")</span>
                            </button>";
                    } else {
                        echo '<button class="nbutton" type="button" onclick="openLogin();">
                                <span>Login</span>
                            </button>';
                        echo '<button class="nbutton" type="button" onclick="openSignup();">
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
                            openLogin();
                            showLoginServerError();
                        });
                    </script>";
                    unset($_SESSION['login_error']);
                }
            ?>
        </div>
        <form action="login.php" method="POST" class="form-form" onsubmit="return submitLoginForm(this);" novalidate>
            <div class="form-input-and-error-container">
                <div class="form-input-container">
                    <input type="text" class="form-input" placeholder="Username or email" name="username" spellcheck="false">
                    <img src="assets/email.png" class="form-icon">
                </div>
                <div class="form-error-box">
                    <img src="assets/exclamation.png" class="form-icon form-error-icon">
                    <span class="form-error-text"></span>
                </div>
            </div>
            <div class="form-input-and-error-container">
                <div class="form-input-container">
                    <input type="password" class="form-input" placeholder="Password" name="password" spellcheck="false">
                    <img src="assets/lock.png" class="form-icon form-show-password" onclick="showPassword(this);" title="Show password">
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
        <p class="form-switch-form">Don't have an account? <span onclick="openSignup();">Signup</span></p>
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
                            openSignup();
                            showSignupServerError();
                        });
                    </script>";
                    unset($_SESSION['signup_error']);
                }
            ?>
        </div>
        <form action="signup.php" method="POST" class="form-form" onsubmit="return submitSignupForm(this);" novalidate>
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
                        <input type="email" class="form-input form-input-1" placeholder="Email" name="email" spellcheck="false">
                        <img src="assets/email.png" class="form-icon">
                    </div>
                    <input type="email" class="form-input form-input-2" placeholder="Confirm email" name="confirmEmail" spellcheck="false">
                    <div class="form-error-box">
                        <img src="assets/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
            </div>
            <div class="form-separator">
                <div class="form-input-and-error-container">
                    <div class="form-input-container">
                        <input type="password" class="form-input form-input-1" placeholder="Password" name="password" spellcheck="false">
                        <img src="assets/lock.png" class="form-icon form-show-password" onclick="showPassword(this);" title="Show password">
                    </div>
                    <input type="password" class="form-input form-input-2" placeholder="Confirm password" name="confirmPassword" spellcheck="false">
                    <div class="form-error-box">
                        <img src="assets/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
            </div>
            <input type="submit" class="form-button" value="Sign up">
        </form>
        <p class="form-switch-form">Already have an account? <span onclick="openLogin();">Login</span></p>
    </div>
    </div>
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
                    <a href="test.html" class="snippet-title">Click to start creating</a>
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