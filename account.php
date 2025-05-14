<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: index.php");
}

$redirect = 'account.php';

$username = $_SESSION['username'] ?? '';
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1") or die('Could not connect: ' . pg_last_error());
$query1 = "SELECT username, email, bio FROM users WHERE username='$username';";
$query2 = "SELECT cardinality(likedsnippets) AS numero_stringhe FROM users WHERE username = '$username'";
$query3 = "SELECT cardinality(savedsnippets) AS numero_stringhe FROM users WHERE username = '$username'";
$result1 = pg_query($query1) or die('Query failed: '. pg_last_error());
$result2 = pg_query($query2) or die('Query failed: '. pg_last_error());
$result3 = pg_query($query3) or die('Query failed: '. pg_last_error());

$line1 = pg_fetch_array($result1, NULL, PGSQL_ASSOC);
$line2 = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
$line3 = pg_fetch_array($result3, NULL, PGSQL_ASSOC);

if (!$line1 or !$line2 or !$line3) {
  echo "<p>Could not find username.</p>";
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
            <?php echo "<p>Email:" . htmlspecialchars($line1['email']) . "</p>" ?>
            <?php echo "<p>Bio:" . htmlspecialchars($line1['bio']) . "</p>" ?>
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
            <div class="funzione-text">Likes</div>
            <div class="funzione-count"><?php echo htmlspecialchars($line2['numero_stringhe']) ?></div>
          </div>
          <div class="funzione-box">
            <div class="funzione-text">Watchlist</div>
            <div class="funzione-count"><?php echo htmlspecialchars($line3['numero_stringhe']) ?></div>
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
  <?php
    pg_free_result($result1);
    pg_free_result($result2);
    pg_free_result($result3);
    pg_close($dbcon);
    ?>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>