let signupLoading = false;
function submitSignupForm(event, redirect) {
    event.preventDefault();
    if (!signupLoading) {
        let form = event.target;
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
            return;
        }

        signupLoading = true;
        document.getElementById("signup-submit").style.background = "#ddd";
        document.getElementById("signup-submit-text").style.display = "none";
        document.getElementById("signup-submit-loader").style.display = "block";
        const formData = new FormData(form);

        fetch('signup.php?redirect=' + redirect, {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.href = data.redirect;
                } else {
                    showSignupServerError(data.error);
                }
            })
            .catch(error => {
                showSignupServerError(error + ' [this is a programming error, please send this error to the staff providing enough context]');
            })
            .finally(() => {
                signupLoading = false
                document.getElementById("signup-submit").style.background = "#fff";
                document.getElementById("signup-submit-text").style.display = "block";
                document.getElementById("signup-submit-loader").style.display = "none";
            });
    }
}

function checkUsernameAvailability(input) {
    let checkname = input.value;

    fetch('checkusername.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `name=${encodeURIComponent(checkname)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayAvailability(data.success, null);
            } else {
                displayAvailability(data.success, data.error);
            }
        })
}

function displayAvailability(available, error) {
    let username = document.getElementById('username-input');
    if (available) {
        hideError(username);
        username.style.border = "2px solid rgb(100, 255, 100)";
    } else {
        hideError(username);
        showError(username, error);
        username.style.border = "2px solid rgb(255, 100, 100)";
    }
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

function openSignup(event) {
    event.stopPropagation();
    resetErrorForAllForms();
    document.getElementById('login-center-div').style.display = 'none';
    document.getElementById('signup-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
}

function closeSignup() {
    resetErrorForAllForms();
    document.getElementById('signup-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
    document.getElementById('username-input').style.border = "";
}

function showSignupServerError(error) {
    let errorBox = document.getElementById("signup-server-error");
    let errorSnip = document.createElement("span");
    errorSnip.textContent = error;
    errorBox.innerHTML = '';
    errorBox.appendChild(errorSnip);
    errorBox.style.display = "block";
}