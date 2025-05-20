<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: index.php");
}
$redirect = 'account.php';

$my_username = $_SESSION['username'] ?? '';
$username = $_GET['username'] ?? '';
$dbcon = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=alfonzo1");

$email = null;
$bio = null;
$numero_liked = null;
$numero_saved = null;
$numero_codici = null;
$result5 = null;
$result6 = null;

$found = false;

if ($dbcon != -1) {
  $query1 = "SELECT username, email, bio FROM users WHERE username='$username';";
  $query2 = "SELECT cardinality(likedsnippets) AS numero_stringhe FROM users WHERE username = '$username'";
  $query3 = "SELECT cardinality(savedsnippets) AS numero_stringhe FROM users WHERE username = '$username'";
  $query4 = "SELECT count(*) AS numero_codici FROM snips WHERE creator = '$username'";
  $query5 = "with this as (SELECT * FROM snips WHERE creator = '$username' order by created_at desc) SELECT * FROM this limit 3";
  $query6 = "SELECT * FROM users WHERE username = '$my_username'";
  $result1 = pg_query($query1);
  $result2 = pg_query($query2);
  $result3 = pg_query($query3);
  $result4 = pg_query($query4);
  $result5 = pg_query($query5);
  $result6 = pg_query($query6);

  if ($result1 && $result2 && $result3 && $result4 && $result5 && $result6) {
    $line1 = pg_fetch_array($result1, NULL, PGSQL_ASSOC);
    $line2 = pg_fetch_array($result2, NULL, PGSQL_ASSOC);
    $line3 = pg_fetch_array($result3, NULL, PGSQL_ASSOC);
    $line4 = pg_fetch_array($result4, NULL, PGSQL_ASSOC);
    $found = true;
    $email = $line1['email'];
    $bio = $line1['bio'];
    $numero_liked = $line2['numero_stringhe'];
    $numero_saved = $line3['numero_stringhe'];
    $numero_codici = $line4['numero_codici'];
  }
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
  <script src="assets/scripts/login.js"></script>
  <script src="assets/scripts/signup.js"></script>
  <script src="assets/scripts/explorer.js"></script>
</head>

<body>
  <!-- Header con il nome del sito e il menu a tendina -->
  <?php include 'navbar-code.php'; ?> <!--NAVBAR-->
  <?php include 'login-signup-code.php'; ?> <!--LOGIN AND SIGNUP-->
  <div id="rest" onclick="closeLogin(); closeSignup();">

    <?php
    if ($found) {
      ?>
      <main class="profilo-container">

        <var class="main_var">
          <!-- Sezione delle informazioni dell'utente -->
          <section class="profilo-info">
            <img src="fotoprofilo.jpg" alt="Foto Profilo" class="profilo-img">
            <div class="info-text">
              <?php echo "<h2>" . htmlspecialchars($username) . "</h2>" ?>
              <?php echo "<p>Email:" . htmlspecialchars($email) . "</p>" ?>
              <?php echo "<p>Bio:" . htmlspecialchars($bio) . "</p>" ?>
              <?php
              if ($my_username != $username) {
                /*qua bisogna mettere il pulsante per seguire gli altri utenti*/
                $is_following = false;
                $line6 = pg_fetch_array($result6, NULL, PGSQL_ASSOC);
                $lista = $line6["following"];// PostgreSQL restituisce gli array come stringa tipo: {elem1,elem2,...}
                $lista = trim($lista, '{}'); // Rimuovi le parentesi graffe
                $elements = explode(',', $lista); // Split sugli elementi
            
                foreach ($elements as $item) {
                  if ($item == '$username') {
                    $is_following = true;
                  }
                }
                ?>
                <button id="follow-btn" class="<?php $is_following ? 'following' : 'follow' ?>"
                  onclick="toggleFollow('<?php htmlspecialchars($username) ?>', <?php $is_following ? 'true' : 'false' ?>)">
                  <?php $is_following ? 'Following' : 'Follow' ?>
                </button>
                <?php
              }
              ?>
            </div>
          </section>

          <!-- Recent Activity (tre codici recenti in orizzontale) -->
          <section class="recent-activity">
            <h2>RECENT ACTIVITY</h2>
            <div class="activity-container">
              <?php
              $line5 = pg_fetch_array($result5, NULL, PGSQL_ASSOC);
              if ($line5) {
                while ($line5) {
                  $id = (int) $line5['id'];
                  ?>
                  <div class="output-snip" data-snippet-id="<?= $id ?>">
                    <div class="output-snip-opener"
                      onclick="location.href='snippet.php?name=<?= urlencode($line5['file_location']) ?>';">
                      <span>View code</span>
                    </div>
                    <div class="output-loader" id="output-loader-<?= $id ?>"></div>
                    <iframe id="output-snip-frame-<?= $id ?>" class="output-preview">
                    </iframe>
                    <div class="info">
                      <div class="info-creator">
                        <div class="info-pfp"></div>
                        <span><?= htmlspecialchars($line5['creator']) ?></span>
                      </div>
                      <div class="info-views">
                        <p class="info-text"><?= htmlspecialchars($line5['views']) ?>
                          <span class="info-subtext"> views</span>
                        </p>
                      </div>
                    </div>
                  </div>
                  <?php
                  $line5 = pg_fetch_array($result5, NULL, PGSQL_ASSOC);
                }
              } else {
                echo "<p class='centro'>Non ci sono risultati.</p>";
              }
              ?>
            </div>
          </section>

          <!-- Funzionalità sotto, ciascuna occupa una riga intera -->
          <section class="funzioni-container">
            <div class="funzione-box">
              <div class="funzione-text">Activity</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_codici) ?></div>
            </div>
            <div class="funzione-box">
              <div class="funzione-text">Likes</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_liked ?? 0) ?></div>
            </div>
            <div class="funzione-box">
              <div class="funzione-text">Watchlist</div>
              <div class="funzione-count"><?php echo htmlspecialchars($numero_saved ?? 0) ?></div>
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

      </main> <?php
    } else { ?>
      <main class="profilo-container">
        <var class="main_var">
          <section class="profilo-info">
            <h2>ERROR: UNABLE TO CONNECT TO THE DATABASE. PLEASE TRY AGAIN LATER.</h2>
          </section>
        </var>
      </main>
      <?php
    }
    include 'footer-code.php'; ?> <!--FOOTER-->
  </div>
  <?php
  pg_free_result($result1);
  pg_free_result($result2);
  pg_free_result($result3);
  pg_free_result($result4);
  pg_free_result($result5);
  pg_close($dbcon);
  ?>
</body>
<script src="assets/scripts/login.js"></script>
<script src="assets/scripts/signup.js"></script>

</html>