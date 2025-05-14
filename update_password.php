<?php
session_start();

if (!isset($_SESSION["username"])) {
  http_response_code(403);
  echo "Accesso negato";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $newPassword = $_POST["password"] ?? '';
  $username = $_SESSION["username"];

  // Validazione lato server (obbligatoria anche se già fatta in JS)
  if (!preg_match('/^(?=.*\d)[A-Za-z\d]{8,16}$/', $newPassword)) {
    echo "Password non valida.";
    exit;
  }

  // Hash della nuova password
  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  // Connessione al DB
  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1")
    or die("Connessione al DB fallita");

  // Aggiorna la password
  $query = "UPDATE users SET password = $1 WHERE username = $2";
  $result = pg_query_params($conn, $query, array($hashedPassword, $username));

  if ($result) {
    echo "Password aggiornata con successo.";
  } else {
    echo "Errore durante l'aggiornamento.";
  }

  pg_close($conn);
} else {
  http_response_code(405);
  echo "Metodo non consentito.";
}
?>
