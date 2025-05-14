function validateEmail() {
  const emailInput = document.getElementById("email");
  const errorSpan = document.getElementById("email-error");
  const email = emailInput.value.trim();
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailRegex.test(email)) {
    emailInput.classList.add("error");
    errorSpan.textContent = "Inserisci un indirizzo email valido.";
    return false;
  }

  emailInput.classList.remove("error");
  errorSpan.textContent = "";

  // Invia la nuova email al server
  fetch("update_email.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "email=" + encodeURIComponent(email)
  })
  .then(response => response.text())
  .then(data => {
    alert(data);  // puoi migliorare con un messaggio più elegante
  })
  .catch(error => {
    alert("Errore nella richiesta: " + error);
  });

  return false; // blocca il comportamento di default del form
}

function validatePassword() {
  const password = document.getElementById("password");
  const confirm = document.getElementById("confirm-password");
  const passwordError = document.getElementById("password-error");
  const confirmError = document.getElementById("confirm-error");

  const passwordVal = password.value.trim();
  const confirmVal = confirm.value.trim();

  const passwordRegex = /^(?=.*\d)[A-Za-z\d]{8,16}$/;

  let valid = true;

  // Validazione password
  if (!passwordRegex.test(passwordVal)) {
    password.classList.add("error");
    passwordError.textContent = "La password deve avere tra 8 e 16 caratteri e contenere almeno un numero.";
    valid = false;
  } else {
    password.classList.remove("error");
    passwordError.textContent = "";
  }

  // Validazione conferma
  if (passwordVal !== confirmVal) {
    confirm.classList.add("error");
    confirmError.textContent = "Le password non corrispondono.";
    valid = false;
  } else {
    confirm.classList.remove("error");
    confirmError.textContent = "";
  }

  if (!valid) return false;

  // Invia la nuova password al server
  fetch("update_password.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "password=" + encodeURIComponent(passwordVal)
  })
  .then(response => response.text())
  .then(data => {
    alert(data);  // messaggio di successo o errore
  })
  .catch(error => {
    alert("Errore nella richiesta: " + error);
  });

  return false; // blocca l'invio del form
}

function updateAccount() {
  const usernameInput = document.getElementById("username");
  const bioInput = document.getElementById("bio");

  const username = usernameInput.value.trim();
  const bio = bioInput.value.trim();

  if (username === "") {
    alert("Lo username non può essere vuoto.");
    return false;
  }

  fetch("update_account.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `username=${encodeURIComponent(username)}&bio=${encodeURIComponent(bio)}`
  })
  .then(response => response.text())
  .then(data => {
    alert(data); // Messaggio di successo o errore
    location.reload(); // Ricarica la pagina per aggiornare i dati
  })
  .catch(error => {
    alert("Errore durante l'invio: " + error);
  });

  return false; // Impedisce il submit del form
}
