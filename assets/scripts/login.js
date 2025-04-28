function submitLoginForm(form) {
    let error = false;

    let username = form.username;
    let password = form.password;

    hideError(username);
    hideError(password);

    if (username.value == "") {
        showError(username, "Username or email is required");
        error = true;
    }
    if (password.value == "") {
        showError(password, "Password is required");
        error = true;
    }
    if (error) {
        //shakeElement(document.getElementById("page"), 500);
    }
    return !error;
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
}

function shakeElement(element, duration) {
    element.style.animation = `shake ${duration}ms`;
    setTimeout(() => {
        element.style.animation = "";
    }, duration);
}

function showPassword(icon) {
    var input = icon.parentElement.querySelectorAll(".form-input");
    input.forEach(i => {
        if (i.type === "password") {
        i.type = "text";
        icon.src="assets/unlock.png";
    } else {
        i.type = "password";
        icon.src="assets/lock.png";
    }
    });
}
function openLogin() {
    document.getElementById('signup-page').style.display = 'none';
    document.getElementById('login-page').style.display = 'block';
    document.getElementById('rest').style.filter = 'brightness(50%)';
}

function closeLogin() {
    document.getElementById('login-page').style.display = 'none';
    document.getElementById('rest').style.filter = 'brightness(100%)';
}