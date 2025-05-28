<div class="center-div" id="login-center-div">
    <div class="form-page" id="login-page">
        <p class="form-title">Login</p>
        <div class="form-server-error-container" id="login-server-error"></div>
        <form class="form-form" onsubmit="submitLoginForm(event, '<?= urlencode($redirect) ?>');" novalidate>
            <div class="form-input-and-error-container">
                <div class="form-input-container">
                    <input type="text" class="form-input" placeholder="Username" name="username" spellcheck="false"
                        value="<?php
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
                    <input type="password" class="form-input" placeholder="Password" name="password" spellcheck="false"
                        value="<?php
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
                <p class="form-forgot-password">Forgot password?</>
            </div>
            <button type="submit" class="form-button" id="login-submit">
                <span id="login-submit-text">Login</span>
                <div class="submit-loader" id="login-submit-loader"></div>
            </button>
        </form>
        <p class="form-switch-form">Don't have an account? <span onclick="openSignup(event);">Signup</span></p>
    </div>
</div>
<div class="center-div" id="signup-center-div">
    <div class="form-page" id="signup-page">
        <p class="form-title">Sign up</p>
        <div class="form-server-error-container" id="signup-server-error"></div>
        <form class="form-form" onsubmit="submitSignupForm(event, '<?= urlencode($redirect) ?>');" novalidate>
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
                        <img src="assets/images/lock.png" class="form-icon form-show-password"
                            onclick="showPassword(this);" title="Show password">
                    </div>
                    <input type="password" class="form-input form-input-2" placeholder="Confirm password"
                        name="confirmPassword" spellcheck="false">
                    <div class="form-error-box">
                        <img src="assets/images/exclamation.png" class="form-icon form-error-icon">
                        <span class="form-error-text"></span>
                    </div>
                </div>
            </div>
            <button type="submit" class="form-button" id="signup-submit">
                <span id="signup-submit-text">Signup</span>
                <div class="submit-loader" id="signup-submit-loader"></div>
            </button>
        </form>
        <p class="form-switch-form">Already have an account? <span onclick="openLogin(event);">Login</span></p>
    </div>
</div>