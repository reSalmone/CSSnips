let loginLoading = false;
function submitLoginForm(event, redirect) {
    event.preventDefault();
    if (!loginLoading) {
        let form = event.target;
        let error = false;

        let username = form.username;
        let password = form.password;

        hideError(username);
        hideError(password);

        if (username.value == "") {
            showError(username, "Username is required");
            error = true;
        }
        if (password.value == "") {
            showError(password, "Password is required");
            error = true;
        }
        if (error) {
            return;
        }

        loginLoading = true;
        document.getElementById("login-submit").style.background = "#ddd";
        document.getElementById("login-submit-text").style.display = "none";
        document.getElementById("login-submit-loader").style.display = "block";
        const formData = new FormData(form);

        fetch('login.php?redirect=' + redirect, {
            method: 'POST',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.href = data.redirect;
                } else {
                    showLoginServerError(data.error);
                }
            })
            .catch(error => {
                showLoginServerError(error + ' [this is a programming error, please send this error to the staff providing enough context]');
            })
            .finally(() => {
                loginLoading = false
                document.getElementById("login-submit").style.background = "#fff";
                document.getElementById("login-submit-text").style.display = "block";
                document.getElementById("login-submit-loader").style.display = "none";
            });
    }
}

function hideError(element) {
    element.style.border = "2px solid rgba(255, 255, 255, 0.3)";
    let errorBox = element.parentElement.parentElement.querySelector(".form-error-box");
    if (errorBox) {
        errorBox.querySelector(".form-error-text").textContent = "";
        errorBox.style.maxHeight = 0 + "px";
        errorBox.style.opacity = "0";
    }
}

function resetError(...elements) {
    elements.forEach(element => {
        if (element instanceof HTMLInputElement) {
            hideError(element);
        }
    });
}

function showError(element, errorText) {
    element.style.border = "2px solid rgba(255, 0, 0, 0.3)";
    let errorBox = element.parentElement.parentElement.querySelector(".form-error-box");
    if (errorBox) {
        errorBox.querySelector(".form-error-text").textContent += errorText + "\r\n";
        /*I add 1 to the scrollHeight because of weird behaviour AFTER I change stuff within it 
        (it adds a pixel (my guess is it's a floating point error)) so to fix I just expand it by that pixel*/
        errorBox.style.maxHeight = (errorBox.scrollHeight + 1) + "px";
        errorBox.style.opacity = "1";
    }
    //shakeElement(document.getElementById("login-page"), 500);
}

function shakeElement(element, duration) {
    element.classList.add('shake');

    setTimeout(() => {
        element.classList.remove('shake');
    }, duration);
}

function showPassword(icon) {
    var input = icon.parentElement.querySelectorAll(".form-input");
    input.forEach(i => {
        if (i.type === "password") {
            i.type = "text";
            icon.src = "assets/images/unlock.png";
        } else {
            i.type = "password";
            icon.src = "assets/images/lock.png";
        }
    });
}
function openLogin(event) {
    event.stopPropagation();
    resetErrorForAllForms();
    document.getElementById('signup-center-div').style.display = 'none';
    document.getElementById('login-center-div').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(30%)';
}

function closeLogin(event) {
    resetErrorForAllForms();
    document.getElementById('login-center-div').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}

function resetErrorForAllForms() {
    forms = document.getElementsByClassName("form-form");
    Array.from(forms).forEach(form => {
        resetError(...form.elements);
    });
}

function showLoginServerError(error) {
    let errorBox = document.getElementById("login-server-error");
    let errorSnip = document.createElement("span");
    errorSnip.textContent = error;
    errorBox.innerHTML = '';
    errorBox.appendChild(errorSnip);
    errorBox.style.display = "block";
}