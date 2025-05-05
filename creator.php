<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSSnip - Create</title>
    <link rel="stylesheet" href="creator.css">
    <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="login-signup.css">
    <link rel="stylesheet" href="checkbox.css"> <!-- Checkbox figa nel login -->
</head>

<body>
    <div class="navbar">
        <div class="left-navbar">
            <a href="index.php" class="navbar-title">CSSnips</a>
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
        <div class="right-navbar">
            <?php
            /*se trova in questa sessione un username settato significa che l'utente ha già una sessione attiva e vede logout,
            se no gli fa vedere il bottone login che runna openLogin() che è una funzione che sta nel file login.js che mostra
            il blocco con id #page da display: none; a display: block;*/
            if (isset($_SESSION['username'])) {
                echo "<button class='nbutton' type='button' onclick='location.href=\"logout.php?redirect=creator.php\";'>
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
            <form action="login.php?redirect=creator.php" method="POST" class="form-form"
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
            <form action="signup.php?redirect=creator.php" method="POST" class="form-form"
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
    <div class="center-div post-center-div" id="post-center-div">
        <div class="post-page">
            <div class="post-title-container">
                <span class="post-title">Snippet preview</span>
                <span class="post-subtitle">Confirm before submitting the post request</span>
            </div>
            <iframe id="post-preview"></iframe>
            <div class="post-info">
                <div class="post-name-container">
                    <div class="post-name-title-container">
                        <span class="post-name-title">Snippet's name</span>
                        <span class="post-name-subtitle">Add a name to the snippet to save it as unique key</span>
                        <span class="post-name-subtitle" style="font-style: italic; color: var(--color4-placeholder);">IMPORTANTE - Finché non avrò creato un sistema di certificazione fai attenzione a NON ripetere i nomi</span>
                    </div>
                    <div class="post-name-input-container">
                        <input type="text" class="post-name-input" id="post-name" spellcheck="false">
                        <img src="assets/search.png" class="post-name-icon">
                    </div>
                </div>
                <span id="post-type"></span>
                <div class="post-description-container">
                    <span class="post-description-title">Description</span>
                    <span id="post-description-content"></span>
                </div>
                <div class="post-tags-container">
                    <span class="post-tags-title">Snippet's tags</span>
                    <div id="post-tags-list"></div>
                </div>
            </div>
            <div class="post-actions">
                <button class="post-action-button" onclick="closePost();">Cancel</button>
                <button class="post-action-button" onclick="postSnippet();">Post</button>
            </div>
        </div>
    </div>

    <div id="rest" onclick="closeLogin(); closeSignup();">
        <div class="snippet-page">
            <div class="snippet-action-bar">
                <div class="left-action-buttons">
                    <div class="type-dropdown-div">
                        <button class="action-button" id="current-type"></button>
                        <div class="type-dropdown-container">
                            <div class="type-dropdown-content">
                                <button class="type-dropdown-button" onclick="setElementType(this, event);">Button</button>
                                <button class="type-dropdown-button" onclick="setElementType(this, event);">Switch</button>
                                <button class="type-dropdown-button" onclick="setElementType(this, event);">Input</button>
                                <button class="type-dropdown-button" onclick="setElementType(this, event);">Form</button>
                                <button class="type-dropdown-button" onclick="setElementType(this, event);">Card</button>
                                <button class="type-dropdown-button" onclick="setElementType(this, event);">Loader</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-action-buttons">
                    <div class="toggle-switch">
                        <label class="switch-label">
                            <input type="checkbox" class="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <button class="action-button" id="action-reset" onclick="resetSnippet();">Reset snippet</button>
                    <?php
                    $action = isset($_SESSION['username']) ? "saveDraft(event);" : "openLogin(event);";
                    echo '<button class="action-button" type="button" onclick="' . $action . '">Save draft</button>';
                    $action = isset($_SESSION['username']) ? "openPost(event);" : "openLogin(event);";
                    echo '<button class="action-button" type="button" onclick="' . $action . '">Post snippet</button>';
                    ?>
                </div>
            </div>
            <div class="snippet-container">
                <iframe id="output" class="output-container">

                </iframe>
                <div class="code-container">
                    <div class="code-buttons">
                        <div class="lang-buttons-container">
                            <button class="lang-button" id="html-button" onclick="switchLang('html')">HTML</button>
                            <button class="lang-button" id="css-button" onclick="switchLang('css')">CSS</button>
                            <button class="lang-button" id="js-button" onclick="switchLang('js')">JS</button>
                        </div>
                    </div>
                    <div class="code-div">
                        <button class="copy-button" onclick="copyToClipboard();">
                            <img src="copy.png" class="copy-icon">
                            <span id="copy-span">Copy</span>
                        </button>
                        <div class="line-numbers" id="line-numbers">
                            <div>1</div>
                        </div>
                        <textarea class="input-area" id="html-area" oninput="updateLines(this); displayCode()"
                            onscroll="syncScroll(this);" onkeydown="insertTab(event, this)" spellcheck="false"
                            placeholder="Html code"></textarea>
                        <textarea class="input-area" id="css-area" oninput="updateLines(this); displayCode();"
                            onscroll="syncScroll(this);" onkeydown="insertTab(event, this)" spellcheck="false"
                            placeholder="Css code"></textarea>
                        <textarea class="input-area" id="js-area" oninput="updateLines(this); displayCode();"
                            onscroll="syncScroll(this);" onkeydown="insertTab(event, this)" spellcheck="false"
                            placeholder="JavaScript code"></textarea>
                    </div>
                </div>
            </div>
            <div class="info-container">
                <div class="description-container">
                    <span class="description-title">Add a description</span>
                    <textarea class="description-area" id="description-area">This is my new element</textarea>
                </div>
                <div class="tags-container">
                    <div class="tags-title-container">
                        <span class="tags-title">Add tags to make the snip easier to find</span>
                        <span class="tags-subtitle">You are free to add up to 10 tags</span>
                    </div>
                    <div class="tags-input-container">
                        <input type="text" class="tags-input" id="tags-input" placeholder="Add a tag..."
                            spellcheck="false">
                        <button class="tags-add" onclick="addTag()">Add tag</button>
                    </div>
                    <span class="tags-subtitle">Current tags:</span>
                    <div id="tags-list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="assets/scripts/creator.js"></script>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>