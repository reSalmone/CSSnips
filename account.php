<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: index.php");
}

$redirect = 'account.php';

$username = $_SESSION['username'] ?? '';

$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");
if (!$dbcon) {
  echo "<p>Errore nella connessione al database.</p>";
  exit;
}

$q = "SELECT username, email, bio FROM users WHERE username='$username';";

$result = pg_query($dbcon, $q);
if (!$result) {
  echo "<p>Errore nella query al database.</p>";
  exit;
}

$user = pg_fetch_array($result, NULL, PGSQL_ASSOC);
if (!$user) {
  echo "<p>Utente non trovato.</p>";
  exit;
}
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
  <link rel="stylesheet" href="footer.css">
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
            <?php echo "<h2>" . htmlspecialchars($username) . "</h2>" ?>
            <?php echo "<p>Email:" . htmlspecialchars($user['email']) . "</p>" ?>
            <?php echo "<p>Bio:" . htmlspecialchars($user['bio']) . "</p>" ?>
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
    <?php include 'footer-code.php'; ?> <!--FOOTER-->
  </div>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>