function submitSignupForm(form) {
    let error = false;

    let username = form.username;
    let email = form.email;
    let confirmEmail = form.confirmEmail;
    let password = form.password;
    let confirmPassword = form.confirmPassword;

    hideError(username);
    hideError(email);
    hideError(confirmEmail);
    hideError(password);
    hideError(confirmPassword);

    if (username.value == "") {
        showError(username, "Username is required")
        error = true;
    } else if (!isValidUsername(username.value)) {
        showError(username, "Invalid username, must be:\n\t1: at least 3 characters\n\t2: not more than 16 characters");
        error = true;
    }
    if (email.value == "") {
        showError(email, "Email is required");
        error = true;
    } else if (!isValidEmail(email.value)) {
        showError(email, "Invalid email, use: 'test@example.net'");
        error = true;
    }
    if (confirmEmail.value == "") {
        showError(confirmEmail, "Email confirmation is required");
        error = true;
    }
    if (confirmEmail.value != email.value) {
        showError(confirmEmail, "Email and email confirmation do not match");
        error = true;
    }
    if (password.value == "") {
        showError(password, "Password is required");
        error = true;
    } else if (!isValidPassword(password.value)) {
        showError(password, "Invalid password, must be:\n\t1: at least 8 characters\n\t2: not more than 16 characters\n\t3: at least 1 number");
        error = true;
    }
    if (confirmPassword.value == "") {
        showError(confirmPassword, "Password confirmation is required");
        error = true;
    }
    if (confirmPassword.value != password.value) {
        showError(confirmPassword, "Password and password confirmation do not match");
        error = true;
    }
    if (error) {
        //shakeElement(document.getElementById("page"), 500);
    }
    return !error;
}

function isValidEmail(email) {
    return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email);
}
function isValidPassword(password) {
    return /^(?=.*\d)[A-Za-z\d!@#$%^&*()_+={}\[\]:;<>,.?\/\\|-]{8,32}$/.test(password);
}
function isValidUsername(username) {
    return /^[A-Za-z0-9]{3,16}$/.test(username);
}

function openSignup() {
    document.getElementById('login-page').style.display = 'none';
    document.getElementById('signup-page').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(50%)';
}

function closeSignup() {
    document.getElementById('signup-page').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}