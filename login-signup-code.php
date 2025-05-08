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
        <form action="login.php?redirect=<?php echo $redirect ?>" method="POST" class="form-form"
            onsubmit="return submitLoginForm(this);" novalidate>
            <div class="form-input-and-error-container">
                <div class="form-input-container">
                    <input type="text" class="form-input" placeholder="Username" name="username" spellcheck="false" value="<?php 
                        if (isset($_SESSION['remember'])) {
                            echo $_SESSION['user'];
                        }
                        ?>">
                    <img src="assets/images/user.png" class="form-icon">
                </div>
                <div class="form-error-box">
                    <img src="assets/images/exclamation.png" class="form-icon form-error-icon">
                    <span class="form-error-text"></span>
                </div>
            </div>
            <div class="form-input-and-error-container">
                <div class="form-input-container">
                    <input type="password" class="form-input" placeholder="Password" name="password" spellcheck="false" value="<?php 
                        if (isset($_SESSION['remember'])) {
                            echo $_SESSION['password'];
                        }
                        ?>">
                    <img src="assets/images/lock.png" class="form-icon form-show-password" onclick="showPassword(this);"
                        title="Show password">
                </div>
                <div class="form-error-box">
                    <img src="assets/images/exclamation.png" class="form-icon form-error-icon">
                    <span class="form-error-text"></span>
                </div>
            </div>
            <div class="form-remember-forgot">
                <label class="checkbox-container">
                    <input type="checkbox" class="checkbox" id="checkbox" name="remember" <?php 
                    if (isset($_SESSION['remember'])) {
                        echo 'checked';
                    }
                    ?>>
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
        <form action="signup.php?redirect=<?php echo $redirect ?>" method="POST" class="form-form"
            onsubmit="return submitSignupForm(this);" novalidate>
            <div class="form-input-and-error-container">
                <div class="form-input-container">
                    <input type="text" class="form-input" placeholder="Username" name="username" spellcheck="false">
                    <img src="assets/images/user.png" class="form-icon">
                </div>
                <div class="form-error-box">
                    <img src="assets/images/exclamation.png" class="form-icon form-error-icon">
                    <span class="form-error-text"></span>
                </div>
            </div>
            <div class="form-separator">
                <div class="form-input-and-error-container">
                    <div class="form-input-container">
                        <input type="email" class="form-input form-input-1" placeholder="Email" name="email"
                            spellcheck="false">
                        <img src="assets/images/email.png" class="form-icon">
                    </div>
                    <input type="email" class="form-input form-input-2" placeholder="Confirm email" name="confirmEmail"
                        spellcheck="false">
                    <div class="form-error-box">
                        <img src="assets/images/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
            </div>
            <div class="form-separator">
                <div class="form-input-and-error-container">
                    <div class="form-input-container">
                        <input type="password" class="form-input form-input-1" placeholder="Password" name="password"
                            spellcheck="false">
                        <img src="assets/images/lock.png" class="form-icon form-show-password" onclick="showPassword(this);"
                            title="Show password">
                    </div>
                    <input type="password" class="form-input form-input-2" placeholder="Confirm password"
                        name="confirmPassword" spellcheck="false">
                    <div class="form-error-box">
                        <img src="assets/images/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
            </div>
            <input type="submit" class="form-button" value="Sign up">
        </form>
        <p class="form-switch-form">Already have an account? <span onclick="openLogin(event);">Login</span></p>
    </div>
</div>