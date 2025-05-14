<?php
session_start();

if (!isset($_SESSION["username"])) {
  http_response_code(403);
  echo "Accesso negato.";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $newUsername = trim($_POST["username"] ?? '');
  $newBio = trim($_POST["bio"] ?? '');
  $oldUsername = $_SESSION["username"];

  if ($newUsername === "") {
    echo "Lo username non può essere vuoto.";
    exit;
  }

  $conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1")
    or die("Connessione al DB fallita");

  $query = "UPDATE users SET username = $1, bio = $2 WHERE username = $3";
  $result = pg_query_params($conn, $query, array($newUsername, $newBio, $oldUsername));

  if ($result) {
    // Aggiorna lo username anche nella sessione, se è cambiato
    $_SESSION["username"] = $newUsername;
    echo "Account aggiornato con successo.";
  } else {
    echo "Errore durante l'aggiornamento.";
  }

  pg_close($conn);
} else {
  http_response_code(405);
  echo "Metodo non consentito.";
}
?>
