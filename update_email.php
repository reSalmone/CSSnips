<?php
session_start();

if (!isset($_SESSION["username"])) {
  http_response_code(403);
  echo "Accesso negato";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"] ?? '';
  $username = $_SESSION["username"];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Email non valida.";
    exit;
  }

  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1")
    or die('Could not connect: ' . pg_last_error());

  $query = "UPDATE users SET email = $1 WHERE username = $2";
  $result = pg_query_params($conn, $query, array($email, $username));

  if ($result) {
    echo "Email aggiornata con successo.";
  } else {
    echo "Errore durante l'aggiornamento.";
  }

  pg_close($conn);
} else {
  http_response_code(405);
  echo "Metodo non consentito.";
}
?>
