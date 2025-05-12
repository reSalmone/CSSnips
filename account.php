<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: index.php");
}

$redirect = 'account.php';
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profilo Utente</title>
  <link rel="stylesheet" href="account.css">
  <link rel="stylesheet" href="assets/NoveoSans-Book/style.css">
  <link rel="stylesheet" href="navbar.css">
  <link rel="stylesheet" href="login-signup.css">
  <link rel="stylesheet" href="checkbox.css">
  <script src="account.js"></script>
</head>

<body>

  <!-- Header con il nome del sito e il menu a tendina -->
  <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
  <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
  <div id="rest" onclick="closeLogin(); closeSignup();">


    <main class="profilo-container">

      <var class="main_var">
        <!-- Sezione delle informazioni dell'utente -->
        <section class="profilo-info">
          <img src="fotoprofilo.jpg" alt="Foto Profilo" class="profilo-img">
          <div class="info-text">
            <h2>Nome Utente</h2>
            <p>Email: utente@email.com</p>
            <p>Data iscrizione: 01/01/2024</p>
            <p>Bio: Appassionato di programmazione web.</p>
          </div>
        </section>

        <!-- Recent Activity (quattro codici recenti in orizzontale) -->
        <section class="recent-activity">
          <h2>RECENT ACTIVITY</h2>
          <div class="activity-container">
          </div>
        </section>

        <!-- Funzionalità sotto, ciascuna occupa una riga intera -->
        <section class="funzioni-container">
          <div class="funzione-box">
            <div class="funzione-text">Activity</div>
            <div class="funzione-count">25</div>
          </div>
          <div class="funzione-box">
            <div class="funzione-text">Watchlist</div>
            <div class="funzione-count">10</div>
          </div>
          <div class="funzione-box">
            <div class="funzione-text">Followers</div>
            <div class="funzione-count">100</div>
          </div>
          <div class="funzione-box">
            <div class="funzione-text">Following</div>
            <div class="funzione-count">50</div>
          </div>
        </section>
      </var>

    </main>
  </div>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>