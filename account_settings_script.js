function validateEmail() {
  const emailInput = document.getElementById("email");
  const errorSpan = document.getElementById("email-error");
  const email = emailInput.value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailRegex.test(email)) {
    emailInput.classList.add("error");
    errorSpan.textContent = "Inserisci un indirizzo email valido.";
    return false; // blocca l'invio
  } else {
    emailInput.classList.remove("error");
    errorSpan.textContent = "";
    return true; // consente l'invio
  }
}

function validatePassword() {
  const password = document.getElementById("password");
  const confirm = document.getElementById("confirm-password");
  const passwordError = document.getElementById("password-error");
  const confirmError = document.getElementById("confirm-error");

  const passwordVal = password.value.trim();
  const confirmVal = confirm.value.trim();

  const policyRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

  let valid = true;

  // Controllo policy password
  if (!policyRegex.test(passwordVal)) {
    password.classList.add("error");
    passwordError.textContent = "La password deve avere almeno 8 caratteri, con maiuscole, minuscole, numeri e simboli.";
    valid = false;
  } else {
    password.classList.remove("error");
    passwordError.textContent = "";
  }

  // Controllo conferma
  if (passwordVal !== confirmVal) {
    confirm.classList.add("error");
    confirmError.textContent = "Le password non corrispondono.";
    valid = false;
  } else {
    confirm.classList.remove("error");
    confirmError.textContent = "";
  }

  return valid; // se false, blocca l'invio
}
